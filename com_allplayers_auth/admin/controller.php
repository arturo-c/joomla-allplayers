<?php
/**
 * @version	0.1
 * @package	twitter
 * @author Mobilada.com
nfo@mobilada.com/g
 * @author mail	info@mobilada.com
 * @copyright	Copyright (C) 2009 Mobilada.com - All rights reserved.
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
	
	function display($tpl = null)
	{
		parent::display($tpl);
	}

}
