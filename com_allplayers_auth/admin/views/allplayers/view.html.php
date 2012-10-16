<?php
/**
 * @version 1.0
 * @package com_allplayers_auth
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
        $this->db = JFactory::getDBO();
    }
    
    function display($tpl = null) {
        global $mainframe, $option;

        $this->addToolbar();
        
        $db = JFactory::getDBO();

        $key = JRequest::getVar( 'consumer_key' );
        $secret = JRequest::getVar( 'consumer_secret' );
        $oauthurl = JRequest::getVar('oauth_url');
        $verifypeer = JRequest::getVar('verify_peer');
        if ($verifypeer){
            $verifypeer = 1;
        }

        $db->setQuery('TRUNCATE #__allplayers_auth');
        $db->query();
        
        $db->setQuery('INSERT INTO #__allplayers_auth VALUES(DEFAULT, "'.$key.'", "'.$secret.'", "'.$oauthurl.'", "'.$verifypeer.'")');
        $db->query();
        //var_dump($db->replacePrefix( (string) $db->getQuery()) );//debug 
       # JFactory::getApplication()->enqueueMessage( 'query: ' . $p);
        $this->db->setQuery('SELECT * FROM #__allplayers_auth');
        $consumer = $this->db->loadObject();
            
        $this->assignRef('consumer', $consumer);
            
        parent::display($tpl);
    }

    function save(){
        //TODO: Add post logic and move save to separate function
    }

    function addToolbar(){
        JToolBarHelper::title( 'All-Players Authentication', 'allplayers-logo.png' );
    }
    
}
