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
<div>
    <p>Popup window installation: Add this script to your sites custom javascript file. </p>
    <p>
        //On document ready
        $(function(){
            $('a.allplayers-login').click(function(ev){
                ev.preventDefault();
                var redirectUrl = $(this).attr('href');
                var path = '';
                if (location.host.indexOf('localhost') !== -1){
                    path = '/MYFOLDER/index.php?option=com_allplayers_auth';
                } else {
                    path = '/index.php?option=com_allplayers_auth';
                }

                var oauthWindow   = window.open(path, 'ConnectWithOAuth', 'location=0,status=0,width=600,height=700,scrollbars=yes');
                var oauthInterval = window.setInterval(function(){
                if (oauthWindow.closed) {
                    window.clearInterval(oauthInterval);
                    window.location = redirectUrl;
                }
                }, 1000);
            });
        });
    </p>
</div>