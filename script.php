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


class com_lupoInstallerScript
{
	/**
	 * This method is called after a component is updated.
	 *
	 * @param  \stdClass $parent - Parent object calling object.
	 *
	 * @return void
	 */
	public function update($parent)
	{
		jimport('joomla.filter.output');

		$db = JFactory::getDBO();
		$db->setQuery("SELECT 
							*
						FROM
							#__lupo_genres
						ORDER BY genre");

		$res = $db->loadAssocList();

		foreach ($res as $row) {
			$query = $db->getQuery(true);
			$alias = str_replace( "-", "_", JFilterOutput::stringURLSafe($row['genre']));
			$fields = array(
				$db->quoteName('alias') . ' = ' . $db->quote($alias)
			);
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($row['id'])
			);
			$query->update($db->quoteName('#__lupo_genres'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$db->execute();
		}
	}
}