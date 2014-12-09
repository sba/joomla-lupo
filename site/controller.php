<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) 2006 - 2014 databauer / Stefan Bauer
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

		$view = JRequest::getVar('view');
		$id = JRequest::getVar('id');
		
		if($view=='category'){
			$model = & $this->getModel();
			$category = $model->getCategory($id);
			$games = $model->getGames($id);
			
			$view = & $this->getView('Category', 'html');
			$view->assignRef('category',$category);
			$view->assignRef('games',$games);
			
			$view->display();
			
		}elseif($view=='game'){
			$model = & $this->getModel();
			$game = $model->getGame($id);
			
			$view = & $this->getView('Game', 'html');
			$view->assignRef('game',$game);

			$view->display();
			
		} else {
			
			$view = & $this->getView('Categories', 'html');
			$model = & $this->getModel();
			$categories = $model->getCategories();
			$view->assignRef('categories',$categories);
			$view->display();
		}
	}
}
