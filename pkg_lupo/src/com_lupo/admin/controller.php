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

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of LUPO component
 */
class LupoController extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = array()) {
		// set default view if not set
		$app         = JFactory::getApplication();
		$defaultview = $app->input->get->get('view', 'Lupos');

		if($defaultview === 'config') {
			//because <menu link="option=com_config&amp;view=component&amp;component=com_lupo">COM_LUPO_MENU_SUBMENU_CONFIGURATION</menu> in lupo.xml
			//is not shown to admin users, we need to redirect to the component config
			$app->redirect(
				JRoute::_('index.php?option=com_config&view=component&component=com_lupo', false)
			);
		}

		$app->input->set('view', $defaultview);

		// call parent behavior
		parent::display($cachable);
	}
}
