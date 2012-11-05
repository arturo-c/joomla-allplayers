<?php
/**
 * @version	1.0
 * @package	allplayers_auth
 * @author Zach Curtis, Wayin Inc
 * @author mail	info@wayin.com
 * @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Plugin\OauthPlugin;
use AllPlayers\AllPlayersClient;

require_once (JPATH_BASE.DS.'media'.DS.'com_allplayers'.DS.'libraries'.DS.'vendor'.DS.'autoload.php');
include_once(JPATH_COMPONENT.DS."helper.php");

class allplayersViewprofile extends Jview{

	function __construct(){
		$this->libspath = JPATH_BASE.DS.'media'.DS.'com_allplayers'.DS.'libraries'.DS;
		parent::__construct();
		$this->session = JFactory::getSession();
		$this->helper = new ComAllPlayersHelper();
	}
	
	public function display($tpl = null) {
        
        $app = JFactory::getApplication();
        $model = $this->getModel();
        $consumer = $model->getConsumer();
        $userId = null;
        $authToken = null;
        $authSecret = null;
        if ($this->helper->areCookiesSet()){
            $authToken = $_COOKIE['apoauth_token'];
            $authSecret = $_COOKIE['apoauth_token_secret'];
        } else {
            $authToken = $this->session->set('auth_token');
            $authSecret = $this->session->get('auth_secret');
        }

        if (!$authToken || !$authSecret){
        	$this->assign('userLoggedIn', false);
        } else {
	        $verifypeer = $consumer->verifypeer;
	        if (!isset($_COOKIE['user_apid'])){
	        	$userInfo = $this->helper->getCredentials();
	        	$userId = $userInfo->apid;
	        } else {
	        	$userId = $_COOKIE['user_apid'];
	        }

	        $client = AllPlayersClient::factory(array(
	            'auth' => 'oauth',
	            'oauth' => array(
	                'consumer_key'    => $consumer->key,
	                'consumer_secret' => $consumer->secret,
	                'token'           => $authToken,
	                'token_secret'    => $authSecret
	            ),
	            'host' => parse_url($consumer->oauthurl.'/api/v1/rest', PHP_URL_HOST),
	            'curl.CURLOPT_SSL_VERIFYPEER' => isset($verifypeer) ? $verifypeer : TRUE,
	            'curl.CURLOPT_CAINFO' => $this->libspath.'/assets/mozilla.pem',
	            'curl.CURLOPT_FOLLOWLOCATION' => FALSE
	        ));
	        $response = $client->get('users/'.$userId.'.json')->send();
	       
	        $user = json_decode($response->getBody(TRUE));
	    	
	        if (!isset($user)){
	        	error_log("Error: Could not load user profile!");
	            $this->_subject->setError('Could not load user profile.');
	            return false;
	        }

	        //$response = $client->get('users/'.$userId.'/events.json')->send();
        	$this->assign('userLoggedIn', true);
        	$this->assign('user', $user);
    	}
   
        parent::display($tbl);
	}
	
}
