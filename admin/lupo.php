<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_lupo')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Set some global property
$app      = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-generic:before {content: "" }'); //remove icon

// Require helper file
JLoader::register('LupoHelper', JPATH_COMPONENT . '/helpers/lupo.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Lupo
$controller = JControllerLegacy::getInstance('Lupo');

// Perform the Request task
$controller->execute($app->input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();



//TODO: Move functions fo controller / model
set_time_limit(600); //10 Min

$zipfile   = 'lupo_spiele_export.zip';
$xmlfile   = 'lupospiele.xml';
$xmlpath   = JPATH_SITE . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
$gamespath = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'spiele' . DIRECTORY_SEPARATOR;


if (isset($_POST['act']) && $_POST['act'] == 'processzip') {
    if (file_exists($xmlpath . $xmlfile)) {
        unlink($xmlpath . $xmlfile);
    }
    if (unzipImages($xmlpath . $zipfile, $xmlpath, $xmlfile, $gamespath)) {
        processXML($xmlpath . $xmlfile);

        $uri = JUri::getInstance();
        $app->redirect($uri->toString());
    }
}


if (isset($_POST['act']) && $_POST['act'] == 'processxml') {
    processXML($xmlpath . $xmlfile);

    $uri = JUri::getInstance();
    $app->redirect($uri->toString());
}

if (isset($_POST['act']) && $_POST['act'] == 'deleteimages') {
    $files = glob($gamespath . '*.jpg'); // get all file names
    $n     = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $n++;
        }
    }
    JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_LUPO_ADMIN_DELETE_IMAGES_MSG', $n));

    $uri = JUri::getInstance();
    $app->redirect($uri->toString());
}


function unzipImages($zipfile, $xmlpath, $xmlfile, $gamespath)
{
    $zip = new ZipArchive;
    $res = $zip->open($zipfile);
    if ($res === true) {
        $zip->extractTo($xmlpath, [$xmlfile]); //extract xml

        //array_map('unlink', glob($gamespath."*.jpg")); //delete all image files before extracting from uploaded zip
        $jpgs = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (pathinfo($filename, PATHINFO_EXTENSION) == 'jpg') {
                $jpgs[] = $filename;
            }
        }
        $zip->extractTo($gamespath, $jpgs); //extract full archive
        $zip->close();

        return true;
    } else {
        JFactory::getApplication()->enqueueMessage(JText::_("COM_LUPO_ADMIN_MSG_ERROR_NO_ZIP_FILE"), 'error');
    }

    return false;
}


