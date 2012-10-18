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

#use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Plugin\OauthPlugin;
use AllPlayers\AllPlayersClient;
require_once (JPATH_COMPONENT.DS.'vendor/autoload.php');
include_once(JPATH_COMPONENT.DS."helper.php");

class allplayersViewallplayers extends Jview{

	function __construct(){
		parent::__construct();
	}
	
	function display($tpl = null) {
		$userLoggedIn = 'false';
		$session = JFactory::getSession();
		$helper = new ComAllPlayersHelper();
		$allplayersSession = $session->get('com_allplayers_credentials');
		if (isset($allplayersSession)){
			$apUser = $helper->getCredentials();
		    $jUser = JFactory::getUser();
		    
		    if ($apUser->username == $jUser->username){
				$userLoggedIn = 'true';
			}
		} else {
		
			$app = JFactory::getApplication();
			$uri = JFactory::getURI();
			$uriInstance = JURI::getInstance( $uri->toString() );
			
			$db = JFactory::getDBO();
			$db->setQuery('SELECT * FROM #__allplayers_auth');
	        $consumer = $db->loadObject();
	       	//$this->assignRef('consumer', $consumer);
			
			if (function_exists('curl_init')) {
			 	$client = new Client($consumer->oauthurl . '/oauth', array(
	                 'curl.CURLOPT_SSL_VERIFYPEER' => isset($consumer->verifypeer) ? $consumer->verifypeer : TRUE,
	                 'curl.CURLOPT_CAINFO' =>  __DIR__.'/assets/mozilla.pem',
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
	    	    $session->set('access_token', $oauth_tokens['oauth_token']);
	    	    $session->set('access_secret', $oauth_tokens['oauth_token_secret']);
	    	   

	    	    $authorize = '/oauth/authorize?oauth_token=' . $oauth_tokens['oauth_token'];
	        	$authorize .= '&oauth_callback=' . urlencode($uri->toString().'&task=callback');

	        	$app->redirect($consumer->oauthurl . $authorize, null, null, true, true);
		        $app->enqueueMessage( 'Load: '.$authorize); 
			}
		}
		$this->assign('userLoggedIn', $userLoggedIn);
		parent::display($tpl);	
	
	}
	
}
