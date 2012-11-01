<?php
/**
 * @version 1.0.0
 * @package allplayers_profile
 * @author Zach Curtis, Wayin Inc
 * @author mail info@wayin.com
 * @copyright Copyright (C) 2012 Wayin.com - All rights reserved.
 * @license   GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

?>
Edit Me!!!
<form action="<?php echo JRoute::_('index.php?option=com_allplayers&view=auth&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" accept-charset="utf-8">
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
        <input type="checkbox" name="verify_peer" value="1" <?php if ($this->consumer->verifypeer){ ?>checked="checked"<?php } ?>>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right">
        <input type="submit" value="save" />
      </td>
    </tr>
  </table>
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
  <?php echo JHtml::_('form.token'); ?>
</form>