function processXML($file)
{
    if (file_exists($file)) {
        $xml = simplexml_load_file($file);
        if ($xml == false) {
            JFactory::getApplication()->enqueueMessage(JText::_("COM_LUPO_ADMIN_MSG_ERROR_XML_INVALID"), 'error');
        } else {
            $db = JFactory::getDBO();

            //cache data not provided in xml, save afterwards
            $db->setQuery("SELECT `alias`, `subsets` FROM #__lupo_categories WHERE NOT ISNULL(subsets)");
            $res_categories = $db->loadAssocList();
            $db->setQuery("SELECT `alias`, `subsets` FROM #__lupo_agecategories WHERE NOT ISNULL(subsets)");
            $res_agecategories = $db->loadAssocList();
            $db->setQuery("SELECT `alias`, `subsets` FROM #__lupo_genres WHERE NOT ISNULL(subsets)");
            $res_genres = $db->loadAssocList();


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

            foreach ($xml->categories->category as $category) {
                $db->setQuery('INSERT INTO #__lupo_categories SET
										`id`=' . $db->quote($category['id']) . '
										, `title`=' . $db->quote($category['desc']) . '
										, `alias`=' . $db->quote(JApplicationHelper::stringURLSafe($category['desc'])) . '
										, `description`=' . $db->quote($category['explanation']) . '
										, `samples`=' . $db->quote($category['samples']) . '
										, `sort`=' . $db->quote($category['sort']));
                $db->execute();
            }

            foreach ($xml->age_categories->category as $category) {
                $db->setQuery('INSERT INTO #__lupo_agecategories SET
										`id`=' . $db->quote($category['id']) . '
										, `title`=' . $db->quote($category['desc']) . '
										, `alias`=' . $db->quote(JApplicationHelper::stringURLSafe($category['desc'])) . '
										, `description`=' . $db->quote($category['explanation']) . '
										, `samples`=' . $db->quote($category['samples']) . '
										, `age_number`=' . $db->quote($category['age_number']) . '
										, `sort`=' . $db->quote($category['sort']));
                $db->execute();
            }

            $n      = 0;
            $genres = [];
            foreach ($xml->games->game as $game) {
                $title = $output = preg_replace('!\s+!', ' ', $game->title);
                $db->setQuery('INSERT INTO #__lupo_game SET
										`number`=' . $db->quote($game['number']) . '
										, `id_databauer`=' . $db->quote($game['id_databauer']) . '
										, `ean`=' . $db->quote(isset($game['ean']) ? $game['ean'] : '0') . '
										, `catid`=' . $db->quote($game['catid']) . '
										, `age_catid`=' . $db->quote($game['age_catid']) . '
										, `title`=' . $db->quote($title) . '
										, `description_title`=' . $db->quote($game->description_title) . '
										, `description`=' . $db->quote($game->description) . '
										, `days`=' . $db->quote($game['days']) . '
										, `fabricator`=' . $db->quote($game->fabricator) . '
										, `author`=' . $db->quote($game->author) . '
										, `artist`=' . $db->quote($game->artist) . '
										, `play_duration`=' . $db->quote($game->play_duration) . '
										, `players`=' . $db->quote($game->players) . '
										, `keywords`=' . $db->quote($game->keywords) . '
										, `genres`=' . $db->quote($game->genres) . '
										, `prolongable`=' . $db->quote($game->prolongable)
                );
                $db->execute();
                $gameid = $db->insertid();

                if ($game->genres != "") {
                    $genres = array_merge($genres, explode(', ', $game->genres));
                }

                if (isset($game->documents->document)) {
                    foreach ($game->documents->document as $document) {
                        $db->setQuery('INSERT INTO #__lupo_game_documents SET
												`gameid`=' . $db->quote($gameid) . '
												, `code`=' . $db->quote($document['code']) . '
												, `type`=' . $db->quote($document['type']) . '
												, `desc`=' . $db->quote($document['desc']) . '
												, `value`=' . $db->quote($document['value'])
                        );
                        $db->execute();
                    }
                }

                if (isset($game->related->game)) {
                    $related_games    = $game->related;
                    $sql_insertvalues = "";
                    foreach ($related_games->game as $related_game) {
                        //create bulk inserts: faster execution
                        $sql_insertvalues .= '(' . $db->quote($gameid) . ',' . $db->quote($related_game) . '),';
                    }
                    $db->setQuery('INSERT INTO #__lupo_game_related (`gameid`, `number`) VALUES ' . substr($sql_insertvalues, 0, -1));
                    $db->execute();
                }

                foreach ($game->editions->edition as $edition) {
                    $n++;
                    $db->setQuery('INSERT INTO #__lupo_game_editions SET
											`gameid`=' . $db->quote($gameid) . '
											, `index`=' . $db->quote($edition['index']) . '
											, `edition`=' . $db->quote($edition['edition']) . '
											, `acquired_date`=' . $db->quote($edition['acquired_date']) . '
											, `tax`=' . $db->quote(str_replace(',', '.', $edition['tax'])) . ' 
											, `content`=' . $db->quote($edition['content'])
                    );
                    $db->execute();
                }
            }

            //add all genres to genre table
            $genres = array_unique($genres);
            asort($genres);
            $last_alias = false;
            foreach ($genres as $genre) {
                $alias = str_replace("-", "_", JFilterOutput::stringURLSafe($genre));
                if ($alias != $last_alias) { //because array_unique does not filter out double alias
                    $db->setQuery('INSERT INTO #__lupo_genres SET
											`genre`=' . $db->quote($genre) . ',
											`alias`=' . $db->quote($alias)
                    );
                }
                $db->execute();
                $last_alias = $alias;
            }

            //add game-genres to table #__lupo_game_genre
            $db->setQuery("SELECT
										*
									FROM #__lupo_genres"
            );
            $res    = $db->loadAssocList();
            $genres = [];
            foreach ($res as $row) {
                $genres[$row['id']]          = $row['genre']; //made key value-array
                $genres_alias[$row['genre']] = $row['alias'];
            }
            $db->setQuery("SELECT
							`id`
							, `genres`
						FROM #__lupo_game
						WHERE genres!=''"
            );
            $res = $db->loadAssocList();
            foreach ($res as $row) {
                unset($genres_alias_list);
                $game_genres = explode(', ', $row['genres']);
                foreach ($game_genres as $game_genre) {
                    $db->setQuery('INSERT INTO #__lupo_game_genre SET
											`gameid`=' . $row['id'] . ', `genreid`=' . array_search($game_genre, $genres)
                    );
                    $db->execute();
                    $genres_alias_list[] = $genres_alias[$game_genre];
                }

                //update genres in games-table (calculated filed)
                if (is_array($genres_alias_list)) {
                    $db->setQuery('UPDATE #__lupo_game 
                                      SET `genres`=' . $db->quote(implode(',', $genres_alias_list)) . '
                                      WHERE id=' . $row['id']
                    );
                    $db->execute();
                }
            }


            //save cached data, not provided in xml
            foreach ($res_categories as $row) {
                $db->setQuery('UPDATE #__lupo_categories 
                                      SET `subsets`=' . $db->quote($row['subsets']) . '
                                      WHERE `alias`=' . $db->quote($row['alias']))->execute();
            }
            foreach ($res_agecategories as $row) {
                $db->setQuery('UPDATE #__lupo_agecategories 
                                      SET `subsets`=' . $db->quote($row['subsets']) . '
                                      WHERE `alias`=' . $db->quote($row['alias']))->execute();
            }
            foreach ($res_genres as $row) {
                $db->setQuery('UPDATE #__lupo_genres 
                                      SET `subsets`=' . $db->quote($row['subsets']) . '
                                      WHERE `alias`=' . $db->quote($row['alias']))->execute();
            }

            //store upload date
            $stats_file = JPATH_ROOT.'/images/upload_stats.json';
            if(file_exists($stats_file)) {
                $json = json_decode(file_get_contents($stats_file), true);
            }
            $json['toylist'] = date('Y-m-d H:i:s');
            file_put_contents($stats_file, json_encode($json));


            JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_LUPO_ADMIN_MSG_SUCCESS_IMPORTED', $n));
        }
    } else {
        JFactory::getApplication()->enqueueMessage(JText::_("COM_LUPO_ADMIN_MSG_ERROR_XML_NOT_FOUND"), 'error');
    }

}