<?php
/**
 * @package		com_allplayers
 * @subpackage	profile
 * @copyright	Copyright (C) 2012 Wayin, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.model');


class allplayersModelAllplayers extends JModel
{
	public $auth;
	public $profile;

	function __construct() {
		parent::__construct();
	}

	
}