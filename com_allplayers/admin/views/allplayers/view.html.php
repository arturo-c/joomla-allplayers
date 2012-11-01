<?php
/**
 * @version 0.0.1
 * @package com_allplayers
 * @author Zach Curtis, Wayin Inc, Zach Curtis
 * @author mail info@wayin.com
 * @copyright   Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license     GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class allplayersViewallplayers extends Jview
{

    function __construct() {
        parent::__construct();
    }
    
    function display($tpl = null) {
        $this->addToolbar();
        
        $this->assign('base_url_raw', JURI::base().'index.php?option=com_allplayers&format=raw');
        parent::display($tpl);
    }

    function save(){
        error_log("\n\nDefault view save!!!\n");
    }
    function addToolbar(){
        JToolBarHelper::title( 'All-Players', 'allplayers-logo.png' );
        JToolBarHelper::save('allplayers.save');
        JToolBarHelper::cancel('allplayers.cancel');
    }
    
}
