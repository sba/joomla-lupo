<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link		https://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Lupo Model
 *
 * @since  3.43.0
 */

use Joomla\CMS\Factory;

class LupoModelFilter extends JModelItem
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
					    #__lupo_agecategories"
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
					    #__lupo_genres"
        );
        return $db->loadAssocList();
    }

    /**
     * Get the categories
     *
     * @return  array  The toy categories
     */
    public function getCategory()
    {
        $input = Factory::getApplication()->input;
        $id    = $input->get('id');
        $db    = JFactory::getDBO();
        $db->setQuery("SELECT
					    *
					FROM
					    #__lupo_categories
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
        $db->setQuery("UPDATE		
                        #__lupo_categories			    
					SET subsets = " .$db->quote($data['subsets']) ."					    
					WHERE id = " . $db->quote($data['id']))->execute();
    }
}