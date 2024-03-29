<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
class com_AllPlayersInstallerScript{
	 /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) 
    {
    	$db = JFactory::getDBO();
        // get installer
        $installer = method_exists($parent, 'getParent') ? $parent->getParent() : $parent->parent;
    	//$query = 'UPDATE ';
        //$db->setQuery($query);

        // $parent is the class calling this method
        $installer->setRedirectURL('index.php?option=com_allplayers');
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) 
    {
        // get installer
        $installer = method_exists($parent, 'getParent') ? $parent->getParent() : $parent->parent;
        // $parent is the class calling this method
        echo '<p>' . JText::_('COM_ALLPLAYERS_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) 
    {
            // $parent is the class calling this method
            echo '<p>' . JText::sprintf('COM_ALLPLAYERS_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) 
    {
            // $parent is the class calling this method
            // $type is the type of change (install, update or discover_install)
            echo '<p>' . JText::_('COM_ALLPLAYERS_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) 
    {
            // $parent is the class calling this method
            // $type is the type of change (install, update or discover_install)
            echo '<p>' . JText::_('COM_ALLPLAYERS_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
    }
}
?>