<?php
/**
 * @version	1.0.0
 * @package	allplayers_profile
 * @author Zach Curtis, Wayin Inc
 * @author mail	info@wayin.com
 * @copyright	Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<fieldset>
  <form method="post" name="user_options">
    <legend>User Options</legend>
    <table>
      <tr>
        <td>Enabled? </td>
        <td><input type="checkbox" name="show_avatar" <?php if ($this->user->is_enabled){?>checked="checked" <?php } ?> value="true"> </td>
      </tr>
      
    </table>
    <?php echo JHtml::_('form.token'); ?>
  </form>
</fieldset>

<fieldset>
  <legend>Group Options</legend>
    <table>
      <tr>
          <td>Enabled?</td>
          <td><input type="checkbox" name="group_enabled" <?php if ($this->group->is_enabled) { ?>checked="checked"<?php }?> value="true"></td>
      </tr>
      
    </table>
  </form>
</fieldset>

 <input type="submit" value="save" />