<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');


/**
 * Lupo Component Controller
 */
class LupoController extends JControllerLegacy {
	function display($cachable = false, $urlparams = []) {
		$document = JFactory::getDocument();
		$app      = JFactory::getApplication('site');
		$wa       = Factory::getApplication()->getDocument()->getWebAssetManager();

		//init session anyway, may it helps with reservation problem...
		$app->getSession();

		$params = $app->getParams();
		$uikit  = $params->get('lupo_load_uikit_css', "0");
		if ($uikit !== "0") {
			$wa->registerAndUseStyle('uikit', "components/com_lupo/uikit/css/" . $uikit);
			$wa->registerAndUseStyle('uikit.slider', "components/com_lupo/uikit/css/components/slider." . str_replace('uikit.', '', $uikit));
			$wa->registerAndUseStyle('com_lupo', "components/com_lupo/uikit/css/components/slidenav." . str_replace('uikit.', '', $uikit));
		}
		$wa->registerAndUseStyle('com_lupo', 'components/com_lupo/css/com_lupo.css?v=2026-01-29');

		//load uikit js anyway
		$wa->registerAndUseScript('uikit', 'components/com_lupo/uikit/js/uikit.min.js', [], [], ['jquery']);
		$wa->registerAndUseScript('uikit.modal', 'components/com_lupo/uikit/js/core/modal.min.js', [], [], ['uikit']);
		$wa->registerAndUseScript('uikit.lightbox', 'components/com_lupo/uikit/js/components/lightbox.min.js', [], [], ['uikit']);
		$wa->registerAndUseScript('uikit.slider', 'components/com_lupo/uikit/js/components/slider.min.js', [], [], ['uikit']);

		$wa->registerAndUseScript('com_lupo', 'components/com_lupo/js/lupo.js', [], [], ['uikit']);


		$view = $app->input->getCmd('view');
		$id   = $app->input->getCmd('id', 0);

		if (in_array($view, ['category', 'agecategory', 'genre'])) {
			$com_foto_list_show  = $params->get('foto_list_show', "0");
			$menu_foto_list_show = $app->input->getCmd('foto_list_show', '');
			if ($menu_foto_list_show == '') {
				$foto_list_show = $com_foto_list_show;
			} else {
				$foto_list_show = $menu_foto_list_show;
			}

			$com_foto_list_prefix  = $params->get('foto_list_prefix', "mini_");
			$menu_foto_list_prefix = $app->input->getCmd('foto_list_prefix', 'mini_');
			if ($menu_foto_list_prefix == '') {
				$foto_list_prefix = $com_foto_list_prefix;
			} else {
				$foto_list_prefix = $menu_foto_list_prefix;
			}

			$filter_types              = [];
			$com_show_category_filter  = $params->get('category_show_category_filter', '1') == '1';
			$menu_show_category_filter = $app->input->getCmd('filter_category_list_show', "");
			if ($menu_show_category_filter == '') {
				$filter_list_show = $com_show_category_filter;
			} else {
				$filter_list_show = $menu_show_category_filter;
			}
			if ($filter_list_show == 1 && ($view != 'category' || $id == 'new')) {
				$filter_types[] = 'category';
			}

			$com_show_agecategory_filter  = $params->get('category_show_agecategory_filter', '1') == '1';
			$menu_show_agecategory_filter = $app->input->getCmd('filter_agecategory_list_show', "");
			if ($menu_show_agecategory_filter == '') {
				$filter_list_show = $com_show_agecategory_filter;
			} else {
				$filter_list_show = $menu_show_agecategory_filter;
			}
			if ($filter_list_show == 1 && $view != 'agecategory') {
				$filter_types[] = 'agecategory';
			}

			$com_show_genre_filter  = $params->get('category_show_genre_filter', '0') == '1';
			$menu_show_genre_filter = $app->input->getCmd('filter_genre_list_show', "");
			if ($menu_show_genre_filter == '') {
				$filter_list_show = $com_show_genre_filter;
			} else {
				$filter_list_show = $menu_show_genre_filter;
			}
			if ($filter_list_show == 1 && $view != 'genre') {
				$filter_types[] = 'genre';
			}
		}


		switch ($view) {
			case 'game':
				$model = $this->getModel();
				$game  = $model->getGame($id, true);

				if ($game !== 'error') {
					$document->setMetaData('og:title', $game['title'], 'property');
					$description = strlen($game['description_title']) > 0 ? substr($game['description_title'], 0, 297) : substr($game['description'], 0, 297);
					$document->setMetaData('og:description', $description, 'property');
					$document->setMetaData('og:image', JUri::base() . $game['image'], 'property');
				}
				$view       = $this->getView('Game', 'html');
				$view->game = $game;
				$view->display();
				break;
			case 'genre':
				$model             = $this->getModel();
				$genre             = $model->getGenre($id);
				$games             = $model->getGamesByGenre($id, $foto_list_prefix);
				$subsets           = $model->getSubsets($filter_types, $games);
				$view              = $this->getView('Genre', 'html');
				$view->title       = $genre['genre'] ?? JText::_('JERROR_PAGE_NOT_FOUND');
				$view->description = '';
				$view->genre       = $genre;
				$view->subsets     = $subsets;
				$view->games       = $games;
				$view->foto        = ['show' => $foto_list_show && $model->hasFoto($games), 'prefix' => $foto_list_prefix];
				$view->display();
				break;
			case 'category':
				$model             = $this->getModel();
				$category          = $model->getCategory($id);
				$games             = $model->getGames($id, 'catid', $foto_list_prefix);
				$subsets           = $model->getSubsets($filter_types, $games);
				$view              = $this->getView('Category', 'html');
				$view->title       = $category['title'] ?? JText::_('JERROR_PAGE_NOT_FOUND');
				$view->description = $category['description'] ?? '';
				$view->category    = $category;
				$view->subsets     = $subsets;
				$view->games       = $games;
				$view->foto        = ['show' => $foto_list_show && $model->hasFoto($games), 'prefix' => $foto_list_prefix];
				$view->display();
				break;
			case 'agecategory':
				$model             = $this->getModel();
				$agecategory       = $model->getAgecategory($id);
				$games             = $model->getGames($id, 'age_catid', $foto_list_prefix);
				$subsets           = $model->getSubsets($filter_types, $games);
				$view              = $this->getView('Agecategory', 'html');
				$view->title       = $agecategory['title'] ?? JText::_('JERROR_PAGE_NOT_FOUND');
				$view->description = $agecategory['description'] ?? '';
				$view->agecategory = $agecategory;
				$view->subsets     = $subsets;
				$view->games       = $games;
				$view->foto        = ['show' => $foto_list_show && $model->hasFoto($games), 'prefix' => $foto_list_prefix];
				$view->display();
				break;
			case 'agecategories':
				$view                = $this->getView('Agecategories', 'html');
				$model               = $this->getModel();
				$agecategories       = $model->getAgecategories(false);
				$view->agecategories = $agecategories;
				$view->display();
				break;
			case 'categories':
			default:
				$view             = $this->getView('Categories', 'html');
				$model            = $this->getModel();
				$categories       = $model->getCategories(false);
				$view->categories = $categories;
				$view->display();
				break;
		}
	}


