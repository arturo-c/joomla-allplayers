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

jimport( 'joomla.application.component.view' );

class twitterViewtwitter extends Jview
{

	function __construct()
	{
		parent::__construct();
    $this->db = JFactory::getDBO();
	}
	
	function display($tpl = null)
	{
    $key = JRequest::getVar( 'consumer_key' );
    $secret = JRequest::getVar( 'consumer_secret' );

    $this->db->execute('truncate #__twitter_consumer');

    $this->db->execute(
      sprintf("insert into #__twitter_consumer values(DEFAULT, '%s', '%s')", 
        $this->db->getEscaped($key),
        $this->db->getEscaped($secret)
      )
    ); 

    $this->db->setQuery('select * from #__twitter_consumer');
    $consumer = $this->db->loadObject();
		
    $this->assignRef('consumer', $consumer);
		
    parent::display($tpl);
	}
	
}
