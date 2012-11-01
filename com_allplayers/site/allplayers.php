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
$media_path = 'media/com_allplayers/';

$document->addScript($media_path.'libraries/jquery/jquery.js');
$document->addScript($media_path.'js/profile.js');

JHTML::_('behavior.modal', 'a.modal');
JHTML::_('behavior.framework',true);
$uncompressed = JFactory::getConfig()->get('debug') ? '-uncompressed' : '';
JHTML::_('script','system/modal'.$uncompressed.'.js', true, true);
JHTML::_('stylesheet','media/system/css/modal.css');

$controller = JController::getInstance('AllPlayers');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();