<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) 2006 - 2014 databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link		https://www.ludothekprogramm.ch
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
class JFormFieldLupo extends JFormFieldList {
	/**
	 * The field type.
	 *
	 * @var        string
	 */
	protected $type = 'Lupo';

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
				    COALESCE(#__lupo_categories.id,0) AS id
				    , IF(ISNULL(#__lupo_categories.title),'" . JText::_('COM_LUPO_VARIOUS_TOYS') . "',#__lupo_categories.title) AS title
				FROM
				    #__lupo_game
				    LEFT JOIN #__lupo_categories ON (#__lupo_categories.id = #__lupo_game.catid)
				GROUP BY catid
				HAVING COUNT(#__lupo_game.id) > 0
				ORDER BY #__lupo_categories.sort, #__lupo_categories.title");

		$res = $db->loadAssocList();

		foreach ($res as $row) {
			$options[] = JHtml::_('select.option', $row['id'], $row['title']);
		}

		return $options;
	}
}

