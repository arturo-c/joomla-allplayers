<?php 
//Script to automatically log an All-Players user into Joomla if the User Id is set.
//Author: Zach Curtis, Wayin, Inc.
if (isset($_COOKIE['user_apid'])){
	$db = JFactory::getDBO();
    $query = 'SELECT u.*, aam.allplayersid apid FROM #__users u INNER JOIN #__allplayers_auth_mapping aam ON aam.userid = u.id WHERE aam.allplayersid = "'.$_COOKIE['user_apid'].'"';
    $db->setQuery($query);
    $user = $db->loadObject();
  	if (isset($user)){
		$credentials = array();
	    $credentials['apid'] = $user->apid;
	    $credentials['username'] = $user->email;
	    $credentials['password'] = 'stuff'; //password cannot be blank for joomla but is not needed for all-players
	    // Perform the log in.
	    $app->login($credentials);
	}
}
?>