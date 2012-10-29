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

class allplayersViewallplayersprofile extends Jview{

	function __construct(){
		parent::__construct();
		$this->session = JFactory::getSession();
	}
	
	public function display($tpl = null) {
        $helper = new ComAllPlayersHelper();
        $app = JFactory::getApplication();
        parent::display($tbl);
	}
	
}
