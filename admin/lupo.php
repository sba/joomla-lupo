<?php
/**
 * @package        Joomla
 * @subpackage     LUPO
 * @copyright      Copyright (C) databauer / Stefan Bauer
 * @author         Stefan Bauer
 * @link           http://www.ludothekprogramm.ch
 * @license        License GNU General Public License version 2 or later
 */

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// Access check.
if ( ! JFactory::getUser()->authorise( 'core.manage', 'com_lupo' ) ) {
	throw new Exception( JText::_( 'JERROR_ALERTNOAUTHOR' ), 404 );
}

// Set some global property
$app      = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleDeclaration( '.icon-generic:before {content: "" }' ); //remove icon
//$document->addStyleDeclaration('.strong {font-weight: bold;}');

// import joomla controller library
jimport( 'joomla.application.component.controller' );

// Get an instance of the controller prefixed by Lupo
$controller = JControllerLegacy::getInstance( 'Lupo' );

// Perform the Request task
$controller->execute( $app->input->getCmd( 'task' ) );

// Redirect if set by the controller
$controller->redirect();


set_time_limit( 600 ); //10 Min

$zipfile   = 'lupo_spiele_export.zip';
$xmlfile   = 'lupospiele.xml';
$xmlpath   = JPATH_SITE . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
$gamespath = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'spiele' . DIRECTORY_SEPARATOR;


if ( isset( $_POST['act'] ) && $_POST['act'] == 'processzip' ) {
	if ( file_exists( $xmlpath . $xmlfile ) ) {
		unlink( $xmlpath . $xmlfile );
	}
	if ( unzipImages( $xmlpath . $zipfile, $xmlpath, $xmlfile, $gamespath ) ) {
		processXML( $xmlpath . $xmlfile );
	}
}


if ( isset( $_POST['act'] ) && $_POST['act'] == 'processxml' ) {
	processXML( $xmlpath . $xmlfile );
}

if ( isset( $_POST['act'] ) && $_POST['act'] == 'deleteimages' ) {
	$files = glob( $gamespath . '*.jpg' ); // get all file names
	$n     = 0;
	foreach ( $files as $file ) {
		if ( is_file( $file ) ) {
			unlink( $file );
			$n ++;
		}
	}
	JFactory::getApplication()->enqueueMessage( JText::sprintf( 'COM_LUPO_ADMIN_DELETE_IMAGES_MSG', $n ) );

	$uri = JUri::getInstance();
	$app->redirect( $uri->toString() );
}


function unzipImages( $zipfile, $xmlpath, $xmlfile, $gamespath ) {
	$zip = new ZipArchive;
	$res = $zip->open( $zipfile );
	if ( $res === true ) {
		$zip->extractTo( $xmlpath, array( $xmlfile ) ); //extract xml

		//array_map('unlink', glob($gamespath."*.jpg")); //delete all image files before extracting from uploaded zip
		$jpgs = array();
		for ( $i = 0; $i < $zip->numFiles; $i ++ ) {
			$filename = $zip->getNameIndex( $i );
			if ( pathinfo( $filename, PATHINFO_EXTENSION ) == 'jpg' ) {
				$jpgs[] = $filename;
			}
		}
		$zip->extractTo( $gamespath, $jpgs ); //extract full archive
		$zip->close();

		return true;
	} else {
		JFactory::getApplication()->enqueueMessage( JText::_( "COM_LUPO_ADMIN_MSG_ERROR_NO_ZIP_FILE" ), 'error' );
	}

	return false;
}


