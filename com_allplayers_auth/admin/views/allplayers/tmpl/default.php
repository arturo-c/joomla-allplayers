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
<form method="POST">
  <table>
    <tr>
      <td>Consumer Key: </td>
      <td><input type="text" name="consumer_key" value="<?php echo $this->consumer->key ?>" /></td>
    </tr>
    <tr>
      <td>Consumer Secret: </td>
      <td><input type="text" name="consumer_secret" value="<?php echo $this->consumer->secret ?>"/></td>
    </tr>
    <tr>
      <td>OAuth URL: </td>
      <td><input type="text" name="oauth_url" value="<?php echo $this->consumer->oauthurl ?>" style="width:250px;"></td>
    </tr>
    <tr>
      <td>Verify Peer: </td>
      <td>
        <input type="checkbox" name="verify_peer" value="1">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right">
        <input type="submit" value="save" />
      </td>
    </tr>
  </table>
  <?php echo JHtml::_('form.token'); ?>
</form>
