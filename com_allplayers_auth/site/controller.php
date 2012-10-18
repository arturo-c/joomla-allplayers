<?php
/**
 * @version 1.0
 * @package allplayers_auth
 * @author Zach Curtis, Wayin Inc
 * @author mail info@wayin.com
 * @copyright Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license   GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
include_once(JPATH_BASE . "/components/com_allplayers_auth/helper.php");

class AllPlayersController extends JController {
    protected $data;

    function __construct() {
        parent::__construct();
        $this->config = JFactory::getConfig();
        $uri = JFactory::getURI();
        $this->baseurl = $uri->toString();
        $this->db = JFactory::getDBO();
        $this->session = JFactory::getSession();
    }

    private function logUserIn($userInfo){
        $app = JFactory::getApplication();
        // Get the log in credentials.
        $credentials = array();
        $credentials['id'] = $userInfo->id;
        $credentials['username'] = $userInfo->email;
        $credentials['password'] = 'stuff'; //password cannot be blank for joomla but is not needed for all-players

        // Perform the log in.
        return $app->login($credentials);
    }
    
    public function display($tpl = null) {
        parent::display($tpl);
    }
    

    public function callback() {
        $userInfo = null;
        $helper = new ComAllPlayersHelper();
        $baseurl = $this->baseurl;
        
        if ($_GET['oauth_token']) {
            try {
                $oauth_token = $this->session->get('access_token');
                $secret = $this->session->get('access_secret');

                $userInfo = $helper->doLogin($oauth_token, $secret);

            } catch (Exception $e) {
                $je = json_decode($e->getMessage());
                $this->setRedirect(JRoute::_('index.php'), "Error");
            }
            
            if ($userInfo) {
                 if ($mapping = $helper->getUserMapping($userInfo)) {
                     // log in user  
                     // For login we are using an authentication plugin.
                    if (true == $this->logUserIn($userInfo)){
                        $this->setRedirect(JRoute::_('index.php?option=com_allplayers_auth'));
                    } else {

                    }
                 } else {
                    //There is no mapping lets do some mappings!
                    $this->setRedirect(JRoute::_('index.php?option=com_allplayers_auth&view=mapping&task=mapping'));
                 }
             } 
        } else {
            setcookie('oauth_token', '', 1, '/');
            setcookie('oauth_token_secret', '', 1, '/');
            $this->setRedirect($baseurl, 'No Auth Token is set.');
        }
    }

    public function mapping() {
        $helper = new ComAllPlayersHelper();
        $credentials = $this->session->get('com_allplayers_credentials');
       
        if (isset($credentials) /* && $credentials->oauth_token == $_COOKIE['oauth_token'] && $credentials->oauth_token_secret == $_COOKIE['oauth_token_secret']*/) {
            // Check if already logged in
            $user = JFactory::getUser();
            
            //Not Logged in. Just head directly to the view.
            if ($user->id == 0) {
                // require_once (JPATH_COMPONENT.DS.'views'.DS.'mapping'.DS.'view.html.php');
                // $view = new allplayersViewMapping();
                // $view->display();
                $createdUser = $this->createUser($credentials->user);
                if ($createdUser){
                    parent::display();
                } else {
                   // $this->setRedirect(JRoute::_('index.php?option=com_allplayers_auth&view=mapping&task=mapping'));
                }
            } else {
                $mapping = $helper->getJoomlaUserMapping($user->id);
                if ($mapping) {
                    $helper->clearCookies();
                    $this->setRedirect('index.php','You already have another All-Players mapping');
                } else {
                    $helper->setUserMapping($credentials->user, $user->id);
                    //$this->setRedirect('index.php', 'All-Players user mapping complete And user logged in successfully.');
                    parent::display();
                }
            }
        } else {
          $this->setRedirect($this->baseurl);
        }
      }

    
   public function getData($userInfo) {
        if ($this->data === null) {

            $this->data = new stdClass();
            $app    = JFactory::getApplication();
            $params = JComponentHelper::getParams('com_users');

            $this->data->apid = $userInfo->id;
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
        $user = new JUser;
        $data = (array)$this->getData($userInfo);
        $data['password'] = JApplication::getHash(JUserHelper::genRandomPassword());
        $data['block'] = 0;
        $useractivation = $params->get('useractivation');
        $sendpassword = $params->get('sendpassword', 1);
        
        // Bind the data.
        if (!$user->bind($data)) {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
            return false;
        }
    
        // Load the users plugin group.
        JPluginHelper::importPlugin('user');
        // Store the data.
        if (!$user->save()) {
            $this->setError(JText::sprintf("Unable to save user. " .
        "Please try again and ensure that your username and email address are not already taken.", 
        'error', $user->getError()));
            return false;
        } else {
            $helper->setUserMapping($userInfo, $user->id);
        }

        // Perform the log in.
        return $this->logUserIn($data);
        
    }

}
