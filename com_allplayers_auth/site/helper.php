<?php 

defined('_JEXEC') or die('Restricted access');

use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Plugin\OauthPlugin;
use AllPlayers\AllPlayersClient;

require_once (JPATH_COMPONENT.DS.'vendor/autoload.php');

class ComAllPlayersHelper {
  
  private $consumer;
  private $token;

  function __construct() {
    $this->db = JFactory::getDBO();
    $this->db->setQuery('select * from #__allplayers_auth');
    $consumer = $this->db->loadObject();
    if (!$consumer) throw new Exception('ComAllPlayersAuthNotInstalledOrConfigured');
    $this->consumer = $consumer;
    $this->session = JFactory::getSession();
  }

  function getJoomlaUserMapping($id) {
    $this->db->setQuery(
      sprintf('select * from #__allplayers_auth_mapping where userid="%s"', 
        $this->db->getEscaped($id)
      )
    );
    $mapping = $this->db->loadObject();
    return $mapping;
  }

  function getUserMapping() {
    $twitterInfo = $this->getCredentials();
    $this->db->setQuery(
      sprintf('select * from #__allplayers_auth_mapping where twitterid="%s"', 
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
    return $this->db->execute(sprintf("insert into #__allplayers_auth_mapping values(DEFAULT, '%s', '%s')", $twitter_userid, $user->id));
  }

  function getCredentials() {
    if ( isset($_SESSION['com_allplayers_credentials']) && 
          $_SESSION['com_allplayers_credentials']->oauth_token == $_COOKIE['oauth_token'] &&
          $_SESSION['com_allplayers_credentials']->oauth_token_secret == $_COOKIE['oauth_token_secret'] &&
          $_SESSION['com_allplayers_credentials']->twitterInfo->timeout > time()
    ) {
      $twitterInfo = $_SESSION['com_allplayers_credentials']->twitterInfo;
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

        $_SESSION['com_allplayers_credentials'] = $twitter_credentials;
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

  function doLogin($consumer, $oauth_token, $secret) {
    $client = new Client($consumer->oauthurl . '/oauth', array(
        'curl.CURLOPT_SSL_VERIFYPEER' => isset($consumer->verifypeer) ? $consumer->verifypeer : TRUE,
        'curl.CURLOPT_CAINFO' => 'assets/mozilla.pem',
        'curl.CURLOPT_FOLLOWLOCATION' => FALSE
      ));

    $oauth = new OauthPlugin(array(
        'consumer_key' => $consumer->key,
        'consumer_secret' => $consumer->secret,
        'token' => $oauth_token,
        'token_secret' => $secret
    ));

    $client->addSubscriber($oauth);

    $response = $client->get('access_token')->send();

    $oauth_tokens = array();
    parse_str($response->getBody(TRUE), $oauth_tokens);
    $this->session->set('auth_token', $oauth_tokens['oauth_token']);
    $this->session->set('auth_secret', $oauth_tokens['oauth_token_secret']);
    $token = $oauth_tokens['oauth_token'];
    $secret = $oauth_tokens['oauth_token_secret'];

    if (!empty($token) && !empty($secret)) {
      $client = AllPlayersClient::factory(array(
          'auth' => 'oauth',
          'oauth' => array(
            'consumer_key' => $this->session->get('consumer_key'),
            'consumer_secret' => $this->session->get('consumer_secret'),
            'token' => $token,
            'token_secret' => $secret
          ),
          'host' => parse_url($this->consumer->oauthurl, PHP_URL_HOST),
          'curl.CURLOPT_SSL_VERIFYPEER' => isset($this->consumer->verifypeer) ? $this->consumer->verifypeer : TRUE,
          'curl.CURLOPT_CAINFO' => __DIR__.'assets/mozilla.pem',
          'curl.CURLOPT_FOLLOWLOCATION' => FALSE
        )
      );
      $response = $client->get('users/current.json')->send();
      // Note: getLocation returns full URL info, but seems to work as a request in Guzzle
      $response = $client->get($response->getLocation())->send();
      $user = json_decode($response->getBody(TRUE));
      $this->session->set('user_uuid', $user->uuid);

      return $user;
    }
    // $twitterObj = new EpiTwitter($this->consumer->key, $this->consumer->secret);
    // $twitterObj->setToken($_GET['oauth_token']);
    // $token = $twitterObj->getAccessToken();
    // $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);

    // // save to cookies
    // setcookie('oauth_token', $token->oauth_token, 0, '/' );
    // setcookie('oauth_token_secret', $token->oauth_token_secret, 0, '/');
    // $this->token = $token;
    //return $this->getCredentials();
  }

}
?>
