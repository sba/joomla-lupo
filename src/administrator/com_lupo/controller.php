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
		$app->input->set('view', $defaultview);

		// call parent behavior
		parent::display($cachable);
	}
}
