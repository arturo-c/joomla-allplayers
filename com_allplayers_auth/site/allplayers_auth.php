<?php
/**
 * @version	0.1
 * @package	twitter
 * @author Mobilada.com
 * @author mail	info@mobilada.com
 * @copyright	Copyright (C) 2009 Mobilada.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
 
// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if($controller = JRequest::getVar('controller')) 
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if ( file_exists( $path ) ) {
		require_once( $path );
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'twitterController' . ucfirst($controller);
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getVar('task', null, 'default', 'cmd'));
$controller->redirect();
