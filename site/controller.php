<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link		http://www.ludothekprogramm.ch
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

	/**
	 * sends reservation email
	 */
	public function sendres(){
		$jinput = JFactory::getApplication()->input;
		$clientname = $jinput->get('clientname', '', 'STRING');
		$clientnr= $jinput->get('clientnr', '', 'STRING');
		$clientemail= $jinput->get('clientemail', '', 'STRING');
		$comment= $jinput->get('comment', '', 'STRING');
		$toynr= $jinput->get('toynr', '', 'STRING');
		$toyname= $jinput->get('toyname', '', 'STRING');

		$mailer = JFactory::getMailer();

		$formerror = false;
		if (!$mailer->ValidateAddress($clientemail)){
			$formerror = 'Ungültige Email';
		}
		if($clientname==""){
			$formerror = 'Name erforderlich';
		}
		if($formerror!==false){
			echo $formerror;
			return;
		}

		$config = JFactory::getConfig();
		$sender = array(
			$config->get( 'mailfrom' ),
			$config->get( 'fromname' )
		);

		$mailer->setSender($sender);

		$recipient = array($clientemail, $config->get( 'mailfrom' )) ;
		$mailer->addRecipient($recipient);
		$mailer->addReplyTo($clientemail);

		$body   = "Spiel-Nr:     $toynr\n";
		$body  .= "Spiel:        $toyname\n\n";
		$body  .= "Kundenname:   $clientname\n";
		$body  .= "Kundennummer: $clientnr\n";
		$body  .= "Email:        $clientemail\n\n";
		$body  .= "Bemerkungen:\n$comment\n\n";
		$mailer->setSubject($config->get( 'sitename' ) .' Spielreservation ' .$toynr .' - '.$toyname);
		$mailer->setBody($body);

		$send = $mailer->Send();
		if ( $send !== true ) {
			echo 'Error sending email: ' . $send->__toString();
		} else {
			echo 'ok';
		}

		return;

	}

	/**
	 * LUPO API - update toy rental state and client-table
	 */
	public function api() {
		$jinput = JFactory::getApplication()->input;
		$act = $jinput->get('act', '', 'STRING');
		$token = $jinput->get('token', '', 'STRING');
		$data = $jinput->get('data', '', 'STRING');

		switch($act){
			case 'authorize':
				if($this->autohorize($token)){
					echo "ok";
				} else {
					echo 'error_token';
				}
				break;
			case 'adr':
				if(!$this->autohorize($token)){
					echo 'error_token';
					return;
				}

				$data = urldecode($data);
				$data = utf8_encode($data);
				//$data = '[{ "nr":"41","un":"REGU","vn":"Regula","nn":"Gubler","ae":"2016-9-15","at":"Jahresabo"}]';
				$arr = json_decode($data);

				foreach($arr as $row){
					$client = new stdClass();
					$client->adrnr = $row->nr;
					$client->username = $row->un;
					$client->firstname = $row->vn;
					$client->lastname = $row->nn;
					$client->aboenddat = $row->ae;
					$client->abotype = $row->at;

					// wäre schöner so, sollte updaten on duplicate key: GEHT ABER NICHT
					//$result = JFactory::getDbo()->insertObject('#__lupo_clients', $client, 'adrnr');

					try {
						$result = JFactory::getDbo()->insertObject('#__lupo_clients', $client);
					}
					catch (Exception $e){
						$result = JFactory::getDbo()->updateObject('#__lupo_clients', $client, 'adrnr');
					}
				}

				echo $result;

			case 'aus':
				if(!$this->autohorize($token)){
					echo 'error_token';
					return;
				}

				$data = urldecode($data);
				$data = utf8_encode($data);
				$arr = json_decode($data);

				//delete all records
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__lupo_borrowed'));
				$db->setQuery($query);
				$result = $db->execute();

				//insert into
				foreach($arr as $row){
					//get edition_id

					//get Number and Index
					if(strpos($row->nr,".")==0){
						$game_nr = $row->nr;
						$game_index = 0;
					} else {
						list($game_nr, $game_index) = explode(".", $row->nr);
					}

					$query = $db->getQuery(true);
					$query->select('#__lupo_game_editions.id')
						->from('#__lupo_game_editions')
						->join('LEFT', '#__lupo_game ON #__lupo_game_editions.gameid = #__lupo_game.id')
						->where('#__lupo_game.number = '.$game_nr.' AND #__lupo_game_editions.index = '.$game_index);
					$db->setQuery($query);
					$edition_row = $db->loadObject();
					
					$client = new stdClass();
					$client->adrnr = $row->adr;
					$client->edition_id = $edition_row->id;
					$client->return_date = $row->rd;
					$client->return_extended = $row->re;

					try {
						$result = JFactory::getDbo()->insertObject('#__lupo_borrowed', $client);
					}
					catch (Exception $e){
						echo "error"; //todo
					}
				}

				echo $result;

		}

		return;
	}

	/**
	 * authorize request
	 *
	 * @param $token
	 *
	 * @return string ok or error_token
	 */
	public function autohorize($token) {
		if($token == md5('luposalz+Ludothek Zofingen')) {
			return true;
		} else {
			return false;
		}
	}


}
