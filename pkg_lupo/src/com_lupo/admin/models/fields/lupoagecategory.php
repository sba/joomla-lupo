<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */


// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the LUPO component
 */
class JFormFieldLupoAgecategory extends JFormFieldList {
	/**
	 * The field type.
	 *
	 * @var        string
	 */
	protected $type = 'LupoAgecategory';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return    array        An array of JHtml options.
	 */
	protected function getOptions() {
		$options   = array();
		$options[] = JHtml::_('select.option', 'new', JText::_('COM_LUPO_NEW_TOYS'));

		$db = JFactory::getDBO();

		$db->setQuery("SELECT
				    alias AS id
				    , IF(ISNULL(#__lupo_agecategories.title),'" . JText::_('COM_LUPO_VARIOUS_TOYS') . "',#__lupo_agecategories.title) AS title
				FROM
				    #__lupo_game
				    LEFT JOIN #__lupo_agecategories ON (#__lupo_agecategories.id = #__lupo_game.age_catid)
				GROUP BY age_catid
				HAVING COUNT(#__lupo_game.id) > 0
				ORDER BY #__lupo_agecategories.sort, #__lupo_agecategories.title");

		$res = $db->loadAssocList();

		foreach ($res as $row) {
			$options[] = JHtml::_('select.option', $row['id'], $row['title']);
		}

		return $options;
	}
}

