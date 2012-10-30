<?php
/**
 * @version 0.0.1
 * @package com_allplayers
 * @author Zach Curtis, Wayin Inc
 * @author mail info@wayin.com
 * @copyright Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license   GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
include_once(JPATH_COMPONENT.DS."helper.php");

class AllPlayersControllerProfile extends JController {
    protected $data;

    function __construct() {
        parent::__construct();
        $this->config = JFactory::getConfig();
        $uri = JFactory::getURI();
        $this->baseurl = $uri->toString();
        $this->db = JFactory::getDBO();
        $this->session = JFactory::getSession();
        $this->helper = new ComAllPlayersHelper();
    }
    public function login(){
        return $this->logUserIn($this->helper->getCredentials());
    }
    private function logUserIn($userInfo){
        $app = JFactory::getApplication();
        // Get the log in credentials.
        $credentials = array();
        $credentials['apid'] = $userInfo->apid;
        $credentials['username'] = $userInfo->email;
        $credentials['password'] = 'stuff'; //password cannot be blank for joomla but is not needed for all-players
        // Perform the log in.
        return $app->login($credentials);
    }
    
    public function display($cachable = false, $urlparams = false) {
        parent::display($cachable, $urlparams);
    }

}
