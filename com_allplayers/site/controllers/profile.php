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
include_once(JPATH_BASE . "/components/com_allplayers/helper.php");

class ProfileController extends AppController {
    protected $data;

    function __construct() {
        parent::__construct();
        $this->config = JFactory::getConfig();
        $uri = JFactory::getURI();
        $this->baseurl = $uri->toString();
        $this->db = JFactory::getDBO();
        $this->session = JFactory::getSession();
    }
    public function login(){
        $helper = new ComAllPlayersHelper();
        return $this->logUserIn($helper->getCredentials());
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
    public function allplayers_profile(){
        require_once (JPATH_COMPONENT.DS.'views'.DS.'allplayersprofile'.DS.'view.html.php');
        $view = new allplayersauthViewallplayersauth();
        $view->display();
    }
    public function close(){
        require_once (JPATH_COMPONENT.DS.'views'.DS.'allplayersauth'.DS.'view.html.php');
        $view = new allplayersauthViewallplayersauth();
        $view->display();
       
    }

    public function callback() {
        $userInfo = null;
        $helper = new ComAllPlayersHelper();
        $app = JFactory::getApplication();
        $baseurl = $this->baseurl;

        if ($_GET['oauth_token']) {
            try {
                $oauth_token = $this->session->get('access_token');
                $secret = $this->session->get('access_secret');
   
                $userInfo = $helper->doLogin($oauth_token, $secret);

            } catch (Exception $e) {
                $je = json_decode($e->getMessage());
                $this->setRedirect(JRoute::_('index.php'), "Error: ". $e->getMessage());
            }

            if ($userInfo) {
                 if ($mapping = $helper->getUserMapping($userInfo->apid)) {
                     // log in user  
                     // For login we are using an authentication plugin.
                    if (true == $this->logUserIn($userInfo)){
             
                        $app->redirect(JRoute::_('index.php?option=com_allplayers_auth&task=close'));
                    } else {
                        $this->setRedirect(JRoute::_('index.php?option=com_allplayers_auth'), "Mapping set but could not login.");
                    }
                 } else {
                    //There is no mapping lets do some mappings!
                    $this->setRedirect(JRoute::_('index.php?option=com_allplayers_auth&view=mapping&task=mapping'));
                 }
             } 
        } else {
            $helper->clearCookies();
            $this->setRedirect($baseurl, 'No Auth Token is set.');
        }
    }

    public function mapping() {
        $helper = new ComAllPlayersHelper();
        $app = JFactory::getApplication();
        $jUser = $helper->getJoomlaAllPlayersUser();
        $apUser = $helper->getCredentials();
        $credentials = $this->session->get('com_allplayers_credentials');

        //I have a joomla user
        if (isset($jUser)){

            if ($jUser->allplayersid == null){
                $helper->setUserMapping($apUser, $jUser->id);
            }

            $this->logUserIn($apUser);
            $app->redirect(JRoute::_('index.php?option=com_allplayers_auth&task=close'));
        } else {

            $createdUser = $this->createUser($credentials->user);
            //Close window after create.
            $app->redirect(JRoute::_('index.php?option=com_allplayers_auth&task=close'));
        }
    }

    
   public function getData($userInfo) {
        if ($this->data === null) {

            $this->data = new stdClass();
            $app    = JFactory::getApplication();
            $params = JComponentHelper::getParams('com_users');

            $this->data->apid = $userInfo->apid;
            $this->data->name = $userInfo->nickname;
            $this->data->username = $userInfo->email;
            $this->data->email = $userInfo->email;

            // Get the groups the user should be added to after registration.
            $this->data->groups = array();

            // Get the default new user group, Registered if not specified.
            $system = $params->get('new_usertype', 2);

            $this->data->groups[] = $system;

            // Unset the passwords.
            unset($this->data->password1);
            unset($this->data->password2);

            // Get the dispatcher and load the users plugins.
            $dispatcher = JDispatcher::getInstance();
            JPluginHelper::importPlugin('user');
        }

        return $this->data;
    }

  public function createUser($userInfo = null) {
        $params = JComponentHelper::getParams('com_users');

        $helper = new ComAllPlayersHelper();
        if (!$userInfo){
            $userInfo = $helper->getCredentials();
        }

        // Initialise the table with JUser.
        $jUser = new JUser;
        $data = (array)$this->getData($userInfo);
        $data['password'] = JApplication::getHash(JUserHelper::genRandomPassword());
        $data['block'] = 0;
        $useractivation = $params->get('useractivation');
        $sendpassword = $params->get('sendpassword', 1);
    
        // Bind the data.
        if (!$jUser->bind($data)) {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $jUser->getError()));
            return false;
        }
    
        // Load the users plugin group.
        JPluginHelper::importPlugin('user');
        // Store the data.
        if (!$jUser->save()) {
            $this->setError(JText::sprintf("Unable to save user. " .
        "Please try again and ensure that your username and email address are not already taken.", 
        'error', $jUser->getError()));
            return false;
        } else {
            $helper->setUserMapping($userInfo, $jUser->id);
        }

        // Perform the log in.
        return $this->logUserIn($data);
        
    }

}
