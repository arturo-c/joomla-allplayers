<?php
/**
 * @version	0.1
 * @package	allplayers_auth
 * @author Zach Curtis, Wayin Inc
 * @author mail	info@wayin.com
 * @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var usernameSuccessElement;
var emailSuccessElement;
function checkUsernameAvailable(usernameField) {
  usernameSuccessElement = document.getElementById(usernameField);
  var testName = $('username').value;
  if (testName != '')
    var myXHR = new XHR({method:'get', onSuccess:showUsernameSuccess}).send(
      'index.php', 
      'option=com_allplayers_auth&task=checkUsernameAvailable&username='+testName
    );
}

function showUsernameSuccess(req) {
  if (req == 1) {
    usernameSuccessElement.innerHTML='<?php echo JText::_('Username is available')?>';
  } else {
    usernameSuccessElement.innerHTML='<?php echo JText::_('Username is in use') ?>';
  }
}

function checkEmailAvailable(emailField) {
  emailSuccessElement = document.getElementById(emailField);
  var testEmail = $('email').value;
  if (testEmail != '' && isEmail(testEmail))
    var myXHR = new XHR({method:'get', onSuccess:showEmailSuccess}).send(
      'index.php', 
      'option=com_allplayers_auth&task=checkEmail&email='+testEmail);
}

  function showEmailSuccess(req) {
    if (req == 1) {
      emailSuccessElement.innerHTML='<?php echo JText::_('Email is available')?>';
    } else {
      emailSuccessElement.innerHTML='<?php echo JText::_('Email is in use') ?>';
    }
  }

function isEmail( text ) {
  var pattern = "^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$";
  var regex = new RegExp( pattern );
  return regex.test( text );
}
</script>
<p>Thank you for sign-ing in with <a href="http://www.allplayers.com.com">All-Players</a>. If you have an account on this site please log in to map your account. Otherwise please supply your information for us to create an account for you.</p>
<div>
<form action="" method="post" name="form" style="float:left; width: 45%;">
	<fieldset>
		<legend><?php echo JText::_("Sign-in") ?></legend>
		<p>
      <?php echo JText::_("If you already have an account please sign-in here. We will map your account with your twitter account.")?>
    </p>
		<div class="label">
		   <label><?php echo JText::_("Username"); ?>:</label>
		</div><input type="text" class="inputbox" name="username" value="" size="20" /><br/>
		<div class="label">
		   <label><?php echo JText::_("Pasword");?>:</label>
		</div><input type="password" class="inputbox" name="password" value="" size="20" /><br/>
		<input type="submit" class="button" value="<?php echo JText::_("Sign In"); ?>" />

		<input type="hidden" name="option" value="com_twitter" />
		<input type="hidden" name="task" value="mapUser" />
	</fieldset>
</form>

<form action="" method="post" name="adminForm" style="float:left; width: 45%;">
	<fieldset>
		<legend><?php echo JText::_("Create A new Account"); ?></legend>
		<p><?php echo JText::_("To create a new account please fill in this form."); ?> </p>
		<div class="label">
		   <label><?php echo JText::_("Username"); ?>: </label>
		</div><input type="text" class="inputbox" id="username" name="username" value="" size="20" onBlur="checkUsernameAvailable('usernameSuccess')" /><br/>
		<div id="usernameSuccess"></div>
		<div class="label">
		   <label><?php echo JText::_("Email"); ?>: </label>
		</div><input type="text" class="inputbox" id="email" name="email" value="" size="20" onBlur="checkEmailAvailable('emailSuccess')" /><br/>
		<div id="emailSuccess"></div>
		<div class="label">
		   <label><?php echo JText::_("Password"); ?>:</label>
		</div><input type="password" class="inputbox" name="password" value="" size="20" /><br/>
		<div class="label">
		   <label><?php echo JText::_("Confirm Password"); ?>:</label>
		</div><input type="password" class="inputbox" name="password2" value="" size="20" /><br/>
		<input type="submit" class="button" value="<?php echo JText::_("Register"); ?>" />

		<input type="hidden" name="option" value="com_allplayers_auth" />
		<input type="hidden" name="task" value="createUser" />
	</fieldset>
</form>
</div>