	/**
	 * store reservation to session
	 */
	public function resadd() {
		$app     = JFactory::getApplication('site');
		$jinput  = $app->input;
		$toynr   = $jinput->get('toynr', '', 'STRING');
		$toyname = $jinput->get('toyname', '', 'STRING');

		$session              = $app->getSession();
		$reservations         = $session->get('lupo_reservations');
		$reservations[$toynr] = (object) ['toynr' => $toynr, 'toyname' => $toyname];
		$session->set('lupo_reservations', $reservations);

		echo json_encode(['msg' => 'ok', 'reservations_nbr' => count($reservations)]);
	}


	/**
	 * delete reservation from session
	 */
	public function resdel() {
		$app    = JFactory::getApplication('site');
		$jinput = $app->input;
		$toynr  = $jinput->get('toynr', '', 'STRING');

		$session      = $app->getSession();
		$reservations = $session->get('lupo_reservations');
		unset($reservations[$toynr]);
		$session->set('lupo_reservations', $reservations);

		echo json_encode(['msg' => 'ok', 'reservations_nbr' => count($reservations)]);
	}


	/**
	 * sends reservation email
	 */
	public function sendres() {
		$app     = JFactory::getApplication('site');
		$session = $app->getSession();
		$config  = JFactory::getConfig();
		$mailer  = JFactory::getMailer();
		$params  = $app->getParams();
		$model   = $this->getModel();

		$jinput = JFactory::getApplication()->input;
		//$recaptcha_response = $jinput->get('g-recaptcha-response', '', 'STRING');
		$clientname   = $jinput->get('clientname', '', 'STRING');
		$clientnr     = $jinput->get('clientnr', '', 'STRING');
		$clientemail  = $jinput->get('clientemail', '', 'STRING');
		$clientmobile = $jinput->get('clientmobile', '', 'STRING');
		$resnow       = $jinput->get('resnow', '', 'STRING');
		$resdate      = $jinput->get('resdate', '', 'STRING');
		$comment      = $jinput->get('comment', '', 'STRING');


		$formerror = [];
		if (!$mailer->ValidateAddress($clientemail)) {
			$formerror[] = '• ' . JText::_('COM_LUPO_RES_FORM_INVALIV_EMAIL');
		}
		if ($clientname == "") {
			$formerror[] = '• ' . JText::_('COM_LUPO_RES_FORM_INVALIV_NAME');
		}
		if ($params->get('detail_show_res_phone', '1') == 1 && $clientmobile == "") {
			$formerror[] = '• ' . JText::_('COM_LUPO_RES_FORM_INVALIV_MOBILE');
		}
		if ($params->get('detail_show_res_date', '1') == 1) {
			if ($resnow == '' && $resdate == '') {
				$formerror[] = '• ' . JText::_('COM_LUPO_RES_FORM_MISSING_RESDATE');
			}
		}

		if (!empty($formerror)) {
			echo implode('<br>', $formerror);

			return;
		}

		$reservations = $session->get('lupo_reservations');
		if ($reservations == null) {
			echo "No reservations found";

			return;
		}
		$reservated_toys = '';
		foreach ($reservations as $reservation) {
			$model->storeReservation($clientnr, $reservation->toynr);
			$reservated_toys .= $reservation->toynr . str_repeat(" ", 15 - strlen($reservation->toynr)) . $reservation->toyname . "\n";
		}

		$email_text = $params->get('detail_toy_res_email_body', "");
		$subject    = $params->get('detail_toy_res_email_subject', "");

		$body = $email_text . "\n\n";
		$body .= str_pad(JText::_('COM_LUPO_RES_EMAIL_BODY_TOYS'), 15) . "\n$reservated_toys\n";
		if ($resdate != "") {
			$body .= str_pad(JText::_('COM_LUPO_RES_EMAIL_BODY_RES_FROM'), 15) . "$resdate\n\n";
		}
		$body .= str_pad(JText::_('COM_LUPO_RES_EMAIL_BODY_CLIENT_NAME'), 15) . "$clientname\n";
		$body .= str_pad(JText::_('COM_LUPO_RES_EMAIL_BODY_CLIENT_NUMBER'), 15) . "$clientnr\n";
		$body .= str_pad(JText::_('COM_LUPO_RES_EMAIL_BODY_CLIENT_EMAIL'), 15) . "$clientemail\n\n";
		$body .= str_pad(JText::_('COM_LUPO_RES_EMAIL_BODY_CLIENT_MOBILE'), 15) . "$clientmobile\n\n";
		$body .= str_pad(JText::_('COM_LUPO_RES_EMAIL_BODY_COMMENTS'), 15) . "\n$comment\n\n";
		$mailer->setSubject(sprintf($subject, $config->get('sitename'), $clientname));
		$mailer->setBody($body);


		$res_sendfrom = $params->get('detail_toy_res_sendfrom', "");

		if ($res_sendfrom != "") {
			$sender = $res_sendfrom;
		} else {
			$sender = [
				$config->get('mailfrom'),
				$config->get('fromname'),
			];
		}

		$res_sendto     = $params->get('detail_toy_res_sendto', "");
		$ludo_recipient = $res_sendto != '' ? $res_sendto : $config->get('mailfrom');

		$mailer->setSender($sender);

		$mailer->addRecipient($clientemail);
		$mailer->addReplyTo($ludo_recipient);
		$send_client = $mailer->Send();

		$mailer->clearAddresses();
		$mailer->clearReplyTos();

		$mailer->addRecipient($ludo_recipient);
		$mailer->addReplyTo($clientemail);
		$send_ludo = $mailer->Send();

		if (($send_client && $send_ludo) !== true) {
			echo '• ' . JText::_('COM_LUPO_RES_ERROR_SENDING_EMAIL') . ': ' . $send_client->__toString() . $send_ludo->__toString();
		} else {
			$session->set('lupo_reservations', null);
			echo 'ok';
		}
	}

