<?php 

defined('_JEXEC') or die('Restricted access');

use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Plugin\OauthPlugin;
use AllPlayers\AllPlayersClient;

require_once (JPATH_BASE.DS.'media'.DS.'com_allplayers'.DS.'libraries'.DS.'vendor'.DS.'autoload.php');

class ComAllPlayersHelper {
  private $libspath;
  private $consumer;
  private $token;

  function __construct() {
    $this->libspath = JPATH_BASE.DS.'media'.DS.'com_allplayers'.DS.'libraries'.DS;
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

  function getUserMapping($apid = null) {
    if (!$apid){
        $userInfo = $this->getCredentials();
        $apid = $userInfo->apid;
    }
    $query = 'SELECT * FROM #__allplayers_auth_mapping WHERE allplayersid="'.$apid.'"';
    $this->db->setQuery($query);
    $mapping = $this->db->loadObject(); 
    return $mapping;
  }

  function getJoomlaAllPlayersUser($apid = null){
    $user = null;
    if (!$apid){
        if ($this->areCookiesSet()){
            $apid = $_COOKIE['user_apid'];
        } else {
            $session = $this->session->get('com_allplayers_credentials');
            if (isset($session) && isset($session->user)){
                $apid = $session->user->apid;
            } else{
                $this->initLogin();
            }
        } 
    }

    if ($apid){
        $query = 'SELECT u.*, aam.allplayersid FROM #__users u INNER JOIN #__allplayers_auth_mapping aam ON aam.userid = u.id WHERE aam.allplayersid = "'.$apid.'"';
        $this->db->setQuery($query);
        $user = $this->db->loadObject();
    } 
    if (!$user){
        $apUser = $this->getCredentials();
        $query = 'SELECT * FROM #__users u WHERE u.username = "'.$apUser->email.'"';
        $this->db->setQuery($query);
        $jUser = $this->db->loadObject();
        //We have a matching joomla user but no mapping. Mapit.
        if (isset($jUser)){
            $this->setUserMapping($apUser, $jUser->id);
        }
        return $this->getJoomlaAllPlayersUser($apUser->apid);
    }
    return $user;
  }

  function setUserMapping($userInfo = null, $jUserId) {
    if (!$userInfo){
        $userInfo = $this->getCredentials();
    }

    $query = 'INSERT INTO #__allplayers_auth_mapping VALUES(DEFAULT, "'.$userInfo->apid.'", "'.$jUserId.'")';
    $this->db->setQuery($query);
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
        $authToken = null;
        $authSecret = null;
        if ($this->areCookiesSet()){
            $authToken = $_COOKIE['apoauth_token'];
            $authSecret = $_COOKIE['apoauth_token_secret'];
        } else {
            $authToken = $this->session->set('auth_token');
            $authSecret = $this->session->get('auth_secret');
        }

       if ($authToken == null || $authSecret == null){
        return false;
       }

        $client = AllPlayersClient::factory(array(
            'auth' => 'oauth',
            'oauth' => array(
                'consumer_key'    => $this->consumer->key,
                'consumer_secret' => $this->consumer->secret,
                'token'           => $authToken,
                'token_secret'    => $authSecret
            ),
            'host' => parse_url($this->consumer->oauthurl, PHP_URL_HOST),
            'curl.CURLOPT_SSL_VERIFYPEER' => isset($this->consumer->verifypeer) ? $this->consumer->verifypeer : TRUE,
            'curl.CURLOPT_CAINFO' => $this->libspath.'assets/mozilla.pem',
            'curl.CURLOPT_FOLLOWLOCATION' => FALSE
        ));

        $response = $client->get('users/current.json')->send();
        // Note: getLocation returns full URL info, but seems to work as a request in Guzzle
        
        $response = $client->get($response->getLocation())->send();
       
        $user = json_decode($response->getBody(TRUE));
        
        $ui = new stdClass();
        $ui->apid = $user->uuid;
        $ui->email = $user->email;
        $ui->profile_image_url = $user->picture;
        $ui->username = $user->username;
        $ui->nickname = $user->nickname;
        
        $user_credentials = new stdClass();
        $user_credentials->user = $ui;
        $user_credentials->oauth_token = $authToken;
        $user_credentials->oauth_token_secret = $authSecret;

        $this->session->set('com_allplayers_credentials', $user_credentials);
        $cookie_expire_time = time()+60*60*24*30*3; //60 days
        setcookie('user_apid', $ui->apid, $cookie_expire_time);
        setcookie('apoauth_token', $user_credentials->oauth_token, $cookie_expire_time);
        setcookie('apoauth_token_secret', $user_credentials->oauth_token_secret, $cookie_expire_time);

        $userInfo = $ui;
      } catch(Exception $e){
        throw $e;
      }
    }
    return $userInfo;
  }

  function areCookiesSet() {
    if (isset($_COOKIE['apoauth_token']) && isset($_COOKIE['apoauth_token_secret']) && isset($_COOKIE['user_apid'])) {
      return true;
    } else {
      $this->clearCookies();
      return false;
    }
  }

    public function clearCookies() {
        setcookie('apoauth_token', '', 1);
        setcookie('apoauth_token_secret', '', 1);
        setcookie('user_apid', '', 1);
    }


    public function doLogin($oauth_token, $oauth_secret) {

        $client = new Client($this->consumer->oauthurl . '/oauth', array(
            'curl.CURLOPT_SSL_VERIFYPEER' => isset($this->consumer->verifypeer) ? $this->consumer->verifypeer : TRUE,
            'curl.CURLOPT_CAINFO' => $this->libspath.'assets/mozilla.pem',
            'curl.CURLOPT_FOLLOWLOCATION' => FALSE,
        ));

        $oauth = new OauthPlugin(array(
            'consumer_key' => $this->consumer->key,
            'consumer_secret' => $this->consumer->secret,
            'token' => $oauth_token,
            'token_secret' => $oauth_secret,
        ));

        $client->addSubscriber($oauth);

        $response = $client->get('access_token')->send();

        // Parse oauth tokens from response object
        $access_tokens = array();
        parse_str($response->getBody(TRUE), $access_tokens);
        $this->session->set('auth_token', $access_tokens['oauth_token']);
        $this->session->set('auth_secret', $access_tokens['oauth_token_secret']);
       
        if (!empty($access_tokens['oauth_token']) && !empty($access_tokens['oauth_token_secret'])) {
            return $this->getCredentials();
        }
        
    }

    //TODO: Move to helpers
    public function initLogin(){
        $app = JFactory::getApplication();
        $uri = JFactory::getURI();
        $uriInstance = JURI::getInstance( $uri->toString() );

        $this->db->setQuery('SELECT * FROM #__allplayers_auth');
        $consumer = $this->db->loadObject();
        
        if (function_exists('curl_init')) {
            $client = new Client($consumer->oauthurl . '/oauth', array(
                 'curl.CURLOPT_SSL_VERIFYPEER' => isset($consumer->verifypeer) ? $consumer->verifypeer : TRUE,
                 'curl.CURLOPT_CAINFO' =>  $this->libspath.'assets'.DS.'mozilla.pem',
                 'curl.CURLOPT_FOLLOWLOCATION' => FALSE,
            ));

            $oauth = new OauthPlugin(array(
                'consumer_key' => $consumer->key,
                'consumer_secret' => $consumer->secret,
                'token' => FALSE,
                'token_secret' => FALSE,
            ));
            
            // if $request path !set then set to request_token
            $timestamp = time();
            $params = $oauth->getParamsToSign($client->get('request_token'), $timestamp);
            $params['oauth_signature'] = $oauth->getSignature($client->get('request_token'), $timestamp);
            $response = $client->get('request_token?' . http_build_query($params))->send();

            // Parse oauth tokens from response object
            $oauth_tokens = array();
            parse_str($response->getBody(TRUE), $oauth_tokens);
            $this->session->set('access_token', $oauth_tokens['oauth_token']);
            $this->session->set('access_secret', $oauth_tokens['oauth_token_secret']);
           
            $authorize = '/oauth/authorize?oauth_token=' . $oauth_tokens['oauth_token'];
            $authorize .= '&oauth_callback=' . urlencode($uri->toString().'&task=callback');
 
            $app->redirect($consumer->oauthurl . $authorize, null, null, true, true);
        }
        return false;
    }

}

?>