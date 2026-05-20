<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */


// No direct access to this file
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die('Restricted access');

/**
 * Lupo Model
 *
 * @since  3.43.0
 */
class LupoModelFilters extends BaseDatabaseModel
{
    /**
   * Get categories and agecategories for filter configuration.
     *
   * @return  array
     */
  public function getItems()
    {
    $db = JFactory::getDBO();
    $query = "
      SELECT
        c.id,
        c.title,
        c.alias,
        c.subsets,
        'category' AS filter_type,
        0 AS sort_group,
        c.sort AS sort_value
      FROM #__lupo_categories AS c

      UNION ALL

      SELECT
        ac.id,
        ac.title,
        ac.alias,
        ac.subsets,
        'agecategory' AS filter_type,
        1 AS sort_group,
        ac.sort AS sort_value
      FROM #__lupo_agecategories AS ac

      ORDER BY sort_group, sort_value, title
    ";

    $db->setQuery($query);

    return $db->loadAssocList();
    }
}