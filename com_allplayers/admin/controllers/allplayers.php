<?php
/**
* @package   com_allplayers
* @author Zach Curtis, Wayin Inc
* @author mail	info@wayin.com
* @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
* @license		GNU/GPL
*/

jimport('joomla.application.component.controller');

class AllPlayersControllerAllplayers extends JController {

	public $application;

	public function __construct($default = array()) {
		parent::__construct($default);
	}

	public function display($cacheable = false, $htmlparams = false){
		parent::display($cacheable, $htmlparams);
	}

	public function save(){
		$app = JFactory::getApplication();
		if (JRequest::checkToken()){
			$model = $this->getModel(JRequest::getVar('model'), 'allplayersModel');
			$data = JRequest::getVar('jform');
			if ($model->save($data)){
				 $app->redirect(JRoute::_('index.php?option=com_allplayers&view=auth'), "Save Successful!");
			} else {
				$app->redirect(JRoute::_('index.php?option=com_allplayers&view=auth'), "Could not save.");
			}
		}
	}

}