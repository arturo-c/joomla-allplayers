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
    $this->db->setQuery('SELECT * FROM #__allplayers_auth');
    $consumer = $this->db->loadObject();
    if (!$consumer) throw new Exception('ComAllPlayersAuthNotInstalledOrConfigured');
    $this->consumer = $consumer;
    $this->session = JFactory::getSession();
  }

  function getJoomlaUserMapping($id) {
    $this->db->setQuery('SELECT * FROM #__allplayers_auth_mapping WHERE userid="'.$id.'"');
    $mapping = $this->db->loadObject();
    return $mapping;
  }

  function getUserMapping($userInfo = null) {
    if (!$userInfo){
        $userInfo = $this->getCredentials();
    }
    $query = 'SELECT * FROM #__allplayers_auth_mapping WHERE allplayersid="'.$userInfo->id.'"';
    $this->db->setQuery($query);
    $mapping = $this->db->loadObject(); 
    return $mapping;
  }

  function setUserMapping($userInfo = null, $jUserId) {
    if (!$userInfo){
        $userInfo = $this->getCredentials();
    }

    $query = 'INSERT INTO #__allplayers_auth_mapping VALUES(DEFAULT, "'.$userInfo->id.'", "'.$jUserId.'")';
     $this->db->setQuery($query);
    error_log("User ID (setUserMappiong): " .$query );
    return $this->db->query();
  }

  function getCredentials() {
    $userInfo = null;

    $allplayersSession = $this->session->get('com_allplayers_credentials');
   
    if ( isset($allplayersSession)) {
        if ($allplayersSession->oauth_token && $allplayersSession->user /*== $_COOKIE['oauth_token'] && $allplayersSession->oauth_token_secret == $_COOKIE['oauth_token_secret']*/){
            $userInfo = $allplayersSession->user;
        }
    } else {
      try {
        $client = AllPlayersClient::factory(array(
            'auth' => 'oauth',
            'oauth' => array(
                'consumer_key' => $this->consumer->key,
                'consumer_secret' => $this->consumer->secret,
                'token' => $this->session->get('auth_token'),
                'token_secret' => $this->session->set('auth_secret')
            ),
            'host' => parse_url($this->consumer->oauthurl, PHP_URL_HOST),
            'curl.CURLOPT_SSL_VERIFYPEER' => isset($this->consumer->verifypeer) ? $this->consumer->verifypeer : TRUE,
            'curl.CURLOPT_CAINFO' => __DIR__.'assets/mozilla.pem',
            'curl.CURLOPT_FOLLOWLOCATION' => FALSE
        ));

        $response = $client->get('users/current.json')->send();
        // Note: getLocation returns full URL info, but seems to work as a request in Guzzle
        
        $response = $client->get($response->getLocation())->send();
       
        $user = json_decode($response->getBody(TRUE));
        
        $ui = new stdClass();
        $ui->id = $user->uuid;
        $ui->email = $user->email;
        $ui->profile_image_url = $user->picture;
        $ui->username = $user->username;
        $ui->nickname = $user->nickname;
        
        $user_credentials = new stdClass();
        $user_credentials->user = $ui;
        $user_credentials->oauth_token = $this->session->get('auth_token');
        $user_credentials->oauth_token_secret = $this->session->get('auth_secret');

        $this->session->set('com_allplayers_credentials', $user_credentials);
        $_COOKIE['oauth_token'] = $user_credentials->oauth_token;
        $_COOKIE['oauth_token_secret'] = $user_credentials->oauth_token_secret;
        $userInfo = $ui;
      } catch(Exception $e){
        throw $e;
      }
    }
    return $userInfo;
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


    function doLogin($oauth_token, $secret) {

        $client = new Client($this->consumer->oauthurl . '/oauth', array(
            'curl.CURLOPT_SSL_VERIFYPEER' => isset($this->consumer->verifypeer) ? $this->consumer->verifypeer : TRUE,
            'curl.CURLOPT_CAINFO' => __DIR__.'assets/mozilla.pem',
            'curl.CURLOPT_FOLLOWLOCATION' => FALSE,
        ));

        $oauth = new OauthPlugin(array(
            'consumer_key' => $this->consumer->key,
            'consumer_secret' => $this->consumer->secret,
            'token' => $oauth_token,
            'token_secret' => $secret,
        ));
        $client->addSubscriber($oauth);

        $response = $client->get('access_token')->send();

        // Parse oauth tokens from response object
        $access_tokens = array();
        parse_str($response->getBody(TRUE), $access_tokens);
        $this->session->set('auth_token', $access_tokens['oauth_token']);
        $this->session->set('auth_secret', $access_tokens['oauth_token_secret']);
        //$this->token->oauth_token = $access_tokens['oauth_token'];
        //$this->token->oauth_token_secret = $access_tokens['oauth_token_secret'];

        if (!empty($access_tokens['oauth_token']) && !empty($access_tokens['oauth_token_secret'])) {
            return $this->getCredentials();
        }
        
    }

}

?>