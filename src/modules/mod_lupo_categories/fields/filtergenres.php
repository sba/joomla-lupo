<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('subform');

/**
 * Subform field with backward compatibility for legacy textarea values.
 */
class JFormFieldFiltergenres extends JFormFieldSubform
{
    /**
     * Field type
     *
     * @var string
     */
    protected $type = 'Filtergenres';

    /**
     * Normalize legacy values before Joomla's SubformField::setup() runs its own
     * is_string($this->value) + json_decode($this->value, true) check. That check
     * assigns $this->value directly (bypassing any magic setter) and turns plain
     * legacy text into null, because json_decode() fails on non-JSON strings.
     * Normalizing here - before parent::setup() ever sees the raw value - is the
     * only point where we can prevent that data loss.
     *
     * @param \SimpleXMLElement $element
     * @param mixed             $value
     * @param string|null       $group
     *
     * @return boolean
     */
    public function setup(\SimpleXMLElement $element, $value, $group = null)
    {
        $value = $this->normalizeLegacyValue($value);

        return parent::setup($element, $value, $group);
    }

    /**
     * Render field input.
     *
     * @return string
     */
    protected function getInput()
    {
        $this->value = $this->normalizeLegacyValue($this->value);

        return parent::getInput();
    }

    /**
     * Convert legacy textarea or scalar arrays into subform row arrays.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function normalizeLegacyValue($value)
    {
        $value = $this->extractFilterValue($value);

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            // Support both JSON arrays and JSON strings from legacy parameter storage.
            if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_string($decoded))) {
                $value = $decoded;
            }
        }

        if (is_string($value)) {
            return $this->rowsToSubformValue($this->tokensToRows($this->stringToTokens($value)));
        }

        if (is_object($value)) {
            $value = (array) $value;
        }

        if (!is_array($value) || empty($value)) {
            return array();
        }

        $first = reset($value);

        if (is_array($first) || is_object($first)) {
            return $this->rowsToSubformValue($this->normalizeRows($value));
        }

        // Older list values can be stored as scalar arrays, sometimes with multiline entries.
        $tokens = array();

        foreach ($value as $entry) {
            if (!is_scalar($entry)) {
                continue;
            }

            $entry = (string) $entry;

            // Legacy textarea content can arrive wrapped in a scalar array when switching field types.
            if (preg_match('/\R/', $entry)) {
                $tokens = array_merge($tokens, $this->stringToTokens($entry));
                continue;
            }

            $tokens[] = $entry;
        }

        return $this->rowsToSubformValue($this->tokensToRows($tokens));
    }

    /**
     * Extract filter value from wrapped legacy structures.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function extractFilterValue($value)
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (is_array($value) && array_key_exists('filter_genres', $value)) {
            return $value['filter_genres'];
        }

        return $value;
    }

    /**
     * Normalize row objects/arrays to the flat structure expected by the subform.
     *
     * @param array $rows
     *
     * @return array
     */
    protected function normalizeRows(array $rows)
    {
        $flatRows = array();

        foreach ($rows as $row) {
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

            if (!is_scalar($token)) {
                continue;
            }

            $token = trim((string) $token);

            if ($token === '') {
                continue;
            }

            $flatRows[] = array('genre_token' => $token);
        }

        return $flatRows;
    }

    /**
     * Convert flat rows into the nested structure Joomla subform expects.
     *
     * @param array $rows
     *
     * @return array
     */
    protected function rowsToSubformValue(array $rows)
    {
        $subformValue = array();
        $index        = 0;

        foreach ($rows as $row) {
            if (!is_array($row) || !isset($row['genre_token'])) {
                continue;
            }

            $token = trim((string) $row['genre_token']);

            if ($token === '') {
                continue;
            }

            // Joomla's SubformField::loadSubFormData() binds each row directly against
            // filter_genre_item.xml, which has no "item" group - so the row value must be flat.
            $subformValue['filter_genres' . $index] = array(
                'genre_token' => $token,
            );
            $index++;
        }

        return $subformValue;
    }

    /**
     * Split legacy textarea content into tokens.
     *
     * @param string $value
     *
     * @return array
     */
    protected function stringToTokens($value)
    {
        $trimmed = trim((string) $value);

        if ($trimmed === '') {
            return array();
        }

        return preg_split('/\R+/', $trimmed);
    }

    /**
     * Build subform rows and map legacy titles to current genre aliases.
     *
     * @param array $tokens
     *
     * @return array
     */
    protected function tokensToRows(array $tokens)
    {
        $rows         = array();
        $titleToAlias = $this->getGenreTitleMap();

        foreach ($tokens as $token) {
            if (!is_scalar($token)) {
                continue;
            }

            $token = trim((string) $token);

            if ($token === '') {
                continue;
            }

            // Normalize legacy separator variants like "--" to the supported "-" token.
            if (preg_match('/^-+$/', $token)) {
                $token = '-';
            }

            if ($token !== '-' && isset($titleToAlias[$token])) {
                $token = (string) $titleToAlias[$token];
            } elseif ($token !== '-') {
                $lookup = function_exists('mb_strtolower') ? mb_strtolower($token, 'UTF-8') : strtolower($token);

                if (isset($titleToAlias[$lookup])) {
                    $token = (string) $titleToAlias[$lookup];
                }
            }

            $rows[] = array('genre_token' => $token);
        }

        return $rows;
    }

    /**
     * Return map from genre title to genre alias.
     *
     * @return array
     */
    protected function getGenreTitleMap()
    {
        $map = array();

        // Query all genres directly instead of LupoModelLupo::getGenres(), which only
        // returns genres that currently have at least one linked game (INNER JOIN).
        // Legacy filter titles must still map to their alias even without linked games.
        $db = JFactory::getDbo();
        /** @noinspection SqlResolve */
        $db->setQuery('SELECT alias, genre AS title FROM #__lupo_genres ORDER BY genre');
        $genres = (array) $db->loadAssocList();

        foreach ($genres as $genre) {
            if (isset($genre['title'], $genre['alias'])) {
                $title = trim((string) $genre['title']);

                if ($title === '') {
                    continue;
                }

                $map[$title] = $genre['alias'];
                $map[function_exists('mb_strtolower') ? mb_strtolower($title, 'UTF-8') : strtolower($title)] = $genre['alias'];
            }
        }

        return $map;
    }
}
