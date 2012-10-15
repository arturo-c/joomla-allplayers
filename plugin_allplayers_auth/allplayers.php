<?php

/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * Written by Zach Curtis, Wayin Inc 2012
 * Libs provided by All-Players
 */

// No direct access
defined('_JEXEC') or die;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Plugin\OauthPlugin;
use AllPlayers\AllPlayersClient;

require_once __DIR__.'/vendor/autoload.php';

jimport( 'joomla.environment.uri' );
jimport( 'joomla.plugin.plugin' );


/**
 * All-Players Authentication Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Authentication.allplayers
 * @since 2.5
 */
class plgAuthenticationAllPlayers extends JPlugin {
	private $_app;
	//private $_p = 'a';
	function plgAuthenticationAllPlayers(&$subject, $config = array()){
		parent::__construct($subject, $config);
	}
       
    function onAfterDispatch() {
    	global $LoginModule;
        $app =& JFactory::getApplication();
        if ( $app->getName() != 'site' ) return true;   
        if ( $app->getCfg('offline') ) return true;		
        $document =& JFactory::getDocument();
        if ( $document->getType() != 'html' ) return true; 
    }
	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array	$credentials Array holding the user credentials
	 * @param	array   $options	Array of extra options
	 * @param	object	$response	Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onUserAuthenticate($credentials, $options, & $response) {
		$message = '';
		$success = 0;

		if (empty($credentials['password'])) {
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JGLOBAL_AUTH_PASS_BLANK');
			return false;
		}
		$consumer_key = $this->params->get('consumer_key');
		$consumer_secret = $this->params->get('consumer_secret');
		$verify_peer = $this->params->get('verify_peer');
		$auth_domain = $this->params->get('auth_domain');
		// check if we have curl or not
		if (function_exists('curl_init')) {
		 	$client = new Client($auth_domain . '/oauth', array(
                 'curl.CURLOPT_SSL_VERIFYPEER' => isset($verify_peer) ? $verify_peer : TRUE,
                 'curl.CURLOPT_CAINFO' =>  __DIR__.'/assets/mozilla.pem',
                 'curl.CURLOPT_FOLLOWLOCATION' => FALSE,
            ));

		 	$oauth = new OauthPlugin(array(
                'consumer_key' => $consumer_key,
                'consumer_secret' => $consumer_secret,
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

       		$access_token = $oauth_tokens['oauth_token'];
        	$access_secret = $oauth_tokens['oauth_token_secret'];
        	
        	if ($access_token == null) {
				$message = JText::_('JGLOBAL_AUTH_ACCESS_DENIED');
        	}

	     	$authorize = '/oauth/authorize?oauth_token=' . $access_token;
        	$authorize .= '&oauth_callback=' . urlencode(JURI::getScheme() . '://' . JURI::getHost() . '/auth');

			// $client = new Client($auth_domain . '/oauth', array(
			// 	'curl.CURLOPT_SSL_VERIFYPEER' => isset($verify_peer) ? $verify_peer : TRUE,
			// 	'curl.CURLOPT_CAINFO' => __DIR__.'/assets/mozilla.pem',
			// 	'curl.CURLOPT_FOLLOWLOCATION' => FALSE,
			// ));

			// $oauth = new OauthPlugin(array(
			// 	'consumer_key' => $consumer_key,
			// 	'consumer_secret' => $consumer_secret ,
			// 	'token' => $access_token,
			// 	'token_secret' => $access_secret,
			// ));


   //      	$client->addSubscriber($oauth);

   //      	$response = $client->get('access_token')->send();

		} else {
			$message = 'curl isn\'t insalled';
		}

		$response->type = 'Twitter';
        // if ($success) {
        //         JFactory::getApplication()->enqueueMessage('Success Twitter login','message');
        //         $response->status        = JAuthentication::STATUS_SUCCESS;
        //         $response->error_message = '';
        //         $response->email         = $userdata[2][1];
        //         $response->username      = $userdata[2][0];
        //         $response->fullname      = $userdata[2][0];
        // }  else  {
        //         $response->status         = JAuthentication::STATUS_FAILURE;
        //         $response->error_message  = JText::sprintf('JGLOBAL_AUTH_FAILED', $message);
        // }

		$response->error_message = $message;
	}
}
