<?php
/**
 * @version 1.0
 * @package com_allplayers_profile
 * @author Zach Curtis, Wayin Inc, Zach Curtis
 * @author mail info@wayin.com
 * @copyright   Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license     GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class allplayersViewprofile extends Jview
{

    function __construct() {
      parent::__construct();
      $this->db = JFactory::getDBO();
    }
    
    function display($tpl = null) {
		// require_once (JPATH_COMPONENT.DS.'views'.DS.'allplayersauth'.DS.'view.html.php');
  //       $view = new allplayersViewallplayersauth();
  //       $view->display();
    	
       // JRequest::setVar('auth_view', $view);
        parent::display($tpl);
    }
    
}
