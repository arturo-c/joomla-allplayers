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
$app->set('icon', 'all-players.png');	

$document->addStylesheet($media_path.'libraries/jquery/jquery-ui.custom.css');
$document->addStylesheet($media_path.'css/admin.css');

$document->addScript($media_path.'libraries/jquery/jquery.js');
$document->addScript($media_path.'libraries/jquery/jquery-ui.custom.min.js');
$document->addScript($media_path.'js/admin.js');

JHTML::_('behavior.modal', 'a.modal');

$controller = JController::getInstance('AllPlayers');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();