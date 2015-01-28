<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link				http://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');


/**
 * Lupo Component Controller
 */
class LupoController extends JControllerLegacy {
	function display(){
        $document = JFactory::getDocument();
        $app = JFactory::getApplication();
        $params = $app->getParams();
        $uikit = $params->get('lupo_load_uikit_css', "0");
        if($uikit!=="0") {
            $document->addStyleSheet(JURI::base() . "components/com_lupo/uikit/css/".$uikit, 'text/css', "screen");
        }
        $document->addStyleSheet(JURI::base() . "components/com_lupo/css/com_lupo.css",'text/css',"screen");

		//$document->addScript() will not work because its loaded before jquery / uikit
		$document->addCustomTag('<script src="'.JURI::root(true).'/components/com_lupo/uikit/js/uikit.min.js" type="text/javascript"></script>');
		$document->addCustomTag('<script src="'.JURI::root(true).'/components/com_lupo/uikit/js/components/lightbox.min.js" type="text/javascript"></script>');

		$view = $app->input->getCmd('view');
		$id = $app->input->getCmd('id', 0);
		$foto_prefix = $app->input->getCmd('foto_prefix', 'thumb_');
		$foto_show = $app->input->getCmd('foto_show', 0);

		switch($view){
			case 'game':
				$model = & $this->getModel();
				$game = $model->getGame($id);
				$view = & $this->getView('Game', 'html');
				$view->game = $game;
				$view->display();
				break;
			case 'genre':
				$model = &$this->getModel();
				$genre = $model->getGenre($id);
				$games = $model->getGamesByGenre($id);
				$view = &$this->getView('Genre', 'html');
				$view->genre = $genre;
				$view->games = $games;
				$view->display();
				break;
			case 'category':
				$model = &$this->getModel();
				$category = $model->getCategory($id);
				$games = $model->getGames($id, 'catid', $foto_prefix);
				$view = &$this->getView('Category', 'html');
				$view->category = $category;
				$view->games = $games;
				$view->foto_show = $foto_show;
				$view->display();
				break;
			case 'agecategory':
				$model = & $this->getModel();
				$agecategory = $model->getAgecategory($id);
				$games = $model->getGames($id, 'age_catid');
				$view = & $this->getView('Agecategory', 'html');
				$view->agecategory = $agecategory;
				$view->games = $games;
				$view->display();
				break;
			case 'agecategories':
				$view = & $this->getView('Agecategories', 'html');
				$model = & $this->getModel();
				$agecategories = $model->getAgecategories();
				$view->agecategories = $agecategories;
				$view->display();
				break;
			case 'categories':
			default:
				$view = & $this->getView('Categories', 'html');
				$model = & $this->getModel();
				$categories = $model->getCategories();
				$view->categories = $categories;
				$view->display();
				break;
		}
	}
}
