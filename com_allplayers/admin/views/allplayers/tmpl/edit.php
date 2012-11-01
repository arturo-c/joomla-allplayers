<?php
/**
 * @version	0.0.1
 * @package	allplayers
 * @author Zach Curtis, Wayin Inc
 * @author mail	info@wayin.com
 * @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div id="admin_tabs">
  <ul>
    <li><a href="<?php echo $this->base_url_raw . '&view=auth&task=auth.edit'; ?>">Authentication</a></li>
    <li><a href="<?php echo $this->base_url_raw . '&view=profile&task=profile.edit'; ?>">Profile</a></li>
  </ul>
  <div class="loader"><span class="icon"></span> Loading...</div>
</div>

