<?php
/**
 * @version	0.1
 * @package	allplayers
 * @author Zach Curtis, Wayin Inc
*
 * @author mail	info@wayin.com
 * @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class allplayersController extends JController
{

	function __construct() {
		parent::__construct();
    	$this->db = JFactory::getDBO();
	}
	
	function display($cachable = false, $urlparams = false)
	{
		parent::display($cachable, $urlparams);
	}

}
