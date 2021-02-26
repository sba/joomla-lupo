<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Lupo View
 */
class LupoViewFilters extends JViewLegacy {
	/**
	 * Lupo view display method
	 * @return void
	 */
	function display($tpl = null) {
		// Get data from the model
        $this->items      = $this->get('Categories');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
		}

        // Set the submenu
        LupoHelper::addSubmenu('filter');

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
