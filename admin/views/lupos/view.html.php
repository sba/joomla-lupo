<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Lupo View
 */
class LupoViewLupos extends JViewLegacy {
	/**
	 * Lupo view display method
	 * @return void
	 */
	function display($tpl = null) {
		// Check for errors.
		if (is_array($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
		}

        // Set the submenu
        LupoHelper::addSubmenu('lupos');

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('LUPO'));
		JToolBarHelper::preferences('com_lupo');
	}


}
?>