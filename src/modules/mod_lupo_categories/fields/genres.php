<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Genre list field for module configuration.
 */
class JFormFieldGenres extends JFormFieldList
{
    /**
     * Field type
     *
     * @var string
     */
    protected $type = 'Genres';

    /**
     * Build options from com_lupo genres.
     *
     * @return array
     */
    protected function getOptions()
    {
        if (!class_exists('LupoModelLupo')) {
            JLoader::import('lupo', JPATH_SITE . '/components/com_lupo/models');
        }

        $options = parent::getOptions();

        // Query all genres directly instead of LupoModelLupo::getGenres(), which only
        // returns genres that currently have at least one linked game (INNER JOIN).
        // Legacy filter selections must remain selectable even without linked games.
        $db = JFactory::getDbo();
        /** @noinspection SqlResolve */
        $db->setQuery('SELECT alias, genre AS title FROM #__lupo_genres ORDER BY genre');
        $genres = (array) $db->loadAssocList();

	    // Separator token for manual visual grouping in frontend output.
	    $options[] = JHtml::_('select.option', '-', '----------------');

        foreach ($genres as $genre) {
            $options[] = JHtml::_('select.option', (string) $genre['alias'], $genre['title']);
        }

        return $options;
    }
}

