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
     * Get the categories
     *
     * @return  array  The toy categories
     */
    public function getCategories()
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT
					    *
					FROM
					    #__lupo_categories"
					);
        return $db->loadAssocList();
    }
}