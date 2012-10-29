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
    <li><a href="<?php echo $this->base_url_raw . '&controller=auth&view=auth'; ?>">Authentication</a></li>
    <li><a href="<?php echo $this->base_url_raw . '&controller=profile&view=profile'; ?>">Profile</a></li>
  </ul>
</div>