<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
class AllPlayersTableAuth extends JTable
{

	function __construct(&$db)
	{
		parent::__construct('#__allplayers_auth', 'id', $db);
	}
}
