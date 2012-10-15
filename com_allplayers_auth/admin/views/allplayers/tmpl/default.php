<?php
/**
 * @version	0.1
 * @package	twitter
 * @author Mobilada.com
nfo@mobilada.com/g
 * @author mail	info@mobilada.com
 * @copyright	Copyright (C) 2009 Mobilada.com - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<form METHOD="POST">
<table>
  <tr>
    <td>Consumer Key: </td>
    <td><input type="text" name="consumer_key" value="<?php echo $this->consumer->key ?>"/></td>
  </tr>
  <tr>
    <td>Consumer Secret: </td>
    <td><input type="text" name="consumer_secret" value="<?php echo $this->consumer->secret ?>"/></td>
  </tr>
  <tr>
    <td colspan="2" align="right">
      <input type="submit" value="save" />
    </td>
  </tr>
</table>
</form>
