<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Component Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */

jimport('joomla.application.component.controller');

include_once(JPATH_BASE . DS."components".DS."com_allplayers".DS."helper.php");

class AllPlayersController extends JController
{

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Load the submenu.
		//ContentHelper::addSubmenu(JRequest::getCmd('view', 'default'));

		$view		= JRequest::getCmd('view', 'default');
		$layout 	= JRequest::getCmd('layout', 'default');
		$id			= JRequest::getInt('id');

		parent::display();

		return $this;
	}

	public function logout(){
        $helper = new ComAllPlayersHelper();
        $app = JFactory::getApplication();
        $helper->logout();
        $app->redirect('index.php');
    }
}
