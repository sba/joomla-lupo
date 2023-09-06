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
 * HTML View class for the LUPO Component
 */
class LupoViewGame extends JViewLegacy {
	// Overwriting JView display method
	function display($tpl = null) {
		$app = JFactory::getApplication();

        // Check for errors.
        $errors = $this->get('Errors');
        if ($errors) {
            JFactory::getApplication()->enqueueMessage(implode('<br />', $errors), 'error');
            return false;
        }

		// Check for empty title and add site name if param is set
		if ($this->game !== 'error') {
			$title = $this->game['title'];
			if (empty($title)) {
				$title = $app->get('sitename');
			} elseif ($app->get('sitename_pagetitles', 0) == 1) {
				$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
			} elseif ($app->get('sitename_pagetitles', 0) == 2) {
				$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
			}
		} else {
			$title = $app->get('sitename');
		}

		JFactory::getDocument()->setTitle($title);

		// Display the view
		parent::display($tpl);
	}
}
