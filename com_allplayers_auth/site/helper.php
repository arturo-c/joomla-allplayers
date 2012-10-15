<?php 

defined('_JEXEC') or die('Restricted access');

include(JPATH_BASE . "/components/com_twitter/twitter-async/EpiCurl.php");
include(JPATH_BASE . "/components/com_twitter/twitter-async/EpiOAuth.php");
include(JPATH_BASE . "/components/com_twitter/twitter-async/EpiTwitter.php");

class ComTwitterHelper {
  
  private $consumer;
  private $token;

  function __construct() {
    $this->db = JFactory::getDBO();
    $this->db->setQuery('select * from #__twitter_consumer');
    $consumer = $this->db->loadObject();
    if (!$consumer) throw new Exception('ComTwitterNotInstalledOrConfigured');
    $this->consumer = $consumer;
  }

  function getJoomlaUserMapping($id) {
    $this->db->setQuery(
      sprintf('select * from #__twitter_mapping where userid="%s"', 
        $this->db->getEscaped($id)
      )
    );
    $mapping = $this->db->loadObject();
    return $mapping;
  }

  function getUserMapping() {
    $twitterInfo = $this->getCredentials();
    $this->db->setQuery(
      sprintf('select * from #__twitter_mapping where twitterid="%s"', 
        $this->db->getEscaped($twitterInfo->id)
      )
    );
    $mapping = $this->db->loadObject();
    return $mapping;
  }

  function setUserMapping() {
    $twitterInfo = $this->getCredentials();
    $twitter_userid = $twitterInfo->id;
		$user = JFactory::getUser();	
    return $this->db->execute(sprintf("insert into #__twitter_mapping values(DEFAULT, '%s', '%s')", $twitter_userid, $user->id));
  }

  function getCredentials() {
    if ( isset($_SESSION['com_twitter_credentials']) && 
          $_SESSION['com_twitter_credentials']->oauth_token == $_COOKIE['oauth_token'] &&
          $_SESSION['com_twitter_credentials']->oauth_token_secret == $_COOKIE['oauth_token_secret'] &&
          $_SESSION['com_twitter_credentials']->twitterInfo->timeout > time()
    ) {
      $twitterInfo = $_SESSION['com_twitter_credentials']->twitterInfo;
    } else {
      try {
        $twitterInfo = null;
        $twitterObj = new EpiTwitter(
          $this->consumer->key, 
          $this->consumer->secret, 
          $this->token->oauth_token, 
          $this->token->oauth_token_secret
        );
        $twitterInfo = $twitterObj->get_accountVerify_credentials();
        
        $ti = new stdClass();
        $ti->timeout = time() + 20 * 60; //(20 minutes of timeout)
        $ti->name = $twitterInfo->name;
        $ti->screen_name = $twitterInfo->screen_name;
        $ti->status = $twitterInfo->status;
        $ti->id = $twitterInfo->id;
        $ti->profile_image_url = $twitterInfo->profile_image_url;
        
        $twitter_credentials = new stdClass();
        $twitter_credentials->twitterInfo = $ti;
        $twitter_credentials->oauth_token = $this->token->oauth_token;
        $twitter_credentials->oauth_token_secret = $this->token->oauth_token_secret;

        $_SESSION['com_twitter_credentials'] = $twitter_credentials;
        $twitterInfo = $ti;
      } catch(Exception $e){
        throw $e;
      }
    }
    return $twitterInfo;
  }

  function areCookiesSet() {
    if ($_COOKIE['oauth_token'] && $_COOKIE['oauth_token_secret']) {
      return true;
    } else {
      $this->clearCookies();
      return false;
    }
  }

  function clearCookies() {
    setcookie('oauth_token', '', 1);
    setcookie('oauth_token_secret', '', 1);
  }

  function getAuthenticateUrl() {
    $twitterObj = new EpiTwitter($this->consumer->key, $this->consumer->secret);
    return $twitterObj->getAuthenticateUrl();
  }

  function doLogin() {
    $twitterObj = new EpiTwitter($this->consumer->key, $this->consumer->secret);
    $twitterObj->setToken($_GET['oauth_token']);
    $token = $twitterObj->getAccessToken();
    $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);

    // save to cookies
    setcookie('oauth_token', $token->oauth_token, 0, '/' );
    setcookie('oauth_token_secret', $token->oauth_token_secret, 0, '/');
    $this->token = $token;
    return $this->getCredentials();
  }
}
?>
