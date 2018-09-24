<?php
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
		// Get data from the model
		$items      = $this->get('Items');
		$pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
		}

		// Assign data to the view
		$this->items      = $items;
		$this->pagination = $pagination;

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