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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_lupo'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Set some global property
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-generic:before {content: "" }'); //remove icon
//$document->addStyleDeclaration('.strong {font-weight: bold;}');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Lupo
$controller = JControllerLegacy::getInstance('Lupo');

// Perform the Request task
$controller->execute($app->input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();


set_time_limit ( 300 ); //5 Min

$xmlfile = 'lupospiele.xml';
$xmlpath = 'components/com_lupo/xml_upload/';
$gamespath = '../images/spiele/';



if(isset($_FILES['xmlfile'])){
	$zip = new ZipArchive;
	if($_FILES['xmlfile']['tmp_name']==''){
		JFactory::getApplication()->enqueueMessage( JText::_( "Keine Datei ausgewählt!" ), 'error' );
	} else {
		$res = $zip->open($_FILES['xmlfile']['tmp_name']);
		if ($res === TRUE) {
			$zip->extractTo($xmlpath, array($xmlfile)); //extract xml
			$zip->extractTo($gamespath); //extract full archive
			$zip->close();

			unlink($gamespath . $xmlfile); //remove xmlfile from images folder

			processXML($xmlpath .$xmlfile);

		} else {
			JFactory::getApplication()->enqueueMessage( JText::_( "Konnte hochgeladene Datei nicht entpacken! zip-Datei erwartet." ), 'error' );
		}
	}
}


if(isset($_POST['act']) && $_POST['act']=='processxml'){
	processXML($xmlpath .$xmlfile);
}


function processXML($file){
	if (file_exists($file)) {
		$xml = simplexml_load_file($file);
		if($xml==false){
			JFactory::getApplication()->enqueueMessage( JText::_( "Fehler in XML Definition" ), 'error' );
		} else {
			$db =& JFactory::getDBO();

			$db->setQuery('TRUNCATE #__lupo_game');
			$db->execute();

			$db->setQuery('TRUNCATE #__lupo_game_editions');
			$db->execute();

			$db->setQuery('TRUNCATE #__lupo_game_documents');
			$db->execute();

			$db->setQuery('TRUNCATE #__lupo_categories');
			$db->execute();

			$db->setQuery('TRUNCATE #__lupo_agecategories');
			$db->execute();

			$db->setQuery('TRUNCATE #__lupo_genres');
			$db->execute();

			$db->setQuery('TRUNCATE #__lupo_game_genre');
			$db->execute();

			$db->setQuery('TRUNCATE #__lupo_game_related');
			$db->execute();

			foreach($xml->categories->category as $category){
				$db->setQuery('INSERT INTO #__lupo_categories SET
										id='.$db->quote($category['id']).'
										, title='.$db->quote($category['desc']).'
										, alias='.$db->quote(JApplication::stringURLSafe($category['desc'])).'
										, sort='.$db->quote($category['sort']));
				$db->execute();
			}

			foreach($xml->age_categories->category as $category){
				$db->setQuery('INSERT INTO #__lupo_agecategories SET
										id='.$db->quote($category['id']).'
										, title='.$db->quote($category['desc']).'
										, alias='.$db->quote(JApplication::stringURLSafe($category['desc'])).'
										, sort='.$db->quote($category['sort']));
				$db->execute();
			}

			$n=0;
			$genres=array();
			foreach($xml->games->game as $game){
				$db->setQuery('INSERT INTO #__lupo_game SET
										number='.$db->quote($game['number']).'
										, catid='.$db->quote($game['catid']).'
										, age_catid='.$db->quote($game['age_catid']).'
										, title='.$db->quote($game->title).'
										, description='.$db->quote($game->description).'
										, days='.$db->quote($game['days']).'
										, fabricator='.$db->quote($game->fabricator).'
										, play_duration='.$db->quote($game->play_duration).'
										, players='.$db->quote($game->players).'
										, keywords='.$db->quote($game->keywords).'
										, genres='.$db->quote($game->genres)
				);
				$db->execute();
				$gameid = $db->insertid();

				if($game->genres!="") {
					$genres = array_merge($genres, explode(', ', $game->genres));
				}

				if(isset($game->documents->document)){
					foreach($game->documents->document as $document){
						$db->setQuery('INSERT INTO #__lupo_game_documents SET
												gameid='.$db->quote($gameid).'
												, `code`='.$db->quote($document['code']).'
												, `type`='.$db->quote($document['type']).'
												, `desc`='.$db->quote($document['desc']).'
												, `value`='.$db->quote($document['value'])
						);
						$db->execute();
					}
				}

				if(isset($game->related->game)){
					$related_games = $game->related;
					$sql_insertvalues = "";
					foreach($related_games->game as $related_game){
						//create bulk inserts: faster execution
						$sql_insertvalues .= '('.$db->quote($gameid).','.$db->quote($related_game).'),';
					}
					$db->setQuery('INSERT INTO #__lupo_game_related (`gameid`, `number`) VALUES '. substr($sql_insertvalues,0,-1));
					$db->execute();
				}

				foreach($game->editions->edition as $edition){
					$n++;
					$db->setQuery('INSERT INTO #__lupo_game_editions SET
											gameid='.$db->quote($gameid).'
											, `index`='.$db->quote($edition['index']).'
											, edition='.$db->quote($edition['edition']).'
											, acquired_date='.$db->quote($edition['acquired_date']).'
											, tax='.$db->quote($edition['tax'])
					);
					$db->execute();
				}
			}

			//add all genres to genre table
			$genres=array_unique($genres);
			foreach($genres as $genre){
				$db->setQuery('INSERT INTO #__lupo_genres SET
											genre='.$db->quote($genre)
				);
				$db->execute();
			}

			//add game-genres to table #__lupo_game_genre
			$db->setQuery("SELECT
										*
									FROM #__lupo_genres"
			);
			$res = $db->loadAssocList();
			$genres=array();
			foreach($res as $row){
				$genres[$row['id']]=$row['genre']; //made key value-array
			}
			$db->setQuery("SELECT
							id
							, genres
						FROM #__lupo_game
						WHERE genres!=''"
			);
			$res = $db->loadAssocList();
			foreach($res as $row){
				$game_genres = explode(', ',$row['genres']);
				foreach($game_genres as $game_genre){
					$db->setQuery('INSERT INTO #__lupo_game_genre SET
											gameid='.$row['id'].', genreid='.array_search($game_genre, $genres)
					);
					$db->execute();
				}
			}

			JFactory::getApplication()->enqueueMessage( $n . ' Spiele importiert' );
		}
	} else {
		JFactory::getApplication()->enqueueMessage( JText::_( "Konnte $file nicht öffnen." ), 'error' );
	}

}