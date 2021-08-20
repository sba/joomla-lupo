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

class com_lupoInstallerScript
{
    private $component_name;

    public function __construct()
    {
        $this->component_name = 'com_lupo';
    }

    /**
     * This method is called after component is updated.
     *
     * @param \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent)
    {
        jimport('joomla.filter.output');

        $db = JFactory::getDBO();
        $db->setQuery("SELECT 
							*
						FROM
							#__lupo_genres
						ORDER BY genre");

        $res = $db->loadAssocList();

        foreach ($res as $row) {
            $query      = $db->getQuery(true);
            $alias      = str_replace("-", "_", JFilterOutput::stringURLSafe($row['genre']));
            $fields     = [
                $db->quoteName('alias') . ' = ' . $db->quote($alias),
            ];
            $conditions = [
                $db->quoteName('id') . ' = ' . $db->quote($row['id']),
            ];
            $query->update($db->quoteName('#__lupo_genres'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $db->execute();
        }
    }


    function postflight($type, $parent)
    {
        $lang = JFactory::getLanguage();
        $lang->load($this->component_name, JPATH_SITE, $lang->getTag(), true);

        //check if lang overrides are defined
        $file = JPATH_SITE . '/language/overrides/' . $lang->getTag() . '.override.ini';
        $ini  = [];
        if (is_file($file)) {
            $content = @file_get_contents($file);

            if ($content && is_string($content)) {
                $ini = @parse_ini_string($content, true);
            }
        }

        $body_default = isset($ini['COM_LUPO_RES_EMAIL_BODY']) ? $ini['COM_LUPO_RES_EMAIL_BODY'] : JText::_('COM_LUPO_RES_EMAIL_BODY');
        $body_default = str_replace('\n', "\n", $body_default);

        $params = JComponentHelper::getParams($this->component_name);

        $subject = $params->get('detail_toy_res_email_subject', '');
        if ($subject == "") {
            $subject = JText::_('COM_LUPO_RES_EMAIL_SUBJECT');
            $params->set('detail_toy_res_email_subject', $subject);
        }

        $body = $params->get('detail_toy_res_email_body', '');
        if ($body == "") {
            $params->set('detail_toy_res_email_body', $body_default);
        }

        // Save the parameters
        $componentid = JComponentHelper::getComponent($this->component_name)->id;
        $table       = JTable::getInstance('extension');
        $table->load($componentid);
        $table->bind(['params' => $params->toString()]);
        $table->store();
    }
    
}