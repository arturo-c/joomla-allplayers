<?php
defined('_JEXEC') or die;

jimport( 'joomla.application.component.modeladmin' );

class allplayersModelauth extends JModelAdmin {
	public $consumer; //allplayersModelconsumer
	protected $text_prefix = 'COM_ALLPLAYERS';

	public function __construct(){
		parent::__construct();
	}

	public function getTable($type = 'Auth', $prefix = 'AllPlayersTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true){
		// Get the form.
		
        $form = $this->loadForm('com_allplayers.auth', 'auth', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) 
        {
            return false;
        }
        return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_allplayers.edit.auth.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if (!$data->oauthurl) {
				$data->set('oauthurl', JRequest::getString('oauthurl', 'https://www.allplayers.com'));
			}
			if (!$data->verifypeer){
				$data->verifypeer = JRequest::getInt('verifypeer', 1);
			}
		}

		return $data;
	}

	public function getItem(){
		$this->_db->setQuery('SELECT * FROM #__allplayers_auth');
		$consumer = $this->_db->loadObject();
		if ($consumer){
			$this->consumer = $consumer;
		} 
		return $this->consumer;
	}

} 

?>