	/**
	 * prolongation of toy retour date
	 *
	 * task is called by ajax-call
	 * only update if session client-id is equal with toy-recordset
	 *
	 * returns 'error' if failed or new retour-date
	 */
	public function prolong() {
		$jinput  = JFactory::getApplication()->input;
		$lupo_id = $jinput->get('lupo_id', '', 'STRING');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$session = JFactory::getSession();
		$client  = $session->get('lupo_client');
		if (!$client) {
			echo 'error';

			return;
		}

		$componentParams        = JComponentHelper::getParams('com_lupo');
		$prolongation_enabled   = $componentParams->get('lupo_prolongation_enabled', '0') == 1;
		$prolongation_valid_abo = $componentParams->get('lupo_prolongation_valid_abo', '0') == 1;
		$hasAbo                 = $client->aboenddat != "0000-00-00";
		$hasValidAbo            = $hasAbo && $client->aboenddat >= date("Y-m-d");

		if (!$prolongation_enabled || ($prolongation_valid_abo && !$hasValidAbo)) {
			echo 'error';

			return;
		}

		// Fields to update.
		$fields     = [
			$db->quoteName('return_extended') . ' = 1',
			$db->quoteName('return_extended_online') . ' = 1',
			$db->quoteName('return_date') . ' = ' . $db->quoteName('return_date_extended'),
		];
		$conditions = [
			$db->quoteName('adrnr') . ' = ' . $db->quote($client->adrnr),
			$db->quoteName('lupo_id') . ' = ' . $db->quote($lupo_id),
		];

		$query->update($db->quoteName('#__lupo_clients_borrowed'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();

		if ($result && $db->getAffectedRows() == 1) {
			//query to get new date
			$query = $db->getQuery(true);
			$query->select('return_date')
				->from('#__lupo_clients_borrowed')
				->where($db->quoteName('lupo_id') . ' = ' . $db->quote($lupo_id));
			$db->setQuery($query);
			$row = $db->loadObject();

			if ($row) {
				echo date('d.m.Y', strtotime($row->return_date));
			} else {
				echo "error";
			}
		} else {
			echo "error";
		}

		return;
	}


	/**
	 * LUPO API - update toy rental state and client-table
	 */
	public function api() {
		$jinput = JFactory::getApplication()->input;
		$act    = $jinput->get('act', '', 'STRING');
		$token  = $jinput->get('token', '', 'STRING');
		$data   = $jinput->get('data', '', 'STRING');

		$db = JFactory::getDbo();

		if (!$this->autohorize($token)) {
			echo 'error_token';

			return;
		}

		switch ($act) {
			case 'authorize':
				echo "ok";
				break;
			case 'adr':
				//$data = '[{ "nr":"41","un":"REGU","vn":"Regula","nn":"Gubler","em":"mail@example.com","te":"079 132 45 67","ae":"2016-9-15","at":"Jahresabo"}]';
				$rows = json_decode($data);

				if (!is_array($rows)) {
					echo "data:" . $data;
					echo "nodata";

					return;
				}

				foreach ($rows as $row) {
					$client            = new stdClass();
					$client->adrnr     = $row->nr;
					$client->username  = $row->un;
					$client->firstname = $row->vn;
					$client->lastname  = $row->nn;
					$client->email     = $row->em ?? '';
					$client->phone     = $row->te ?? '';
					$client->aboenddat = $row->ae == '' ? '0000-00-00' : $row->ae;
					$client->abotype   = $row->at;

					// wäre schöner so, sollte updaten on duplicate key: GEHT ABER NICHT
					//$result = JFactory::getDbo()->insertObject('#__lupo_clients', $client, 'adrnr');

					try {
						$result = JFactory::getDbo()->insertObject('#__lupo_clients', $client);
					} catch (Exception $e) {
						$result = JFactory::getDbo()->updateObject('#__lupo_clients', $client, 'adrnr');
					}
				}

				echo $result;
				break;

			case 'aus':
				$rows = json_decode($data);

				if (!is_array($rows)) {
					echo "nodata";

					return;
				}

				//preserve online prolongations
				$query = $db->getQuery(true);
				$query->select('*')
					->from('#__lupo_clients_borrowed')
					->where($db->quoteName('return_extended_online') . ' = 1');
				$db->setQuery($query);
				$preserved_prolongations = $db->loadObjectList();


				//delete all records
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__lupo_clients_borrowed'));
				$db->setQuery($query);
				$db->execute();

				//cache query-result. min 3x faster
				$query    = $db->setQuery('SELECT #__lupo_game_editions.id, #__lupo_game.`number` FROM #__lupo_game_editions LEFT JOIN #__lupo_game ON #__lupo_game_editions.gameid = #__lupo_game.id');
				$game_ids = $db->loadAssocList('number', 'id');

				foreach ($rows as $row) {
					$game_nr = $this->addZeroToGameNumber($row->nr);

					if (isset($game_ids[$game_nr])) { //only process if game exists in online-catalogue
						$client                       = new stdClass();
						$client->lupo_id              = $row->id;  //LFDAUSLEIHNR
						$client->adrnr                = $row->adr; //ADRNR
						$client->tax_extended         = $row->tx;  //tx = gebühr für verlängerung
						$client->game_number          = $game_nr;
						$client->edition_id           = $game_ids[$game_nr]; //TODO: remove -> refactor without editions-table
						$client->return_date          = $row->rd;            //rd = returdate
						$client->return_date_extended = $row->ed;            //vd = verlängerungs-datum (extended date)
						$client->return_extended      = $row->ex;            //is extended (spiel wurde verlängert)
						$client->reminder_sent        = $row->re;
						$client->next_reservation     = $row->rs == "" ? null : $row->rs;
						$client->quarantine           = $row->qu == "" ? null : $row->qu;

						try {
							JFactory::getDbo()->insertObject('#__lupo_clients_borrowed', $client);

							if (is_array($preserved_prolongations)) {
								foreach ($preserved_prolongations as $preserved_prolongation) {
									if ($client->lupo_id == $preserved_prolongation->lupo_id && $client->return_extended == 0) {
										// Fields to update.
										$fields     = [
											$db->quoteName('return_extended') . ' = 1',
											$db->quoteName('return_extended_online') . ' = 1',
											$db->quoteName('return_date') . ' = ' . $db->quoteName('return_date_extended'),
										];
										$conditions = [
											$db->quoteName('lupo_id') . ' = ' . $db->quote($client->lupo_id),
										];

										$query = $db->getQuery(true);
										$query->update($db->quoteName('#__lupo_clients_borrowed'))->set($fields)->where($conditions);

										$db->setQuery($query);
										$db->execute();
									}
								}
							}

							//delete in online-reservation table if reservation in lupo exists for this toy
							if ($row->rs) {
								$query = $db->getQuery(true);
								$query->delete($db->quoteName('#__lupo_reservations_web'));
								$query->where($db->quoteName('game_number') . ' = ' . $db->quote($game_nr));
								$db->setQuery($query);
								$db->execute();
							}

						} catch (Exception $e) {
							echo "error";
							//echo $e->getMessage();  //todo error handling here and in LUPO
							die();
						}
					}
				}

				//store upload date
				$stats_file = JPATH_ROOT . '/images/upload_stats.json';
				if (file_exists($stats_file)) {
					$json = json_decode(file_get_contents($stats_file), true);
				}
				$json['websync_ausleihen'] = date('Y-m-d H:i:s');
				file_put_contents($stats_file, json_encode($json));

				echo 'ok';
				break;

			case 'res':
				//$data= [{"nr":"0.1", "rs":"2016-05-12"}]
				$rows = json_decode($data);

				if (!is_array($rows)) {
					echo "nodata";

					return;
				}

				//remove all reservation dates
				$fields = [
					$db->quoteName('next_reservation') . ' = NULL',
				];
				$query  = $db->getQuery(true);
				$query->update($db->quoteName('#__lupo_game_editions'))->set($fields);

				$db->setQuery($query);
				$db->execute();

				//cache query-result. min 3x faster
				$query    = $db->setQuery('SELECT #__lupo_game_editions.id, #__lupo_game.`number` FROM #__lupo_game_editions LEFT JOIN #__lupo_game ON #__lupo_game_editions.gameid = #__lupo_game.id');
				$game_ids = $db->loadAssocList('number', 'id');

				foreach ($rows as $row) {
					$game_nr = $this->addZeroToGameNumber($row->nr);

					if (isset($game_ids[$game_nr])) { //only process if game exists in online-catalogue
						try {
							$fields     = [
								$db->quoteName('next_reservation') . ' = ' . $db->quote($row->rs),
							];
							$conditions = [
								$db->quoteName('id') . ' = ' . $game_ids[$game_nr],
							];

							$query = $db->getQuery(true);
							$query->update($db->quoteName('#__lupo_game_editions'))->set($fields)->where($conditions);

							$db->setQuery($query);
							$db->execute();


							//delete in online-reservation table if reservation in lupo exists for this toy
							$query = $db->getQuery(true);
							$query->delete($db->quoteName('#__lupo_reservations_web'));
							$query->where($db->quoteName('game_number') . ' = ' . $db->quote($game_nr));
							$db->setQuery($query);
							$db->execute();

						} catch (Exception $e) {
							echo "error"; //todo
							die();
						}
					}
				}
				echo 'ok';
				break;

			case 'prolong':
				// reads online prolongations to store them in LUPO
				$query = $db->getQuery(true);
				$query->select('#__lupo_game.number, #__lupo_clients_borrowed.adrnr, #__lupo_clients_borrowed.return_date_extended, #__lupo_clients_borrowed.tax_extended')
					->from('#__lupo_clients_borrowed')
					->join('LEFT', '#__lupo_game ON #__lupo_clients_borrowed.game_number = #__lupo_game.number')
					->where('#__lupo_clients_borrowed.return_extended_online = 1');
				$db->setQuery($query);

				$res = $db->loadObjectList();

				$json = json_encode($res);
				echo $json;
				break;
			default;
				echo 'nothing to do';
				break;
		}

		return;
	}


	/**
	 * compile full game-number (web-table contains full number when editions are exported as single game)
	 *
	 * @param $game_nr
	 *
	 * @return string game-number with index
	 */

	function addZeroToGameNumber($game_nr) {
		if (strpos($game_nr, ".") == 0) {
			return $game_nr . '.0';
		} else {
			return $game_nr;
		}
	}

	/**
	 * authorize request
	 *
	 * @param $token
	 *
	 * @return string ok or error_token
	 */
	public function autohorize($token) {
		$params       = JComponentHelper::getParams('com_lupo');
		$config_token = $params->get('lupo_websynctoken');

		if ($token == $config_token) {
			return true;
		} else {
			return false;
		}
	}
}
