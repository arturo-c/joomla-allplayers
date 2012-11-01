<?php

$helperFile = JPATH_SITE.DS.'components'.DS.'com_allplayers'.DS.'helper.php';
	if (file_exists($helperFile)){
		require_once $helperFile;
	} else {
		//echo ;
		die('<div style="color:yellow;font-weight:bold;">All-Players Joomla component not found. Please install.</div>');
	}

class ModAllPlayersProfileHelper extends ComAllPlayersHelper{
	
	function __construct(){
		parent::__construct();
	}

}