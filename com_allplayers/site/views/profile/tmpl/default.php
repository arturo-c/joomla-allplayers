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
	$user = $this->user;
	?>
	<h1>Name: <?php echo $user->username;?></h1>
	Avatar: <img src="<?php echo $user->picture; ?>" alt="avatar image">
	<p>Email: <?php echo $user->email;?></p>
	<p>Gender: <?php echo $user->gender;?></p>
	<p>Nickname: <?php echo $user->nickname; ?></p>
	<p><a href="<?php echo $user->profile_url;?>/edit/profile" class="edit-profile" rel="{handler: 'iframe', size: {x: 1000, y: 700}}">Edit Profile</a></p>

<script>
	$(function(){

	});
</script>
<?php } else {?>
	<div>
		<p>
			Please login to view your profile.
			<a href="index.php?option=com_allplayers&view=profile" class="allplayers-login">Login</a>
		</p>
	</div>
<?php } ?>
