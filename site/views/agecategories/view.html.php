<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer 
 * @author		Stefan Bauer
 * @link		http://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the LUPO Component
 */
class LupoViewAgecategories extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$app        = JFactory::getApplication();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JFactory::getApplication()->enqueueMessage(implode('<br />', $errors), 'error');
			return false;
		}

		// Check for empty title and add site name if param is set
		$title = JText::_('COM_LUPO_AGE_CATEGORIES');
		if ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		JFactory::getDocument()->setTitle($title);

		// Display the view
		parent::display($tpl);
	}
}
