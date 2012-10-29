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
	}
	public function display($cacheable = false, $htmlparams = false){
		// require_once (JPATH_COMPONENT.DS.'views'.DS.'allplayersauth'.DS.'view.html.php');
  //       $view = new allplayersViewallplayersauth();
  //       JRequest::setVar('auth_view', $view);
		 parent::display($cacheable,$htmlparams);
	}
}