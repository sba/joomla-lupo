<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_lupo_categories
 *
 * @package     Joomla.Site
 * @subpackage  mod_lupo_categories
 * @since       1.0
 */
class ModLupoCategoriesHelper
{
    /**
     * Retrieve list of categories
     *
     * @param JRegistry  &$params module parameters
     *
     * @return  mixed
     */
    public static function &getList(&$params)
    {
        if (!class_exists('LupoModelLupo')) {
            JLoader::import('lupo', JPATH_BASE . '/components/com_lupo/models');
        }

        $model         = new LupoModelLupo();
        $newgames      = $model->getCategoryNew();
        $categories    = $model->getCategories(false, false);
        $agecategories = $model->getAgecategories(false, false);
        $genres        = $model->getGenres();

        // Filter genres by configured order with backward compatibility to older formats.
        $filter_param = $params->get('filter_genres', array());

        // Legacy values may be stored as JSON-encoded strings (e.g. "Spiel...\r\n-").
        if (is_string($filter_param)) {
            $decoded = json_decode($filter_param, true);

            if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_string($decoded))) {
                $filter_param = $decoded;
            }
        }

        if (is_object($filter_param)) {
            $filter_param = (array) $filter_param;
        }

        if (is_string($filter_param)) {
            // Legacy textarea format.
            $filter_param = trim((string) $filter_param) !== '' ? preg_split('/\R+/', trim((string) $filter_param)) : array();
        }

        if (is_array($filter_param) && !empty($filter_param)) {
            $first = reset($filter_param);

            // New subform format: list of rows, each row containing genre_token.
            if (is_array($first) || is_object($first)) {
                $normalized = array();

                foreach ($filter_param as $row) {
                    $token = null;

                    if (is_array($row) && isset($row['genre_token'])) {
                        $token = $row['genre_token'];
                    } elseif (is_array($row) && isset($row['item']) && is_array($row['item']) && isset($row['item']['genre_token'])) {
                        $token = $row['item']['genre_token'];
                    } elseif (is_object($row) && isset($row->genre_token)) {
                        $token = $row->genre_token;
                    } elseif (is_object($row) && isset($row->item) && is_object($row->item) && isset($row->item->genre_token)) {
                        $token = $row->item->genre_token;
                    }

                    if (is_scalar($token)) {
                        $normalized[] = $token;
                    }
                }

                $filter_param = $normalized;
            } else {
                // Legacy textarea content can arrive wrapped in scalar arrays after field type changes.
                $normalized = array();

                foreach ($filter_param as $entry) {
                    if (!is_scalar($entry)) {
                        continue;
                    }

                    $entry = trim((string) $entry);

                    if ($entry === '') {
                        continue;
                    }

                    if (preg_match('/\R/', $entry)) {
                        $parts = preg_split('/\R+/', $entry);

                        foreach ($parts as $part) {
                            $part = trim((string) $part);

                            if ($part !== '') {
                                $normalized[] = $part;
                            }
                        }

                        continue;
                    }

                    $normalized[] = $entry;
                }

                $filter_param = $normalized;
            }
        } else {
            $filter_param = array();
        }

        if (!empty($filter_param)) {
            $genres_new      = array();
            $genres_by_alias = array();
            $genres_by_key   = array();

            foreach ($genres as $genre) {
                $genres_by_alias[(string) $genre['alias']] = $genre;
                $genres_by_key[$genre['title']]            = $genre;
            }

            foreach ($filter_param as $filter_genre) {
                if (!is_scalar($filter_genre)) {
                    continue;
                }

                $filter_genre = trim((string) $filter_genre);

                if (preg_match('/^-+$/', $filter_genre)) {
                    $genres_new[] = $filter_genre;
                    continue;
                }

                if (isset($genres_by_alias[$filter_genre])) {
                    $genres_new[] = $genres_by_alias[$filter_genre];
                    continue;
                }

                if (isset($genres_by_key[$filter_genre])) {
                    $genres_new[] = $genres_by_key[$filter_genre];
                }
            }

            $genres = $genres_new;
        }

        if ($newgames[0]['number'] == 0) {
            $newgames = false;
        }

        $return = array('newgames' => $newgames, 'categories' => $categories, 'agecategories' => $agecategories, 'genres' => $genres);
        return $return;
    }
}
