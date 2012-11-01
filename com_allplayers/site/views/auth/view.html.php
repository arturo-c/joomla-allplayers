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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Plugin\OauthPlugin;
use AllPlayers\AllPlayersClient;
require_once (JPATH_BASE . DS."media".DS."com_allplayers".DS.'libraries'.DS.'vendor'.DS.'autoload.php');
include_once(JPATH_BASE . DS."components".DS."com_allplayers".DS."helper.php");

class allplayersViewauth extends Jview{

	function __construct(){
		parent::__construct();
	}
	
	public function display($tpl = null) {
        $helper = new ComAllPlayersHelper();
        $app = JFactory::getApplication();
        $apUser = $helper->getCredentials();
        $jUser = $helper->getJoomlaAllPlayersUser();
        
        if (!$apUser){
        	$helper->initLogin();
        }
     
        if (isset($apUser)){
            //no mapped user, check for joomla user.
            if ($jUser){
                $existingJUser = $helper->mapExistingJoomlaUser($apUser->email);
            } else {
                $app->redirect(JRoute::_('index.php?option=com_allplayers&controller=auth&view=mapping&task=mapping'));
            }
        	
			$this->assign('userLoggedIn', true);
        	parent::display($tbl);
        //I do not have a joomla user
        } else {
        	//$app->redirect(JRoute::_('index.php?option=com_allplayers&controller=auth&view=mapping&task=mapping'));
        }
	    parent::display($tbl);
	}
	
}
