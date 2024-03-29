<?php

/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author Zach Curtis, Wayin Inc 2012
 * Libs provided by All-Players
 */

// No direct access
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );

/**
 * All-Players Authentication Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Authentication.allplayers
 * @since 2.5
 */
class plgAuthenticationAllPlayers extends JPlugin {
       
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
	function onUserAuthenticate($credentials, $options, & $response) {
		
		$message = '';
		$success = 0;
		$this->db = JFactory::getDBO();
		
		if (empty($credentials['apid'])) {
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('All-Players id is blank. Please try again.');
			return false;
		}
		$query = 'SELECT * FROM #__users u INNER JOIN #__allplayers_auth_mapping aam ON aam.userid = u.id WHERE aam.allplayersid = "'.$credentials['apid'].'"';

		$this->db->setQuery($query);
		$user = $this->db->loadObject();
		
		if (isset($user)){
			if ($user->block == 0){
				$success = 1;
			} else {
				$success = 0;
				$message = "Your account has been disabled. Please contact customer service.";
			}
		} else {
			$success = 0;
			$message = "Could not verify Joomla and All-Players account.";
		}

		//$response->type = 'All-Players Authentication';
		
        if ($success) {
            $response->status        = JAuthentication::STATUS_SUCCESS;
            $response->error_message = '';
            $response->email         = $user->email;
        }  else  {
            $response->status         = JAuthentication::STATUS_FAILURE;
            $response->error_message  = JText::sprintf('JGLOBAL_AUTH_FAILED', $message);
        }

	}
}
