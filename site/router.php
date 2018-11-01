<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer 
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
	$itemid = $params->get('lupomenuitem', '0');

	if($itemid!='0') {
		$app = JFactory::getApplication();
		$app->getMenu()->setActive( $itemid );
	}

	$vars = array();
	switch ($segments[0]) {
		case 'categories':
			$vars['view'] = 'categories';
			break;
		case 'agecategories':
			$vars['view'] = 'agecategories';
			break;
		case 'category':
			$vars['view']   = 'category';
			$alias = str_replace(":","-", $segments[1]); //bugfix to support alias-routing... TODO: find proper solution
			$vars['id']     = $alias;
			$vars['Itemid'] = $itemid;
			break;
		case 'agecategory':
			$vars['view']   = 'agecategory';
			$alias = str_replace(":","-", $segments[1]); //bugfix to support alias-routing... TODO: find proper solution
			$vars['id']     = $alias;
			$vars['Itemid'] = $itemid;
			break;
		case 'genre':
			$vars['view']   = 'genre';
			$id             = explode(':', $segments[1]);
			$vars['id']     = $id[0];
			$vars['Itemid'] = $itemid;
			break;
		case 'game':
			$vars['view'] = 'game';
			$id           = explode(':', $segments[1]);
			$vars['id']   = $id[0];

			if (!class_exists('LupoModelLupo')) {
				JLoader::import('lupo', JPATH_BASE . '/components/com_lupo/models');
			}
			$model      = new LupoModelLupo();
			$cat_itemid = $model->getCategoryItemId($id[0]);
			if ($cat_itemid !== false) {
				$itemid = $cat_itemid;
			}
			$vars['Itemid'] = $itemid;
			break;
	}

	return $vars;
}

