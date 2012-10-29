<?php
/**
* @package   com_allplayers
* @author Zach Curtis, Wayin Inc
* @author mail	info@wayin.com
* @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
* @license		GNU/GPL
*/

/*
	Class: DefaultController
		The controller class for frontpage
*/
class AllPlayersControllerAuth extends JController {

	public $application;

	public function __construct($default = array()) {
		parent::__construct($default);
		$this->db = JFactory::getDBO();
	}
	public function display($cacheable = false, $htmlparams = false){
		 parent::display($cacheable,$htmlparams);
	}

	public function save(){
		$key = JRequest::getVar( 'consumer_key' );
        $secret = JRequest::getVar( 'consumer_secret' );
        $oauthurl = JRequest::getVar('oauth_url');
        $verifypeer = JRequest::getVar('verify_peer');
        if ($verifypeer){
            $verifypeer = 1;
        }

		$this->db->setQuery('TRUNCATE #__allplayers_auth');
        $this->db->query();
        
        $this->db->setQuery('INSERT INTO #__allplayers_auth VALUES(DEFAULT, "'.$key.'", "'.$secret.'", "'.$oauthurl.'", "'.$verifypeer.'")');
        $this->db->query();

		$this->display();
	}
}