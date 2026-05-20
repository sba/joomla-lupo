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

/**
 * Lupo Model
 *
 * @since  3.43.0
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class LupoModelFilter extends BaseDatabaseModel
{
  /**
   * Resolve table name by filter type.
   *
   * @param string $filterType
   *
   * @return string
   */
  private function getFilterTableByType($filterType)
  {
    if ($filterType === 'agecategory') {
      return '#__lupo_agecategories';
    }

    return '#__lupo_categories';
  }

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
					    #__lupo_categories
					ORDER BY
					    `sort`,`alias`"
        );
        return $db->loadAssocList();
    }

    /**
     * Get the agecategories
     *
     * @return  array  The toy agecategories
     */
    public function getAgecategories()
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT
					    *
					FROM
					    #__lupo_agecategories
                    ORDER BY 
                        `sort`,`age_number`,`alias`"
        );
        return $db->loadAssocList();
    }

    /**
     * Get the genres
     *
     * @return  array  The genres
     */
    public function getGenres()
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT
					    *
					FROM
					    #__lupo_genres
					ORDER BY 
				        alias"
        );
        return $db->loadAssocList();
    }

    /**
     * Get all different players-entries
     *
     * @return  array  The genres
     */
    public function getPlayers()
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT
					    players
					FROM
					    #__lupo_game
					WHERE     
					    players != ''
                    GROUP BY
                        players
					ORDER BY 
				        players"
        );
        return $db->loadAssocList();
    }

    /**
     * Get the category
     *
     * @return  array  The toy category
     */
    public function getCategory()
    {
        $input = Factory::getApplication()->input;
        $id    = $input->get('id');
        $filterType = $input->getCmd('filter_type', 'category');
        $table = $this->getFilterTableByType($filterType);
        $db    = JFactory::getDBO();
        $db->setQuery("SELECT
					    *
					FROM
                  {$table}
					WHERE id = " . $db->quote($id));
        return $db->loadAssoc();
    }


    /**
     * Method to save the form data.
     *
     * @param array $data The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   1.6
     */
    public function save($data)
    {
        $db    = JFactory::getDBO();
        $table = $this->getFilterTableByType($data['filter_type'] ?? 'category');
        $db->setQuery("UPDATE		
                {$table}			    
					SET subsets = " .$db->quote($data['subsets']) ."					    
					WHERE id = " . $db->quote($data['id']))->execute();

		return true;
    }
}