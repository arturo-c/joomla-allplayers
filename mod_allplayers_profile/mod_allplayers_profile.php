<?php 
defined('_JEXEC') or die('Direct Access to this location prohibited.');

require_once __DIR__.DS.'helper.php';
$helper = new ModAllPlayersProfileHelper();
$linkText = "Login / Register";
$isLoggedIn = false;

if (isset($_COOKIE['user_apid'])){
	$apUser = $helper->getCredentials();
	if (isset($apUser)){
		$linkText = "$apUser->username";
		$isLoggedIn = true; 
	}
}
$modClassSuffix = $params->get('moduleclass_sfx', '');
require JModuleHelper::getLayoutPath('mod_allplayers_profile', $params->get('layout', 'default'));