function processXML( $file ) {
	if ( file_exists( $file ) ) {
		$xml = simplexml_load_file( $file );
		if ( $xml == false ) {
			JFactory::getApplication()->enqueueMessage( JText::_( "COM_LUPO_ADMIN_MSG_ERROR_XML_INVALID" ), 'error' );
		} else {
			$db = JFactory::getDBO();

			$db->setQuery( 'TRUNCATE #__lupo_game' );
			$db->execute();

			$db->setQuery( 'TRUNCATE #__lupo_game_editions' );
			$db->execute();

			$db->setQuery( 'TRUNCATE #__lupo_game_documents' );
			$db->execute();

			$db->setQuery( 'TRUNCATE #__lupo_categories' );
			$db->execute();

			$db->setQuery( 'TRUNCATE #__lupo_agecategories' );
			$db->execute();

			$db->setQuery( 'TRUNCATE #__lupo_genres' );
			$db->execute();

			$db->setQuery( 'TRUNCATE #__lupo_game_genre' );
			$db->execute();

			$db->setQuery( 'TRUNCATE #__lupo_game_related' );
			$db->execute();

			foreach ( $xml->categories->category as $category ) {
				$db->setQuery( 'INSERT INTO #__lupo_categories SET
										`id`=' . $db->quote( $category['id'] ) . '
										, `title`=' . $db->quote( $category['desc'] ) . '
										, `alias`=' . $db->quote( JApplicationHelper::stringURLSafe( $category['desc'] ) ) . '
										, `description`=' . $db->quote( $category['explanation'] ) . '
										, `samples`=' . $db->quote( $category['samples'] ) . '
										, `sort`=' . $db->quote( $category['sort'] ) );
				$db->execute();
			}

			foreach ( $xml->age_categories->category as $category ) {
				$db->setQuery( 'INSERT INTO #__lupo_agecategories SET
										`id`=' . $db->quote( $category['id'] ) . '
										, `title`=' . $db->quote( $category['desc'] ) . '
										, `alias`=' . $db->quote( JApplicationHelper::stringURLSafe( $category['desc'] ) ) . '
										, `description`=' . $db->quote( $category['explanation'] ) . '
										, `samples`=' . $db->quote( $category['samples'] ) . '
										, `sort`=' . $db->quote( $category['sort'] ) );
				$db->execute();
			}

			$n      = 0;
			$genres = array();
			foreach ( $xml->games->game as $game ) {
				$db->setQuery( 'INSERT INTO #__lupo_game SET
										`number`=' . $db->quote( $game['number'] ) . '
										, `id_databauer`=' . $db->quote( $game['id_databauer'] ) . '
										, `catid`=' . $db->quote( $game['catid'] ) . '
										, `age_catid`=' . $db->quote( $game['age_catid'] ) . '
										, `title`=' . $db->quote( $game->title ) . '
										, `description_title`=' . $db->quote( $game->description_title ) . '
										, `description`=' . $db->quote( $game->description ) . '
										, `days`=' . $db->quote( $game['days'] ) . '
										, `fabricator`=' . $db->quote( $game->fabricator ) . '
										, `author`=' . $db->quote( $game->author ) . '
										, `artist`=' . $db->quote( $game->artist ) . '
										, `play_duration`=' . $db->quote( $game->play_duration ) . '
										, `players`=' . $db->quote( $game->players ) . '
										, `keywords`=' . $db->quote( $game->keywords ) . '
										, `genres`=' . $db->quote( $game->genres ) . '
										, `prolongable`=' . $db->quote( $game->prolongable )
				);
				$db->execute();
				$gameid = $db->insertid();

				if ( $game->genres != "" ) {
					$genres = array_merge( $genres, explode( ', ', $game->genres ) );
				}

				if ( isset( $game->documents->document ) ) {
					foreach ( $game->documents->document as $document ) {
						$db->setQuery( 'INSERT INTO #__lupo_game_documents SET
												`gameid`=' . $db->quote( $gameid ) . '
												, `code`=' . $db->quote( $document['code'] ) . '
												, `type`=' . $db->quote( $document['type'] ) . '
												, `desc`=' . $db->quote( $document['desc'] ) . '
												, `value`=' . $db->quote( $document['value'] )
						);
						$db->execute();
					}
				}

				if ( isset( $game->related->game ) ) {
					$related_games    = $game->related;
					$sql_insertvalues = "";
					foreach ( $related_games->game as $related_game ) {
						//create bulk inserts: faster execution
						$sql_insertvalues .= '(' . $db->quote( $gameid ) . ',' . $db->quote( $related_game ) . '),';
					}
					$db->setQuery( 'INSERT INTO #__lupo_game_related (`gameid`, `number`) VALUES ' . substr( $sql_insertvalues, 0, - 1 ) );
					$db->execute();
				}

				foreach ( $game->editions->edition as $edition ) {
					$n ++;
					$db->setQuery( 'INSERT INTO #__lupo_game_editions SET
											`gameid`=' . $db->quote( $gameid ) . '
											, `index`=' . $db->quote( $edition['index'] ) . '
											, `edition`=' . $db->quote( $edition['edition'] ) . '
											, `acquired_date`=' . $db->quote( $edition['acquired_date'] ) . '
											, `tax`=' . $db->quote( str_replace( ',', '.', $edition['tax'] ) )
					);
					$db->execute();
				}
			}

			//add all genres to genre table
			$genres = array_unique( $genres );
			foreach ( $genres as $genre ) {
				$db->setQuery( 'INSERT INTO #__lupo_genres SET
											`genre`=' . $db->quote( $genre )
				);
				$db->execute();
			}

			//add game-genres to table #__lupo_game_genre
			$db->setQuery( "SELECT
										*
									FROM #__lupo_genres"
			);
			$res    = $db->loadAssocList();
			$genres = array();
			foreach ( $res as $row ) {
				$genres[ $row['id'] ] = $row['genre']; //made key value-array
			}
			$db->setQuery( "SELECT
							`id`
							, `genres`
						FROM #__lupo_game
						WHERE genres!=''"
			);
			$res = $db->loadAssocList();
			foreach ( $res as $row ) {
				$game_genres = explode( ', ', $row['genres'] );
				foreach ( $game_genres as $game_genre ) {
					$db->setQuery( 'INSERT INTO #__lupo_game_genre SET
											`gameid`=' . $row['id'] . ', `genreid`=' . array_search( $game_genre, $genres )
					);
					$db->execute();
				}
			}

			JFactory::getApplication()->enqueueMessage( JText::sprintf( 'COM_LUPO_ADMIN_MSG_SUCCESS_IMPORTED', $n ) );
		}
	} else {
		JFactory::getApplication()->enqueueMessage( JText::_( "COM_LUPO_ADMIN_MSG_ERROR_XML_NOT_FOUND" ), 'error' );
	}

}