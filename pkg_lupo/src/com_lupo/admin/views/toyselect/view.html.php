<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;

/**
 * Toyselect View for Editor Button
 */
class LupoViewToyselect extends HtmlView {
	protected $items;
	protected $state;
	protected $pagination;

	public function display($tpl = null) {
		$app = Factory::getApplication();
		$input = $app->input;

		if ($input->getCmd('tmpl') === 'component' || $input->getCmd('layout') === 'modal') {
			$this->setLayout('modal');
		}

		$db = Factory::getDbo();
		
		$search = $app->input->getString('search');
		$limit = $app->input->getInt('limit', 20);
		$limitstart = $app->input->getInt('limitstart', 0);

		$query = $db->getQuery(true);
		$query->select('id, number, title');
		$query->from('#__lupo_game');
		
		if (!empty($search)) {
			$search_quoted = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(title LIKE ' . $search_quoted . ' OR number LIKE ' . $search_quoted . ')');
		}
		
		$query->order('number ASC');

		// Get total for pagination
		$db->setQuery($query);
		$db->loadObjectList();
		$total = $db->getNumRows();

		$db->setQuery($query, $limitstart, $limit);
		$this->items = $db->loadObjectList();
		
		jimport('joomla.html.pagination');
		$this->pagination = new JPagination($total, $limitstart, $limit);
		$this->search = $search;

		parent::display($tpl);
	}
}
