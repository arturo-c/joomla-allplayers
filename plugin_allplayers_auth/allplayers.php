<?php

/**
 * @copyright	Copyright (C) - 2012 Wayin, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author Zach Curtis, Wayin Inc 2012
 * Libs provided by All-Players
 */

// No direct access
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );

/**
 * All-Players User Profile Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Profile.allplayers
 * @since 2.5
 */
class plgAllPlayersUserProfile extends JPlugin
	private $_app;

	function plgAllPlayersUserProfile(&$subject, $config = array()){
		parent::__construct($subject, $config);
	}
       
	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array	$credentials Array holding the user credentials
	 * @param	array   $options	Array of extra options
	 * @param	object	$response	Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onContentPrepareData($context, $data) {
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile'))) {
			return true;
		}
		
		if (is_object($data)) {
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->profile) and $userId > 0) {
				// Load the profile data from the database.
				$db = JFactory::getDbo();
				// $db->setQuery(
				// 	'SELECT profile_key, profile_value FROM #__user_profiles' .
				// 	' WHERE user_id = '.(int) $userId." AND profile_key LIKE 'profile.%'" .
				// 	' ORDER BY ordering'
				// );
				
				$results = $db->loadRowList();

				// Check for a database error.
				if ($db->getErrorNum())
				{
					$this->_subject->setError($db->getErrorMsg());
					return false;
				}

				// Merge the profile data.
				$data->profile = array();

				foreach ($results as $v)
				{
					$k = str_replace('profile.', '', $v[0]);
					$data->profile[$k] = json_decode($v[1], true);
					if ($data->profile[$k] === null)
					{
						$data->profile[$k] = $v[1];
					}
				}
			}

			if (!JHtml::isRegistered('users.url'))
			{
				JHtml::register('users.url', array(__CLASS__, 'url'));
			}
			if (!JHtml::isRegistered('users.calendar'))
			{
				JHtml::register('users.calendar', array(__CLASS__, 'calendar'));
			}
			if (!JHtml::isRegistered('users.tos'))
			{
				JHtml::register('users.tos', array(__CLASS__, 'tos'));
			}
		}

		return true;

	}
}
