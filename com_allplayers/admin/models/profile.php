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


class allplayersModelprofile extends JModel
{
	public $group = array();
	public $user = array();

	function __construct() {
		parent::__construct();
		$this->db = $this->_db;
	}

	public function loadFormData(){
		$this->db->setQuery('SELECT * FROM #__allplayers_profile');
		$profileConfigs = $this->db->loadObjectList();
		foreach ($profileConfigs as $key => $value) {
			$config_json = json_decode($value->values);
			if ($value->group == 'g'){
				$this->group = $config_json;
			} else if ($value->group == 'u'){
				$this->user = $config_json;
			}
		}
	}


	
}