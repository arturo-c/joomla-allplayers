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

<form method="POST">
  <table>
    <tr>
      <td>Show Avatar: </td>
      <td><input type="checkbox" name="show_avatar" value=""/> </td>
    </tr>
    <tr>
      <td colspan="2" align="right">
        <input type="submit" value="save" />
      </td>
    </tr>
  </table>
  <?php echo JHtml::_('form.token'); ?>
</form>