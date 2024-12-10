<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// No direct access to this file
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * Lupo Model
 */
class LupoModelLupo extends BaseDatabaseModel {
	/**
	 * @var object item
	 */
	protected $item;

	protected $upload_date;

	/**
	 * Constructor method for the LupoModelLupo class
	 */
	public function __construct() {
		parent::__construct();

		$upload_date = '';
		$stats_file = JPATH_ROOT . '/images/upload_stats.json';
		if (file_exists($stats_file)) {
			$json = json_decode(file_get_contents($stats_file), true);
			$upload_date = strtotime($json['toylist']) > 0 ? date('YmdHi', strtotime($json['toylist'])) : '';
		}
		$this->upload_date = $upload_date;
	}
	
	/**
	 * Get the Lupo category with new games
	 *
	 * @return array the category
	 */
	public function getCategoryNew() {
		$componentParams = JComponentHelper::getParams('com_lupo');
		$nbr_new_games   = (int) $componentParams->get('nbr_new_games', '30');
		if ($nbr_new_games == 0) {
			$nbr_new_games = 30;
		}

		$db = JFactory::getDBO();

		$db->setQuery("SELECT
			    'new' AS id
			    , '" . JText::_("COM_LUPO_NEW_TOYS") . "' AS title
			    , '' AS description
				, '' AS samples				    
			    , COUNT(#__lupo_game.id) AS number 
			FROM
			    #__lupo_game
			LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
			WHERE #__lupo_game.id IN(SELECT * FROM (SELECT gameid FROM `#__lupo_game_editions` ORDER BY acquired_date DESC LIMIT $nbr_new_games) as temp_table)");

		$res = $db->loadAssocList();

		foreach ($res as &$row) {
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=category&id=' . $row['id']);
		}

		return $res;
	}

	/**
	 * Get the Lupo categories
	 *
	 * @param   show_new override component settings
	 * @param   load_samples bool load sample games to array
	 *
	 * @return array the categories
	 */
	public function getCategories($show_new = true, $load_samples = true) {
		$componentParams = JComponentHelper::getParams('com_lupo');
		$db              = JFactory::getDBO();

		$show_diverse = (int) $componentParams->get('show_diverse', '1');
		$sql_clause   = '';
		if ($show_diverse == 0) {
			$sql_clause = ' AND #__lupo_game.catid > 0';
		}

		if ((int) $componentParams->get('cats_sort', '0') == 0) {
			$sql_sort = '#__lupo_categories.title, #__lupo_categories.sort';
		} else {
			$sql_sort = '#__lupo_categories.sort, #__lupo_categories.title';
		}

		if ($show_new) {
			$res = $this->getCategoryNew();
		}

		$db->setQuery("SELECT
				    #__lupo_categories.id
				    , #__lupo_categories.alias AS alias
				    , #__lupo_categories.title AS title
				    , #__lupo_categories.description AS description
				    , #__lupo_categories.samples AS samples				    
				    , COUNT(#__lupo_game.id) AS number
				FROM
				    #__lupo_game
				    LEFT JOIN #__lupo_categories ON (#__lupo_categories.id = #__lupo_game.catid)
					LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
				WHERE published=1  AND #__lupo_categories.title<>'' $sql_clause
				GROUP BY catid
				HAVING COUNT(#__lupo_game.id) > 0
				ORDER BY $sql_sort");

		if (isset($res) && $res[0]['number'] > 0) {
			$res = array_merge($res, $db->loadAssocList());
		} else {
			$res = $db->loadAssocList();
		}

		$category_foto_list_prefix = $componentParams->get('category_foto_list_prefix', '');

		foreach ($res as &$row) {
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=category&id=' . $row['alias']);

			//add photo to game array
			$category_foto = $this->getCategoryFoto($row['alias'], $category_foto_list_prefix);
			if ($category_foto['image'] != null) {
				$row += $category_foto;
			} else {
				if ($row['samples'] != "") {
					$row += $this->getGameFoto($row['samples'], 'mini_');
				} else {
					$row += ['image' => null, 'image_thumb' => null];
				}
			}

			if ($load_samples) {
				$samples      = explode(",", $row['samples']);
				$sample_games = false;
				if (is_array($samples)) {
					foreach ($samples as $sample_nr) {
						$sample_game = $this->getGamesByNumber($sample_nr);
						if (is_array($sample_game)) {
							$sample_games[] = $sample_game[0];
						}
					}
					$row['sample_games'] = $sample_games;
				}
			}
		}

		return $res;
	}

	/**
	 * Get the Lupo agecategories
	 *
	 * @param   show_new override component settings
	 * @param   load_samples bool load sample games to array
	 *
	 * @return array the agecategories
	 */
	public function getAgecategories($show_new = true, $load_samples = true) {
		$componentParams = JComponentHelper::getParams('com_lupo');
		$db              = JFactory::getDBO();

		$show_without_age = (int) $componentParams->get('show_without_age', '1');
		$sql_clause   = '';
		if ($show_without_age == 0) {
			$sql_clause = ' AND #__lupo_game.age_catid > 0';
		}

		if ($show_new) {
			$res = $this->getCategoryNew();
		}

		$db->setQuery("SELECT
				    #__lupo_agecategories.id
				    , #__lupo_agecategories.alias AS alias
				    , #__lupo_agecategories.title AS title
				    , #__lupo_agecategories.description AS description
				    , #__lupo_agecategories.samples AS samples
				    , COUNT(#__lupo_game.id) AS number
				FROM
				    #__lupo_game
				    LEFT JOIN #__lupo_agecategories ON (#__lupo_agecategories.id = #__lupo_game.age_catid)
					LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
				WHERE published=1 AND #__lupo_agecategories.title<>'' $sql_clause
				GROUP BY age_catid
				HAVING COUNT(#__lupo_game.id) > 0
				ORDER BY #__lupo_agecategories.sort, #__lupo_agecategories.title");

		if (isset($res) && $res[0]['number'] > 0) {
			$res = array_merge($res, $db->loadAssocList());
		} else {
			$res = $db->loadAssocList();
		}

		foreach ($res as &$row) {
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=agecategory&id=' . $row['alias']);

			//add photo to game array
			$category_foto = $this->getCategoryFoto($row['alias']);
			if ($category_foto['image'] != null) {
				$row += $category_foto;
			} else {
				if ($row['samples'] != "") {
					$row += $this->getGameFoto($row['samples'], 'mini_');
				} else {
					$row += ['image' => null, 'image_thumb' => null];
				}
			}

			if ($load_samples) {
				$samples      = explode(",", $row['samples']);
				$sample_games = false;
				if (is_array($samples)) {
					foreach ($samples as $sample_nr) {
						$sample_game = $this->getGamesByNumber($sample_nr);
						if (is_array($sample_game)) {
							$sample_games[] = $sample_game[0];
						}
					}
					$row['sample_games'] = $sample_games;
				}
			}
		}

		return $res;
	}

	/**
	 * Get the Lupo genres
	 *
	 * @return array the genres
	 */
	public function getGenres() {
		$db = JFactory::getDBO();

		$db->setQuery("SELECT
						  #__lupo_genres.id
						  , #__lupo_genres.genre AS title
						  , #__lupo_genres.alias
						  , COUNT(#__lupo_game.id) AS number
						FROM
						  #__lupo_game
						INNER JOIN #__lupo_game_genre ON #__lupo_game.id = #__lupo_game_genre.gameid
						INNER JOIN #__lupo_genres ON #__lupo_game_genre.genreid = #__lupo_genres.id
						GROUP BY #__lupo_genres.id
						ORDER BY genre
						");
		$res = $db->loadAssocList();

		foreach ($res as &$row) {
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=genre&id=' . $row['alias']);
		}

		return $res;
	}

	/**
	 * Get the genre
	 *
	 * @genre genre-alias
	 * @return    array the genre
	 */
	public function getGenre($alias) {
		$db  = JFactory::getDBO();
		$sql = "SELECT * FROM #__lupo_genres WHERE alias=" . $db->quote($alias);
		$db->setQuery($sql);
		$res = $db->loadAssoc();

		return $res;
	}

	/**
	 * Get the category
	 *
	 * @id category-alias
	 * @return    array the category
	 */
	public function getCategory($id) {
		$db = JFactory::getDBO();

		if ($id == 'new') {
			$res = ['id' => 'new', 'description' => '', 'title' => JText::_('COM_LUPO_NEW_TOYS')];
		} else {
			$sql = "SELECT * FROM #__lupo_categories WHERE alias=" . $db->quote($id);
			$db->setQuery($sql);
			$res = $db->loadAssoc();
		}

		return $res;
	}

	/**
	 * Get the agecategory
	 *
	 * @id agecategory-alias
	 * @return    array the agecategory
	 */
	public function getAgecategory($id) {
		$db = JFactory::getDBO();

		if ($id == 'new') {
			$res = ['id' => 'new', 'description' => '', 'title' => JText::_('COM_LUPO_NEW_TOYS')];
		} else {
			$sql = "SELECT * FROM #__lupo_agecategories WHERE alias=" . $db->quote($id);
			$db->setQuery($sql);
			$res = $db->loadAssoc();
		}

		return $res;
	}

	/**
	 * Get the Games in a category
	 *
	 * @id          category-alias
	 * @field       catid or age_catid
	 * @foto_prefix name of the prefix for the image
	 * @return array with the games
	 */
	public function getGames($id = 'all', $field = 'catid', $foto_prefix = '') {
		$componentParams = JComponentHelper::getParams('com_lupo');

		$nbr_new_games = (int) $componentParams->get('nbr_new_games', '30');
		if ($nbr_new_games == 0) {
			$nbr_new_games = 30;
		}

		$db = JFactory::getDBO();

		$order_by = 'title, number'; //default order

		if ($id == 'all') {
			$where	= '';
		} elseif ($id == 'new') {
			// SELECT * FROM (SELECT because MySQL does not support subqueries with LIMIT... but sub-sub query works :o
			$where = "WHERE #__lupo_game.id IN(SELECT * FROM (SELECT gameid FROM `#__lupo_game_editions` ORDER BY acquired_date DESC LIMIT $nbr_new_games) as temp_table)";

			$new_games_sort = (int) $componentParams->get('new_games_sort', '1');
			if ($new_games_sort == '1') {
				$order_by = 'acquired_date DESC, title, number';
			}

		} else {
			$cat_table = ($field == 'catid') ? '#__lupo_categories' : '#__lupo_agecategories';
			$where     = "WHERE " . $field . "=" . "(SELECT id FROM $cat_table WHERE alias=" . $db->quote($id) . " LIMIT 1)";

			$category_list_sort      = (int) $componentParams->get('category_list_sort', '0');
			$app                     = JFactory::getApplication('site');
			$menu_category_list_sort = $app->input->getCmd('category_list_sort', '');

			if ($menu_category_list_sort === '' && $category_list_sort == '1' || $menu_category_list_sort !== '' && $menu_category_list_sort == '1') {
				$order_by = 'acquired_date DESC, title, number';
			}
		}

		$sql = "SELECT
					#__lupo_game.id
					, #__lupo_game.number
					, #__lupo_game.title
					, #__lupo_game.description_title
					, #__lupo_game.description
					, #__lupo_game.catid
					, #__lupo_game.genres
					, #__lupo_game.fabricator
					, #__lupo_game.author
					, #__lupo_game.artist
					, #__lupo_game.play_duration
					, #__lupo_game.players
					, #__lupo_game.keywords
					, #__lupo_game.age_catid
					, #__lupo_game.days
					, #__lupo_game_editions.tax
					, #__lupo_game_editions.acquired_date
					, #__lupo_game_editions.next_reservation
     				, #__lupo_game_editions.next_reservation
					, #__lupo_categories.alias AS category_alias 
					, #__lupo_categories.title as category
					, #__lupo_agecategories.alias AS agecategory_alias 
					, #__lupo_agecategories.title as age_category
					, #__lupo_clients_borrowed.return_date
                    , #__lupo_clients_borrowed.return_extended
     				, #__lupo_clients_borrowed.return_date_extended
                    , t_lupo_reservations_web.reservation_web_created_at
					, t_userdefined.value as userdefined
					, COUNT(#__lupo_game_editions.id) as nbr
				FROM #__lupo_game
				LEFT JOIN #__lupo_categories ON (#__lupo_game.catid = #__lupo_categories.id)
				LEFT JOIN #__lupo_agecategories ON (#__lupo_game.age_catid = #__lupo_agecategories.id)
				LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
				LEFT JOIN #__lupo_clients_borrowed ON (#__lupo_game.number = #__lupo_clients_borrowed.game_number)
				LEFT JOIN (SELECT game_number, MAX(created_at) AS reservation_web_created_at  FROM #__lupo_reservations_web GROUP BY game_number) as t_lupo_reservations_web ON (#__lupo_game.number = t_lupo_reservations_web.game_number)				    
				LEFT JOIN (SELECT gameid, `value` FROM #__lupo_game_documents WHERE type='userdefined') AS t_userdefined ON #__lupo_game.id = t_userdefined.gameid
				%%WHERE%%
				GROUP BY #__lupo_game.id
				ORDER BY " . $order_by;
		$db->setQuery(str_replace('%%WHERE%%', $where, $sql));
		$res = $db->loadAssocList();

		//if no new games were found for the last x days: show all games with the newest aquired date
		if ($id == 'new' && count($res) == 0) {
			//$where = "WHERE #__lupo_game_editions.acquired_date >= (SELECT acquired_date FROM #__lupo_game_editions GROUP BY acquired_date ORDER BY acquired_date DESC LIMIT 3,1)"; //all with 3rd date and newer
			$where = "WHERE #__lupo_game_editions.acquired_date = (SELECT acquired_date FROM #__lupo_game_editions GROUP BY acquired_date ORDER BY acquired_date DESC LIMIT 1)";
			$db->setQuery(str_replace('%%WHERE%%', $where, $sql));
			$res = $db->loadAssocList();
		}

		$res = $this->compileGames($res, $foto_prefix);
		$this->saveSearchResultToSession($res);

		return $res;
	}

	/**
	 * Helper-Function to get the games per category
	 *
	 * @id          cat-id
	 * @foto_prefix name of the prefix for the image*
	 * @return array with the games
	 */
	public function getGamesByCategory($id, $foto_prefix = '') {
		$games = $this->getGames($id, 'catid', $foto_prefix);

		return $games;
	}

	/**
	 * Helper-Function to get the games per agecategory
	 *
	 * @id          agecat-id
	 * @foto_prefix name of the prefix for the image*
	 * @return array with the games
	 */
	public function getGamesByAgeCategory($id, $foto_prefix = '') {
		$games = $this->getGames($id, 'age_catid', $foto_prefix);

		return $games;
	}


	/**
	 * Get the Games per genre
	 *
	 * @genre          genre-alias
	 * @foto_prefix    name of the prefix for the image*
	 * @return array with the games
	 */
	public function getGamesByGenre($genre, $foto_prefix = '') {
		$componentParams = JComponentHelper::getParams('com_lupo');
		$db              = JFactory::getDBO();

		$order_by = 'title, number'; //default order

		$category_list_sort      = (int) $componentParams->get('category_list_sort', '0');
		$app                     = JFactory::getApplication('site');
		$menu_category_list_sort = $app->input->getCmd('category_list_sort', '');

		if ($menu_category_list_sort === '' && $category_list_sort == '1' || $menu_category_list_sort !== '' && $menu_category_list_sort == '1') {
			$order_by = 'acquired_date DESC, title, number';
		}

		$db->setQuery("SELECT
							#__lupo_game.id
							, #__lupo_game.number
							, #__lupo_game.title
							, #__lupo_game.description_title
							, #__lupo_game.description
							, #__lupo_game.catid
							, #__lupo_game.genres
							, #__lupo_game.fabricator
							, #__lupo_game.play_duration
							, #__lupo_game.players
							, #__lupo_game.keywords
							, #__lupo_game.age_catid
							, #__lupo_game.days
							, #__lupo_game_editions.tax
							, #__lupo_game_editions.acquired_date
							, #__lupo_game_editions.next_reservation
							, #__lupo_categories.alias AS category_alias 
							, #__lupo_categories.title AS category
							, #__lupo_agecategories.alias AS agecategory_alias 
							, #__lupo_agecategories.title AS age_category
     						, t_lupo_reservations_web.reservation_web_created_at 
							, #__lupo_clients_borrowed.return_date
  							, #__lupo_clients_borrowed.return_extended
  							, #__lupo_clients_borrowed.return_date_extended
							, t_userdefined.value AS userdefined
							, COUNT(#__lupo_game_editions.id) AS nbr
						FROM #__lupo_game
						LEFT JOIN #__lupo_categories ON (#__lupo_game.catid = #__lupo_categories.id)
						LEFT JOIN #__lupo_agecategories ON (#__lupo_game.age_catid = #__lupo_agecategories.id)
						LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
						LEFT JOIN #__lupo_clients_borrowed ON (#__lupo_game.number = #__lupo_clients_borrowed.game_number)
						LEFT JOIN (SELECT game_number, MAX(created_at) AS reservation_web_created_at  FROM #__lupo_reservations_web GROUP BY game_number) as t_lupo_reservations_web ON (#__lupo_game.number = t_lupo_reservations_web.game_number)
						LEFT JOIN (SELECT gameid, `value` FROM #__lupo_game_documents WHERE type='userdefined') AS t_userdefined ON #__lupo_game.id = t_userdefined.gameid
						INNER JOIN #__lupo_game_genre ON (#__lupo_game.id = #__lupo_game_genre.gameid)
						LEFT JOIN #__lupo_genres ON (#__lupo_game_genre.genreid = #__lupo_genres.id)
						WHERE #__lupo_genres.alias=" . $db->quote($genre) . "
						GROUP BY #__lupo_game.id
						ORDER BY $order_by");
		$res = $db->loadAssocList();

		$res = $this->compileGames($res, $foto_prefix);
		$this->saveSearchResultToSession($res);

		return $res;
	}


	/**
	 * Get the Games by game number
	 *
	 * @number      public game number, multiple numbers seperated by ;
	 * @foto_prefix name of the prefix for the image*
	 * @return array with the game(s)
	 */
	public function getGamesByNumber($number, $foto_prefix = '') {
		$numbers = explode(";", $number);
		$db      = JFactory::getDBO();
		$games   = false;

		foreach ($numbers as $number) {
			$number = (strpos($number, '.') == 0 ? $number . '.0' : $number);
			$db->setQuery("SELECT
                            #__lupo_game.number
                        FROM
                            #__lupo_game
                        LEFT JOIN `#__lupo_game_editions` ON `#__lupo_game`.id = `#__lupo_game_editions`.`gameid`
                        WHERE CONCAT(`number`, IF(INSTR(`number`, '.') = 0, CONCAT('.', `index`), '')) = " . $db->quote($db->escape(trim($number))));
			$res = $db->loadAssoc();

			if ($res !== null) {
				$games[] = $this->getGame($res['number']);
			}
		}

		return $games;
	}


	/**
	 * Get a game
	 *
	 * @id           game-number
	 * @load_related bool
	 *
	 * @return array the game
	 */
	public function getGame($id, $load_related = false) {
		$db = JFactory::getDBO();
		$db->setQuery("SELECT 
					    #__lupo_game.*
                        , #__lupo_game_editions.*
					    , #__lupo_categories.alias AS category_alias 
					    , #__lupo_categories.title AS category 
					    , #__lupo_agecategories.alias AS agecategory_alias
					    , #__lupo_agecategories.title AS age_category
					    , t_userdefined.value AS userdefined
     					, t_lupo_reservations_web.reservation_web_created_at
						, #__lupo_clients_borrowed.return_date
                        , #__lupo_clients_borrowed.return_extended
                        , #__lupo_clients_borrowed.return_date_extended
					FROM
					    #__lupo_game 
						LEFT JOIN #__lupo_categories ON (#__lupo_categories.id = #__lupo_game.catid) 
						LEFT JOIN #__lupo_agecategories ON (#__lupo_agecategories.id = #__lupo_game.age_catid)
						LEFT JOIN (SELECT gameid, `value` FROM #__lupo_game_documents WHERE type='userdefined') AS t_userdefined ON #__lupo_game.id = t_userdefined.gameid
						LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
						LEFT JOIN #__lupo_clients_borrowed ON (#__lupo_game.number = #__lupo_clients_borrowed.game_number)
						LEFT JOIN (SELECT game_number, MAX(created_at) AS reservation_web_created_at  FROM #__lupo_reservations_web GROUP BY game_number) as t_lupo_reservations_web ON (#__lupo_game.number = t_lupo_reservations_web.game_number)
					WHERE #__lupo_game.number = " . $db->quote($id));
		$res = $db->loadAssoc();

		if ($res == 0) {
			return 'error';
		}

		//nötig hier? unterhalb via compileGame  nochmals dasselbe!!
		//$res += $this->getLoanStatus($res);

		//load genres
		$db->setQuery("SELECT
                        #__lupo_genres.id
					    , #__lupo_genres.genre
					    , #__lupo_genres.alias
					FROM
					    #__lupo_game_genre
                    LEFT JOIN #__lupo_genres ON #__lupo_genres.id = genreid
					WHERE gameid = (SELECT id FROM #__lupo_game WHERE number=" . $db->quote($id) . " LIMIT 1)");
		$res['genres_list'] = $db->loadAssocList();
		foreach ($res['genres_list'] as &$genre) {
			$genre['link'] = JRoute::_('index.php?option=com_lupo&view=genre&id=' . $genre['alias']);;
		}

		//Load documents
		$db->setQuery("SELECT
					    *
					FROM
					    #__lupo_game_documents
					WHERE gameid = (SELECT id FROM #__lupo_game WHERE number=" . $db->quote($id) . " LIMIT 1)");
		$res['documents'] = $db->loadAssocList();

		//parse document fields
		foreach ($res['documents'] as &$document) {
			switch ($document['code']) {
				case 'youtube':
					$document['href'] = 'https://www.youtube.com/watch?v=' . $document['value'];
					$document['icon'] = 'youtube-play';
					$desc             = 'YouTube';
					$lightbox         = true;
					break;
				case 'vimeo':
					$document['href'] = 'http://vimeo.com/' . $document['value'];
					$document['icon'] = 'vimeo-square';
					$desc             = 'Vimeo';
					$lightbox         = true;
					break;
				case 'facebook':
					$document['href'] = $document['value'];
					$document['icon'] = 'facebook-square';
					$desc             = 'Facebook';
					$lightbox         = false;
					break;
				case 'wikipedia':
					$document['href'] = $document['value'];
					$document['icon'] = 'wikipedia-w';
					$desc             = 'Wikipedia';
					$lightbox         = false;
					break;
				case 'link_manual':
					$document['href'] = $document['value'];
					if (strpos($document['value'], "youtube.com") !== false || strpos($document['value'], "//youtu.be") !== false) {
						$document['icon'] = 'youtube-play';
					} elseif (strpos($document['value'], "vimeo.com") !== false) {
						$document['icon'] = 'vimeo-square';
					} else {
						$document['icon'] = 'file-pdf-o';
					}
					$desc     = 'Spielanleitung';
					$lightbox = false;
					break;
				case 'userdefined':
					break;
				case 'link_review':
				case 'website':
				default:
					$document['href'] = $document['value'];
					$document['icon'] = 'external-link';
					$desc             = 'Link';
					$lightbox         = false;
					break;
			}
			if ($document['desc'] == "") {
				$document['desc'] = $desc;
			}
			if ($lightbox) {
				$document['lightbox'] = "data-uk-lightbox=\"{group:'grp-docs'}\"";
			} else {
				$document['lightbox'] = 'target="_blank"';
			}
		}


		//related games
		if ($load_related) {
			$db->setQuery("SELECT
                          r.number
                          , g.id
                          , g.number
                          , g.title
                          , g.description_title
                          , g.description
                          , g.edition
                          , g.catid
                          , '' AS category_alias
                          , g.age_catid
                          , '' AS agecategory_alias						
                          , NULL AS return_date
                          , NULL AS return_extended
                          , NULL AS return_date_extended
                          , NULL AS next_reservation
                          , NULL AS reservation_web_created_at
                        FROM
                          `#__lupo_game_related` AS r
                          LEFT JOIN
                          (
                            SELECT
                              CONCAT(`number`, IF(INSTR(`number`, '.') = 0, CONCAT('.', `index`), '')) AS gameno
                              , #__lupo_game.id
                              , number
                              , catid
                              , age_catid
                              , title
                              , description_title
                              , description
                              , edition
                            FROM
                              `#__lupo_game`
                              LEFT JOIN `#__lupo_game_editions` ON `#__lupo_game`.id = `#__lupo_game_editions`.`gameid`
                          ) AS g ON g.gameno = CONCAT(r.`number` , IF(INSTR(r.number, '.')=0,'.0',''))
                        WHERE r.gameid = (SELECT id FROM #__lupo_game WHERE number=" . $db->quote($id) . " LIMIT 1)
                        ORDER BY r.id");

			$res['related'] = $db->loadAssocList();
			shuffle($res['related']); //mischen damit nicht immer das spiel mit den meisten ausleihen zuerst kommt

			foreach ($res['related'] as &$relatedgame) {
				$relatedgame = $this->compileGame($relatedgame, 'mini_');
			}
		} else {
			$res['related'] = null;
		}

		$res = $this->compileGame($res, '');

		return $res;
	}


	/**
	 * check games array if one or more foto exists
	 *
	 * @param   array games
	 *
	 * @return boolean true if one or more fotos exists
	 */
	public function hasFoto($games) {
		$hasOneFotoOrMore = false;
		foreach ($games as $key => $row) {
			if ($row['image'] !== null) {
				$hasOneFotoOrMore = true;
			}
		}

		return $hasOneFotoOrMore;
	}


	/**
	 * complete games array
	 *
	 * @param   array games
	 *
	 * @return array completed games
	 */
	public function compileGames($games, $foto_prefix) {
		$pos = 0;
		foreach ($games as $key => &$row) {
			$row += $this->compileGame($row, $foto_prefix, $pos);
			$pos++;
		}

		return $games;
	}


	/**
	 * complete game array
	 *
	 * @param   array  game
	 * @param   string thumb-prefix
	 * @param   string pos
	 *
	 * @return array game
	 */
	public function compileGame($row, $game_thumb_prefix, $pos = '') {
		//add photo to game array
		$row += $this->getGameFoto($row['number'], $game_thumb_prefix);
		$row += $this->getLoanStatus($row);

		//description-text
		if ($row['description_title'] != "") {
			$row['description_full'] = '<b>' . $row['description_title'] . '</b><br>' . $row['description'];
		} else {
			$row['description_full'] = $row['description'];
		}

		if ($pos !== '') {
			$pos = '&pos=' . $pos;
		}
		//Attention: For SEO id exchanged with number, but get-field is still named with id
		$row['link']        = JRoute::_('index.php?option=com_lupo&view=game&id=' . $row['number'] . $pos);
		$row['link_cat']    = JRoute::_('index.php?option=com_lupo&view=category&id=' . $row['category_alias']);
		$row['link_agecat'] = JRoute::_('index.php?option=com_lupo&view=agecategory&id=' . $row['agecategory_alias']);

		//check if toy is in reservation-cart
		$app     = JFactory::getApplication('site');
		$session = $app->getSession();

		$reservations   = $session->get('lupo_reservations');
		$row['in_cart'] = $reservations != null && array_key_exists($row['number'], $reservations);

		return $row;
	}


	/**
	 * add loan status / availability to game array
	 *
	 * @param   array  game*
	 *
	 * @return array loan status
	 */
	public function getLoanStatus($row) {
		$componentParams = JComponentHelper::getParams('com_lupo');

		$days_show_reservation     = '+35 day'; //do not show as reserved if to far in the future
		$days_show_reservation_web = '-14 day'; //max age to show res from cache table

		if ($row['return_date'] != null) {
			$availability['availability_color'] = 'red';
			if ($row['return_extended'] == 1) {
				$return_date = $row['return_date_extended'];
			} else {
				$return_date = $row['return_date'];
			}

			if ($componentParams->get('lupo_show_toystatus_date', '1')) {
				$availability['availability_text'] = sprintf(JText::_("COM_LUPO_BORROWED_TO"), date("d.m.Y", strtotime($return_date)));
			} else {
				$availability['availability_text'] = JText::_("COM_LUPO_BORROWED");
			}

			if ($row['next_reservation'] != null && $row['next_reservation'] < date("Y-m-d", strtotime($days_show_reservation, time()))) {
				$availability['availability_text'] .= ' / ' . JText::_("COM_LUPO_RESERVED_FROM") . ' ' . date("d.m.Y", strtotime($row['next_reservation']));
			}
		} elseif ($row['next_reservation'] != null && $row['next_reservation'] < date("Y-m-d", strtotime($days_show_reservation, time()))) {
			$availability['availability_color'] = 'orange';
			$availability['availability_text']  = JText::_("COM_LUPO_RESERVED_FROM") . ' ' . date("d.m.Y", strtotime($row['next_reservation']));
		} elseif ($row['reservation_web_created_at'] != null && $row['reservation_web_created_at'] > date("Y-m-d", strtotime($days_show_reservation_web, time()))) {
			$availability['availability_color'] = 'orange';
			$availability['availability_text']  = JText::_("COM_LUPO_RESERVED");
		} else {
			$availability['availability_color'] = 'green';
			$availability['availability_text']  = JText::_("COM_LUPO_AVAILABLE");
		}

		return $availability;
	}


	/**
	 * Get the picture of a game
	 *
	 * to avoid showing the image from the browser cache, we can add a version number to the image url
	 *
	 * @param   number game-nbr
	 * @param   prefix of the thumb
	 *
	 * @return array foto
	 *
	 * @todo: refactor thumbnail-logic
	 */
	public function getGameFoto($number, $game_thumb_prefix = "") {

		$get_var_uploaddate = '?v=' . $this->upload_date;

		$game_image = 'images/spiele/' . $number . '.jpg';
		if (file_exists($game_image)) {
			$res['image'] = $game_image;
		} else {
			//try to get file without index in name
			$game_image = 'images/spiele/' . (int) $number . '.jpg';
			if (file_exists($game_image)) {
				$res['image'] = $game_image;
			} else {
				$res['image'] = null;
			}
		}

		$game_image_thumb = 'images/spiele/' . $game_thumb_prefix . $number . '.jpg';
		if (file_exists($game_image_thumb)) {
			$res['image_thumb'] = $game_image_thumb;
		} else {
			//try to get file without index in name
			$game_image = 'images/spiele/' . $game_thumb_prefix . (int) $number . '.jpg';
			if (file_exists($game_image)) {
				$res['image_thumb'] = $game_image;
			} else {
				$res['image_thumb'] = null;
			}
		}

		if ($res['image']) {
			$res['image'] .= $get_var_uploaddate;
		}
		
		if($res['image_thumb']) {
			$res['image_thumb'] .= $get_var_uploaddate;
		} 

		return $res;
	}


/**
	 * Get static picture of the category / agecategory
	 *
	 * @param   category_alias
	 *
	 * @return array foto
	 *
	 */
	public function getCategoryFoto($category_alias = "", $thumb_prefix = "mini_") {
		$image = 'images/spiele/' . $thumb_prefix . $category_alias . '.jpg';
		if (file_exists($image)) {
			$res['image'] = $image;
		} else {
			$res['image'] = null;
		}

		$res['image_thumb'] = $res['image'];

		return $res;
	}


	/**
	 * Get the menu itemid of a game by category
	 *
	 * @param   number gameid
	 *
	 * @return number itemid
	 */
	public function getCategoryItemId($gameid) {
		$db = JFactory::getDBO();

		$db->setQuery("SELECT catid FROM #__lupo_game WHERE id = " . $db->quote($gameid));
		$row = $db->loadRow();

		if (is_array($row) && count($row) > 0) {
			$db->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_lupo&view=category&id=" . $row[0] . "'  AND published=1");
			$row = $db->loadRow();
		}

		if (is_array($row) && count($row) > 0) {
			return $row[0];
		} else {
			return false;
		}
	}


	/**
	 * Save search-result to session
	 *
	 * @param   array games-object
	 *
	 * @return no return
	 */
	public function saveSearchResultToSession($res) {
		$games = [];
		foreach ($res as $row) {
			$games[]['id'] = $row['number'];
		}

		$session = JFactory::getSession();
		$session->set('lupo', $games);
	}


	/**
	 * Returns Number of toys
	 *
	 * @return int number of toys
	 */
	public function totalToys() {
		$db = JFactory::getDBO();

		$db->setQuery("SELECT COUNT(id) AS total FROM #__lupo_game_editions");
		$row = $db->loadResult();

		return $row;
	}

	/**
	 * Gets subsets array for dynamic filter
	 * "Players"-Attribute currently not supported for dynamic filters
	 *
	 * @param   array  $types
	 * @param   array  $games
	 *
	 * @return array filter-def
	 */
	public function getSubsets($types, $games) {
		if (count($games) <= 1 || count($types) == 0) {
			return ['style' => 'dropdown', 'filters' => []];
		}

		//read all available age/cats/genres in games
		$available_genres = [];
		foreach ($games as $game) {
			$available_categories[$game['category_alias']]       = $game['category_alias'];
			$available_agecategories[$game['agecategory_alias']] = $game['agecategory_alias'];

			$genres = explode(',', $game['genres']);
			foreach ($genres as $genre) {
				if ($genre !== "") {
					$available_genres[$genre] = $genre;
				}
			}
		}
		asort($available_genres);

		$agecategories = [];
		$categories    = [];
		$genres        = [];
		$filters       = false;
		foreach ($types as $type) {
			switch ($type) {
				case 'agecategory':
					$agecategories = $this->getAgecategories(false, false);
					break;
				case 'category':
					$categories = $this->getCategories(false, false);
					break;
				case 'genre':
					$genres = $this->getGenres();
					break;
			}
		}

		foreach ($categories as $category) {
			if (array_key_exists($category['alias'], $available_categories)) {
				$filters[$category['title']] = ['categories' => [$category['alias']], 'agecategories' => [], 'genres' => []];
			}
		}
		foreach ($agecategories as $agecategory) {
			if (array_key_exists($agecategory['alias'], $available_agecategories)) {
				$filters[$agecategory['title']] = ['categories' => [], 'agecategories' => [$agecategory['alias']], 'genres' => []];
			}
		}
		foreach ($genres as $genre) {
			if (array_key_exists($genre['alias'], $available_genres)) {
				$filters[$genre['title']] = ['categories' => [], 'agecategories' => [], 'genres' => [$genre['alias']]];
			}
		}

		$subsets = ['style' => 'dropdown', 'filters' => $filters];

		return $subsets;
	}


	/**
	 * stores to reservation cache table
	 * reservations remain in this table until its added to lupo
	 *
	 * @since 3.74.0
	 */

	public function storeReservation($adrnr, $game_number) {
		$tuple              = new stdClass;
		$tuple->game_number = $game_number;
		$tuple->adrnr       = $adrnr;

		$db = JFactory::getDBO();
		$db->insertObject('#__lupo_reservations_web', $tuple);
	}

}


class LupoModelLupoClient extends LupoModelLupo {

	/**
	 * do client login and save values in session
	 *
	 * @param $adrnr
	 * @param $password
	 *
	 * @return bool true if login successful
	 */

	public function clientLogin($adrnr, $password) {
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__lupo_clients.*')
			->from('#__lupo_clients')
			->where('#__lupo_clients.adrnr = ' . $db->quote($adrnr) . ' AND #__lupo_clients.username = ' . $db->quote($password));
		$db->setQuery($query);
		$row = $db->loadObject();

		if ($row) {
			$session = JFactory::getSession();
			$session->set('lupo_client', $row);

			return true;
		} else {
			return false;
		}
	}

	/**
	 * kill client session vars
	 *
	 * @return void
	 */
	public function clientLogout() {
		$session = JFactory::getSession();
		$session->clear('lupo_client');
	}


	public function getClientToys($adrnr) {
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__lupo_clients_borrowed')
			->join('LEFT', '#__lupo_game ON #__lupo_clients_borrowed.game_number = #__lupo_game.number')
			->where('#__lupo_clients_borrowed.adrnr = ' . $db->quote($adrnr))
			->where('#__lupo_clients_borrowed.quarantine = 0')
			->order('return_date, title');
		$db->setQuery($query);
		$res = $db->loadObjectList();

		foreach ($res as &$row) {
			$row->link = JRoute::_('index.php?option=com_lupo&view=game&id=' . $row->number);
		}

		return $res;
	}


	public function getClient($email) {
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__lupo_clients')
			->where('email = ' . $db->quote($email));
		$db->setQuery($query);
		$res = $db->loadObject();

		return $res;
	}

}
