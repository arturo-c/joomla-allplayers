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

class allplayersViewauth extends Jview
{
    protected $form;
    protected $item;

    function __construct() {
      parent::__construct();
      $this->db = JFactory::getDBO();
    }
    
    function display($tpl = null) {
        $this->form     = $this->get('Form');
        $this->item     = $this->get('Item');

        $this->assign('consumer', $this->item);

        parent::display($tpl);
    }
}
