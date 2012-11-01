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

<form action="<?php echo JRoute::_('index.php?option=com_allplayers&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
  <table>
     <tr>
      <td><?php echo $this->form->getLabel('key'); ?></td>
      <td><?php echo $this->form->getInput('key'); ?></td>
    </tr>
    <tr>
      <td><?php echo $this->form->getLabel('secret'); ?></td>
      <td><?php echo $this->form->getInput('secret'); ?></td>
    </tr>
    <tr>
      <td><?php echo $this->form->getLabel('oauthurl'); ?></td>
      <td><?php echo $this->form->getInput('oauthurl'); ?></td>
    </tr>
    <tr>
      <td><?php echo $this->form->getLabel('verifypeer'); ?></td>
      <td><?php echo $this->form->getInput('verifypeer'); ?></td>
    </tr>
  </table>
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="model" value="auth">
  <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
  <?php echo JHtml::_('form.token'); ?>
</form>