<?php
defined('_JEXEC') or die('Restricted Access');
?>
<span class="mod_allplayers_profile <?php echo $modClassSuffix;?>">
	<?php if ($isLoggedIn){ ?>
		Welcome, <a href="index.php?option=com_allplayers&view=profile"><?php echo $linkText; ?></a>&nbsp;
		(<a href="index.php?option=com_allplayers&task=auth.logout">Logout</a>)
	<?php } else { ?>
		<a href="index.php?option=com_allplayers&view=profile" class="login"><?php echo $linkText; ?></a>
		<script>
			(function($){
				$(function(){

					$('.mod_allplayers_profile a.login').click(function(ev){
						ev.preventDefault();
						var redirectUrl = $(this).attr('href');
						var path = '/index.php?option=com_allplayers&view=auth';
						if (location.host.indexOf('localhost') !== -1){
							path = '/usarugby' + path;
						}
						
						var oauthWindow   = window.open(path, 'ConnectWithOAuth', 'location=0,status=0,width=600,height=700,scrollbars=yes');
						var oauthInterval = window.setInterval(function(){
							if (oauthWindow.closed) {
								 window.clearInterval(oauthInterval);
								 window.location = redirectUrl;
							}
						}, 1000);
					})
				});
			})(jQuery);
		</script>
	<?php } ?>
</span>

