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
    }
    
    function display($tpl = null) {
      $model = $this->getModel();
      $config = $model->loadFormData();
      $this->group = $model->group;
      $this->user = $model->user;
      parent::display($tpl);


    }
    
}
