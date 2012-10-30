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
require_once (JPATH_BASE . DS."media".DS."com_allplayers".DS.'libraries'.DS.'vendor'.DS.'autoload.php');
include_once(JPATH_BASE . DS."components".DS."com_allplayers".DS."helper.php");

class allplayersViewauth extends Jview{

	function __construct(){
		parent::__construct();
		$this->session = JFactory::getSession();
	}
	

	public function display($tpl = null) {
        $helper = new ComAllPlayersHelper();
        $app = JFactory::getApplication();
        $apUser = $helper->getCredentials();
        $jUser = $helper->getJoomlaAllPlayersUser();
        
        $allplayersSession = $this->session->get('com_allplayers_credentials');

        if (!$apUser){
        	$helper->initLogin();
        }

		//I have a joomla user
        if (isset($apUser)){
        	$currentuser = JFactory::getUser();
        	if (!isset($currentuser)){
                $app->redirect(JRoute::_('index.php?option=com_allplayers&controller=auth&task=login'));
            }
        	
			$this->assign('userLoggedIn', 'true');
        	parent::display($tbl);
        //I do not have a joomla user
        } else {
        	$app->redirect(JRoute::_('index.php?option=com_allplayers&controller=auth&view=mapping&task=mapping'));
        }
	    parent::display($tbl);
	}
	
}
