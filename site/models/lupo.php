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

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * Lupo Model
 */
class LupoModelLupo extends JModelItem {
	/**
	 * @var object item
	 */
	protected $item;


	/**
	 * Get the Lupo category with new games
	 *
	 * @return array the category
	 */
	public function getCategoryNew() {
		$componentParams = &JComponentHelper::getParams('com_lupo');
		$new_games_age = (int)$componentParams->get('new_games_age', '90'); 
		if($new_games_age==0){
			$new_games_age=90;
		}

		$db =& JFactory::getDBO();

		$db->setQuery("SELECT
			    'new' AS id
			    , '".JText::_("COM_LUPO_NEW_TOYS")."' AS title
			    , COUNT(#__lupo_game.id) AS number 
			FROM
			    #__lupo_game
			LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
			WHERE #__lupo_game_editions.acquired_date > DATE_ADD(DATE(NOW()),INTERVAL -$new_games_age DAY)");

		$res=$db->loadAssocList();

		foreach($res as &$row){
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=category&id='.$row['id']);
		}

		return $res;
	}

	/**
	 * Get the Lupo categories
	 *
	 * @param show_new override component settings
	 * @return array the categories
	 */
	public function getCategories($show_new=true) {
		$componentParams = &JComponentHelper::getParams('com_lupo');
		$db =& JFactory::getDBO();

		$show_diverse = (int)$componentParams->get('show_diverse', '1');
		$sql_clause='';
		if($show_diverse==0){
			$sql_clause = ' AND #__lupo_game.catid > 0';
		}

		if($show_new) {
			$res = $this->getCategoryNew();
		}

		$db->setQuery("SELECT
				    #__lupo_categories.id
				    , #__lupo_categories.title AS title
				    , COUNT(#__lupo_game.id) AS number
				FROM
				    #__lupo_game
				    LEFT JOIN #__lupo_categories ON (#__lupo_categories.id = #__lupo_game.catid)
					LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
				WHERE published=1  AND #__lupo_categories.title<>'' $sql_clause
				GROUP BY catid
				HAVING COUNT(#__lupo_game.id) > 0
				ORDER BY #__lupo_categories.sort, #__lupo_categories.title");

		if(is_array($res) && $res[0]['number']>0){
			$res = array_merge($res,$db->loadAssocList());
		} else {
			$res=$db->loadAssocList();
		}

		foreach($res as &$row){
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=category&id='.$row['id']);
		}

		return $res;
	}

	/**
	 * Get the Lupo agecategories
	 *
	 * @param show_new override component settings
	 * @return array the agecategories
	 */
	public function getAgecategories($show_new=true) {
		$componentParams = &JComponentHelper::getParams('com_lupo');
		$db =& JFactory::getDBO();

		$show_diverse = (int)$componentParams->get('show_diverse', '1');
		$sql_clause='';
		if($show_diverse==0){
			$sql_clause = ' AND #__lupo_game.catid > 0';
		}

		if($show_new) {
			$res = $this->getCategoryNew();
		}

		$db->setQuery("SELECT
				    #__lupo_agecategories.id
				    , #__lupo_agecategories.title AS title
				    , COUNT(#__lupo_game.id) AS number
				FROM
				    #__lupo_game
				    LEFT JOIN #__lupo_agecategories ON (#__lupo_agecategories.id = #__lupo_game.age_catid)
					LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
				WHERE published=1 AND #__lupo_agecategories.title<>'' $sql_clause
				GROUP BY age_catid
				HAVING COUNT(#__lupo_game.id) > 0
				ORDER BY #__lupo_agecategories.sort, #__lupo_agecategories.title");

		if(is_array($res) && $res[0]['number']>0){
			$res = array_merge($res,$db->loadAssocList());
		} else {
			$res=$db->loadAssocList();
		}

		foreach($res as &$row){
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=agecategory&id='.$row['id']);
		}

		return $res;
	}
	/**
	 * Get the Lupo genres
	 *
	 * @return array the genres
	 */
	public function getGenres() {

		$db =& JFactory::getDBO();

		$db->setQuery("SELECT
						  #__lupo_genres.id
						  , #__lupo_genres.genre AS title
						  , COUNT(#__lupo_game.id) AS number
						FROM
						  #__lupo_game
						INNER JOIN #__lupo_game_genre ON #__lupo_game.id = #__lupo_game_genre.gameid
						INNER JOIN #__lupo_genres ON #__lupo_game_genre.genreid = #__lupo_genres.id
						GROUP BY #__lupo_genres.id
						");

		$res=$db->loadAssocList();

		foreach($res as &$row){
			$row['link'] = JRoute::_('index.php?option=com_lupo&view=genre&id='.$row['id']);
		}

		return $res;
	}

	/**
	 * Get the genre
	 *
	 * @id genre-id
	 * @return	array the genre
	 */
	public function getGenre($id) {
		$db =& JFactory::getDBO();
		$sql = "SELECT * FROM #__lupo_genres WHERE id=" .$db->quote($id);
		$db->setQuery($sql);
		$res = $db->loadAssoc();
		return $res;
	}	
	
	/**
	 * Get the category
	 *
	 * @id category-id
	 * @return	array the category
	 */
	public function getCategory($id) {
		$db =& JFactory::getDBO();

		if($id=='new'){
			$res = array('id'=>'new','title'=>JText::_('COM_LUPO_NEW_TOYS'));
		} else {
			$sql = "SELECT * FROM #__lupo_categories WHERE id=" .$db->quote($id);
			$db->setQuery($sql);
			$res = $db->loadAssoc();
		}
		return $res;
	}

	/**
	 * Get the agecategory
	 *
	 * @id agecategory-id
	 * @return	array the agecategory
	 */
	public function getAgecategory($id) {
		$db =& JFactory::getDBO();

		if($id=='new'){
			$res = array('id'=>'new','title'=>JText::_('COM_LUPO_NEW_TOYS'));
		} else {
			$sql = "SELECT * FROM #__lupo_agecategories WHERE id=" .$db->quote($id);
			$db->setQuery($sql);
			$res = $db->loadAssoc();
		}
		return $res;
	}

	/**
	 * Get the Games in a category
	 *
	 * @id category-id
	 * @field catid or age_catid
	 * @foto_prefix name of the prefix for the image
	 * @return array with the games
	 */
	public function getGames($id, $field = 'catid', $foto_prefix = '') {
		$componentParams = &JComponentHelper::getParams('com_lupo');
		$new_games_age = (int)$componentParams->get('new_games_age', '90'); 
		if($new_games_age==0){
			$new_games_age=90;
		}

		$db =& JFactory::getDBO();

		if($id=='new'){
			$where = "WHERE #__lupo_game_editions.acquired_date > DATE_ADD(DATE(NOW()),INTERVAL -$new_games_age DAY)";
		} else {
			$where = "WHERE ".$field."=" .$db->quote($id);
		}

		$sql = "SELECT
					#__lupo_game.id
					, #__lupo_game.number
					, #__lupo_game.title
					, #__lupo_game.description
					, #__lupo_game.catid
					, #__lupo_categories.title as category
					, #__lupo_game.age_catid
					, #__lupo_agecategories.title as age_category
					, #__lupo_game.days
					, #__lupo_game_editions.tax
					, COUNT(#__lupo_game_editions.id) as nbr
				FROM #__lupo_game
				LEFT JOIN #__lupo_categories ON (#__lupo_game.catid = #__lupo_categories.id)
				LEFT JOIN #__lupo_agecategories ON (#__lupo_game.age_catid = #__lupo_agecategories.id)
				LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
				%%WHERE%%
				GROUP BY #__lupo_game.id
				ORDER BY title, number";
		$db->setQuery(str_replace('%%WHERE%%',$where,$sql));
		$res = $db->loadAssocList();

		//if no new games were found for the last x days: show all games up from 3rd newest aquired date
		if ($id=='new' && count($res)==0){
			$where = "WHERE #__lupo_game_editions.acquired_date = (SELECT acquired_date FROM #__lupo_game_editions GROUP BY acquired_date ORDER BY acquired_date DESC LIMIT 3,1)";
			$db->setQuery(str_replace('%%WHERE%%',$where,$sql));
			$res = $db->loadAssocList();
		}

		$pos=0;
		foreach($res as $key => &$row){
			$row += $this->compileGame($row, $foto_prefix, $pos);
			$pos++;
		}

		$session = JFactory::getSession();
		$session->set('lupo', $res);
		
		return $res;
	}	


	/**
	 * Get the Games per genre
	 *
	 * @id genre-id
	 * @foto_prefix name of the prefix for the image*
	 * @return array with the games
	 */
	public function getGamesByGenre($id, $foto_prefix = '') {

		$db =& JFactory::getDBO();
		$db->setQuery("SELECT
							#__lupo_game.id
							, #__lupo_game.number
							, #__lupo_game.title
							, #__lupo_game.description
							, #__lupo_game.catid
							, #__lupo_categories.title as category
							, #__lupo_game.age_catid
							, #__lupo_agecategories.title as age_category
							, #__lupo_game.days
							, #__lupo_game_editions.tax
							, COUNT(#__lupo_game_editions.id) as nbr
						FROM #__lupo_game
						LEFT JOIN #__lupo_categories ON (#__lupo_game.catid = #__lupo_categories.id)
						LEFT JOIN #__lupo_agecategories ON (#__lupo_game.age_catid = #__lupo_agecategories.id)
						LEFT JOIN #__lupo_game_editions ON (#__lupo_game.id = #__lupo_game_editions.gameid)
						INNER JOIN #__lupo_game_genre ON (#__lupo_game.id = #__lupo_game_genre.gameid)
						WHERE #__lupo_game_genre.genreid=".$db->quote($id)."
						GROUP BY #__lupo_game.id
						ORDER BY title, number");
		$res = $db->loadAssocList();

		$pos=0;
		foreach($res as $key => &$row){
			$row += $this->compileGame($row, $foto_prefix, $pos);
			$pos++;
		}

		$session = JFactory::getSession();
		$session->set('lupo', $res);

		return $res;
	}


	/**
	 * Get a game 
	 *
	 * @id game-id
	 * @return array the game
	 */
	public function getGame($id) {
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT 
					    #__lupo_game.*
					    , #__lupo_categories.title AS category 
					    , #__lupo_agecategories.title AS age_category 
					FROM
					    #__lupo_game 
						LEFT JOIN #__lupo_categories ON (#__lupo_categories.id = #__lupo_game.catid) 
						LEFT JOIN #__lupo_agecategories ON (#__lupo_agecategories.id = #__lupo_game.age_catid) 
					WHERE #__lupo_game.id = " .$id);
		$res = $db->loadAssoc();
		
		if($res==0){
			return 'error';
		}

		//Load documents
		$db->setQuery("SELECT
					    *
					FROM
					    #__lupo_game_documents
					WHERE gameid = " .$id);
		$res['documents'] = $db->loadAssocList();

		//Load game editions
		$db->setQuery("SELECT 
					    *
					FROM
					    #__lupo_game_editions
					WHERE gameid = " .$id);
		$res['editions'] = $db->loadAssocList();

		//Find min/max tax
		$min = 9999;
		foreach($res['editions'] as $arr) {
			if($arr['tax'] < $min) {
				$res['tax_min'] = $min = $arr['tax'];
			}
		}
		$max = 0;
		foreach($res['editions'] as $arr) {
			if($arr['tax'] > $max) {
				$res['tax_max'] = $max = $arr['tax'];
			}
		}

		$componentParams = &JComponentHelper::getParams('com_lupo');
		$game_thumb_prefix = $componentParams->get('game_thumb_prefix', 'thumb_');

		$res = $this->compileGame($res, $game_thumb_prefix);

		return $res;
	}

	/**
	 * complete game array
	 *
	 * @param array game
	 * @param string thumb-prefix
	 * @param string pos
	 * @return array game
	 */
	public function compileGame($row, $game_thumb_prefix, $pos = '') {
		//add foto to game array
		$row += $this->getGameFoto($row['number'], $game_thumb_prefix);

		if($pos!==''){
			$pos = '&pos='.$pos;
		}

		$row['link'] = 		  JRoute::_('index.php?option=com_lupo&view=game&id='.$row['id'].$pos);
		$row['link_cat'] =    JRoute::_('index.php?option=com_lupo&view=category&id='.$row['catid']);
		$row['link_agecat'] = JRoute::_('index.php?option=com_lupo&view=agecategory&id='.$row['age_catid']);

		return $row;
	}



	/**
	 * Get the picture of a game
	 *
	 * @param number game-nbr
	 * @param prefix of the thumb
	 * @return array foto
	 */
	public function getGameFoto($number, $game_thumb_prefix="") {
		$game_image = 'images/spiele/'.$number.'.jpg';
		if(file_exists($game_image)){
			$res['image']=$game_image;
		} else {
			$res['image']=null;
		}

		if($game_thumb_prefix!="") {
			$game_image_thumb = 'images/spiele/' . $game_thumb_prefix . $number . '.jpg';
			if (file_exists($game_image_thumb)) {
				$res['image_thumb'] = $game_image_thumb;
			} else {
				$res['image_thumb'] = null;
			}
		}

		return $res;
	}

}
