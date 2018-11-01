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
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the LUPO component
 */
class JFormFieldLupoGenre extends JFormFieldList {
	/**
	 * The field type.
	 *
	 * @var        string
	 */
	protected $type = 'LupoGenre';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return    array        An array of JHtml options.
	 */
	protected function getOptions() {
		$db = JFactory::getDBO();
		$db->setQuery("SELECT 
							*
						FROM
							#__lupo_genres
						ORDER BY genre");

		$res = $db->loadAssocList();

		foreach ($res as $row) {
			$alias = JFilterOutput::stringURLSafe($row['genre']);
			$options[] = JHtml::_('select.option', $alias, $row['genre']);
		}

		return $options;
	}
}

