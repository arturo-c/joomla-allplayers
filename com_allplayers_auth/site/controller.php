<?php
/**
 * @version	0.1
 * @package	twitter
 * @author Mobilada.com
 * @author mail	info@mobilada.com
 * @copyright	Copyright (C) 2009 Mobilada.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
include_once(JPATH_BASE . "/components/com_twitter/helper.php");

class twitterController extends JController
{

	function __construct()
	{
		parent::__construct();
    $this->config = JFactory::getConfig();
    $this->baseurl = $this->config->getValue('config.live_site');
    $this->db = JFactory::getDBO();
	}
	
	function display($tpl = null)
	{
		parent::display($tpl);
	}

  function callback() {
    global $mainframe;
    $helper = new ComTwitterHelper();
    $baseurl = $this->baseurl;

    if ($_GET['oauth_token']) {
      try {
        $twitterInfo = $helper->doLogin();
      } catch (Exception $e) {
        $je = json_decode($e->getMessage());
        $this->setRedirect($baseurl, $je->error);
      }
      if ($twitterInfo) {
        if ($mapping = $helper->getUserMapping()) {
          // log in user  
          // For login we are using an authentication plugin.
          // This plugin looks for COM_TWITTER:COM_TWITTER_LOGIN credentials
          // and  gets $_SESSION['com_twitter'] object for userid
          $mainframe->login(array('username' => 'COM_TWITTER', 'password' => 'COM_TWITTER_LOGIN'));
          $mainframe->redirect(JRoute::_('index.php'));
        } else {
          //There is no mapping lets do some mappings!
          $mainframe->redirect(JRoute::_('index.php?option=com_twitter&view=mapping&task=mapping'));
        }
      } 
      $this->setRedirect(JRoute::_('index.php'), 'Hata');
    } else {
      setcookie('oauth_token', '', 1, '/');
      setcookie('oauth_token_secret', '', 1, '/');
      $this->setRedirect($baseurl, 'No Auth Token is set.');
    }
  }

  function mapping() {
    global $mainframe;
    $helper = new ComTwitterHelper();

    if (
      isset($_SESSION['com_twitter_credentials']) && 
      $_SESSION['com_twitter_credentials']->twitterInfo->timeout > time() &&
      $_SESSION['com_twitter_credentials']->oauth_token == $_COOKIE['oauth_token'] &&
      $_SESSION['com_twitter_credentials']->oauth_token_secret == $_COOKIE['oauth_token_secret']
    ) {
      // Check if already logged in
      $user = JFactory::getUser();
        //Not Logged in. Just head directly to the view.
        if ($user->id == 0) {
        require_once (JPATH_COMPONENT.DS.'views'.DS.'mapping'.DS.'view.html.php');
        $view = new twitterViewMapping();
        $view->display();
      } else {
        $mapping = $helper->getJoomlaUserMapping($user->id);
        if ($mapping) {
          $helper->clearCookies();
          $this->setRedirect('index.php','You already have another twitter mapping');
        } else {
          $helper->setUserMapping();
          $this->setRedirect('index.php', 'Twitter user mapping complete And user logged in successfully.');
        }
      }
    } else {
      $mainframe->redirect($this->baseurl);
    }
  }
	
  function checkUserNameAvailable()
	{
		$username = JRequest::getVar('username');
		$dbo = JFactory::getDBO();
		$query = "SELECT id FROM #__users WHERE username=".$dbo->quote($username);
		$dbo->setQuery($query);
		$result = $dbo->loadResult();

		if ($result)
			echo false;
		else
			echo true;
		exit;
	}
	
  function checkEmail()
	{
		$email = JRequest::getVar('email');
		$dbo = JFactory::getDBO();
		$query = "SELECT id FROM #__users WHERE email=".$dbo->quote($email);
		$dbo->setQuery($query);
		$result = $dbo->loadResult();

		if ($result)
			echo false;
		else
			echo true;
		exit;
	}

	function mapUser()
	{
		global $mainframe;
    try {
      $helper = new ComTwitterHelper($consumer->key, $consumer->secret);
    } catch (Exception $e) {
      $msg = "Could not retrieve Twitter Consumer info. Have you installed com_twitter and configured it?";
      $this->setRedirect('index.php', $msg);
    }
    try {
      $twitterInfo = $helper->getCredentials();
      $username = JRequest::getVar('username', '', 'POST');
      $password = JRequest::getVar('password', '', 'POST');

      $login = $mainframe->login(array('username'=>$username, 'password'=>$password));

      if ($login === true) {
        $helper->setUserMapping();
        $this->setRedirect('index.php', 'Twitter user mapping complete And user logged in successfully.');
      } else {
        $return = JRoute::_('index.php?option=com_twitter&view=mapping&task=mapping');
        $this->setRedirect($return, '');
      }
    } catch (Exception $e) {
      $this->setRedirect($this->baseurl, json_decode($e->getMessage())->error );
    }
	}

	function createUser() {
		global $mainframe;
    $helper = new ComTwitterHelper();
    $twitterInfo = $helper->getCredentials();

		jimport('joomla.user.helper');

		$username = JRequest::getVar('username', '', 'POST');
		$email = JRequest::getVar('email', '', 'POST');
		$password = JRequest::getVar('password', '', 'POST');
		$password2 = JRequest::getVar('password2', '', 'POST');

		$jUser = clone(JFactory::getUser());
		$ACL =& JFactory::getACL();
		$userConfig =& JComponentHelper::getParams('com_users');
		$newUserType = $userConfig->get('new_usertype');
		if (!$newUserType)
			$newUserType = 'Registered';

		$userVals['name'] = $twitterInfo->name;
		$userVals['username'] = $username;
		$userVals['email'] = $email;
		$userVals['password'] = $password;
		$userVals['password2'] = $password;

		$user = JFactory::getUser();
		if (!$user->bind($userVals)) {
			$mainframe->enqueueMessage("Unable to bind user", 'error');
			$mainframe->redirect(JRoute::_('index.php?com_twitter&view=mapping&task=mapping', false));
		}

		$user->set('id', 0);
		$user->set('usertype', $newUserType);
		$user->set('gid', $ACL->get_group_id('', $newUserType, 'ARO'));
		$user->set('block', 0);

		if (!$user->save()) {
			$mainframe->enqueueMessage(
        "Unable to save user. " .
        "Please try again and ensure that your username and email address are not already taken.", 
        'error');
			$mainframe->redirect(JRoute::_('index.php?com_twitter&view=mapping&task=mapping', false));
		}

		if ($mainframe->login(array('username'=>$username, 'password'=>$password))) {
      $helper->setUserMapping();
    } 
	  $mainframe->redirect('index.php');
	}

}
