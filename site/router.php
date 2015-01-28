<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link		http://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Build the route for the com_lupo component
 *
 * @param	array	An array of URL arguments
 *
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 */
function LupoBuildRoute(&$query) {
    $segments = array();
    if(isset($query['view'])) {
        $segments[] = $query['view'];
        unset( $query['view'] );
    }
    if(isset($query['id'])){
        $segments[] = $query['id'];
        unset( $query['id'] );
    };
    return $segments;
}


/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 */
function LupoParseRoute($segments) {
    $params = JComponentHelper::getParams('com_lupo');
    $itemid = $params->get('lupomenuitem');

    $vars = array();
    switch($segments[0]) {
        case 'categories':
            $vars['view'] = 'categories';
            break;
        case 'agecategories':
            $vars['view'] = 'agecategories';
            break;
        case 'category':
            $vars['view'] = 'category';
            $id = explode( ':', $segments[1] );
            $vars['id'] = $id[0];
            $vars['Itemid'] = $itemid;
            break;
        case 'agecategory':
            $vars['view'] = 'agecategory';
            $id = explode( ':', $segments[1] );
            $vars['id'] = $id[0];
            $vars['Itemid'] = $itemid;
            break;
        case 'genre':
            $vars['view'] = 'genre';
            $id = explode( ':', $segments[1] );
            $vars['id'] = $id[0];
            $vars['Itemid'] = $itemid;
            break;
        case 'game':
            $vars['view'] = 'game';
            $id = explode( ':', $segments[1] );
            $vars['id'] = $id[0];
            $vars['Itemid'] = $itemid;
            break;
    }
    return $vars;
}

