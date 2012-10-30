<?php
/**
 * @version	0.0.1
 * @package	com_allplayers
 * @author Zach Curtis, Wayin Inc
 * @author mail	info@wayin.com
 * @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

$app = JFactory::getApplication('allplayers');
$document = JFactory::$document;
$media_path = '../media/com_allplayers/';

$controller = JRequest::getWord('controller');
$task       = JRequest::getWord('task');
$view       = $app->input->getCmd('view');

if (!$task){
	$task = 'display';
}
if (!$view && $controller){
	$view = $controller;
} else if ($view && !$controller){
	$controller = $view;
}
$type = $controller;

JRequest::setVar('controller', $controller);
JRequest::setVar('task', $type.'.'.$task);
JRequest::setVar('view', $view);

// Get an instance of the default controller 
$controller = JController::getInstance('AllPlayers');

$controller->execute($task);