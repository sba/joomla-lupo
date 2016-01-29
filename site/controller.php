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
            $document->addStyleSheet("components/com_lupo/uikit/css/".$uikit, 'text/css', "screen");
        }
        $document->addStyleSheet("components/com_lupo/css/com_lupo.css",'text/css',"screen");

		//load uikit. uncomment if uikit is not loaded with template
		//$document->addScript() will not work because its loaded before jquery / uikit
		//$document->addCustomTag('<script src="'.JURI::root(true).'/components/com_lupo/uikit/js/uikit.min.js" type="text/javascript"></script>');
		//$document->addCustomTag('<script src="'.JURI::root(true).'/components/com_lupo/uikit/js/core/modal.min.js" type="text/javascript"></script>');
		//$document->addCustomTag('<script src="'.JURI::root(true).'/components/com_lupo/uikit/js/components/lightbox.min.js" type="text/javascript"></script>');

		$view = $app->input->getCmd('view');
		$id = $app->input->getCmd('id', 0);

		$com_foto_list_show = $params->get('foto_list_show', "0");
		$com_foto_list_prefix = $params->get('foto_list_prefix', "mini_");

		$menu_foto_list_show = $app->input->getCmd('foto_list_show', '');
		$menu_foto_list_prefix = $app->input->getCmd('foto_list_prefix', 'mini_');

		if($menu_foto_list_show == ''){
			$foto_list_show = $com_foto_list_show;
		} else {
			$foto_list_show = $menu_foto_list_show;
		}

		if($menu_foto_list_prefix == ''){
			$foto_list_prefix = $com_foto_list_prefix;
		} else {
			$foto_list_prefix = $menu_foto_list_prefix;
		}

		switch($view){
			case 'game':
				$model = $this->getModel();
				$game = $model->getGame($id);
				$view = $this->getView('Game', 'html');
				$view->game = $game;
				$view->display();
				break;
			case 'genre':
				$model = $this->getModel();
				$genre = $model->getGenre($id);
				$games = $model->getGamesByGenre($id, $foto_list_prefix);
				$view = $this->getView('Genre', 'html');
				$view->genre = $genre;
				$view->games = $games;
				$view->foto = array('show'=>$foto_list_show, 'prefix'=>$foto_list_prefix);
				$view->display();
				break;
			case 'category':
				$model = $this->getModel();
				$category = $model->getCategory($id);
				$games = $model->getGames($id, 'catid', $foto_list_prefix);
				$view = $this->getView('Category', 'html');
				$view->category = $category;
				$view->games = $games;
				$view->foto = array('show'=>$foto_list_show, 'prefix'=>$foto_list_prefix);
				$view->display();
				break;
			case 'agecategory':
				$model = $this->getModel();
				$agecategory = $model->getAgecategory($id);
				$games = $model->getGames($id, 'age_catid', $foto_list_prefix);
				$view = $this->getView('Agecategory', 'html');
				$view->agecategory = $agecategory;
				$view->games = $games;
				$view->foto = array('show'=>$foto_list_show, 'prefix'=>$foto_list_prefix);
				$view->display();
				break;
			case 'agecategories':
				$view = $this->getView('Agecategories', 'html');
				$model = $this->getModel();
				$agecategories = $model->getAgecategories();
				$view->agecategories = $agecategories;
				$view->display();
				break;
			case 'categories':
			default:
				$view = $this->getView('Categories', 'html');
				$model = $this->getModel();
				$categories = $model->getCategories();
				$view->categories = $categories;
				$view->display();
				break;
		}
	}
}
