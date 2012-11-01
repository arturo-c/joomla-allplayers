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
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class AllPlayersControllerAuth extends JControllerForm {

	public $application;

	public function __construct($default = array()) {
		parent::__construct($default);
		$this->db = JFactory::getDBO();
	}

	public function display($cacheable = false, $htmlparams = false){
		 parent::display($cacheable,$htmlparams);
	}
}