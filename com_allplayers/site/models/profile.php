<?php
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );

class allplayersModelprofile extends JModel {
	public $consumer; //allplayersModelconsumer

	public function __construct(){
		parent::__construct();
		$this->consumer = new allplayersModelconsumer();
	}

	public function getConsumer(){
		$this->_db->setQuery('SELECT * FROM #__allplayers_auth');
		$consumer = $this->_db->loadObject();
		if ($consumer){
			$this->consumer = $consumer;
		}
		return $this->consumer;
	}
} 

class allplayersModelconsumer extends JModel{
	public $id = 0;
	public $key = '';
	public $secret = '';
	public $oauthurl = 'https://www.allplayers.com';
	public $verifypeer = TRUE;
}
?>