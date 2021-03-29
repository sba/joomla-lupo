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
 * LUPO component helper.
 *
 * @param   string  $submenu  The name of the active view.
 *
 * @return  void
 *
 * @since   1.6
 */
abstract class LupoHelper extends JHelperContent
{
    /**
     * Configure the Linkbar.
     *
     * @return Bool
     */

    public static function addSubmenu($submenu)
    {
        JHtmlSidebar::addEntry(
            JText::_('Spiele hochladen'),
            'index.php?option=com_lupo',
            $submenu == 'lupos'
        );

        JHtmlSidebar::addEntry(
            JText::_('Suchfilter bearbeiten'),
            'index.php?option=com_lupo&view=filters',
            $submenu == 'filter'
        );
    }
}