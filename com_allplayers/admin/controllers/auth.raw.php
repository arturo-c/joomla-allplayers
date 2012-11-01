<?php
/**
* @package   com_allplayers
* @author Zach Curtis, Wayin Inc
* @author mail	info@wayin.com
* @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
* @license		GNU/GPL
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class AllPlayersControllerAuth extends JController {

	public $application;

	public function __construct($default = array()) {
		parent::__construct($default);
	}
	public function display($cacheable = false, $htmlparams = false){
		 parent::display($cacheable,$htmlparams);
	}
}