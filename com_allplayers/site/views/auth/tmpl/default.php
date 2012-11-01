<?php
/**
 * @version	1.0
 * @package	allplayers_auth
 * @author Zach Curtis, Wayin Inc
 * @author mail	info@wayin.com
 * @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

if ($this->userLoggedIn){
    ?>

	<script>
		if (window.parent){
			self.close();
		} 
	</script>
<?php } ?>
Redirecting to All-Players for authentication...
