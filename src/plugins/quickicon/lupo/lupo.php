<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class plgQuickiconLupo extends JPlugin {
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);

		$app = JFactory::getApplication();

		// only in Admin and only if the component is enabled
		if ($app->getClientId() !== 1 || JComponentHelper::getComponent('com_lupo', true)->enabled === false) {
			return;
		}

		$this->loadLanguage();
	}

	public function onGetIcons($context) {
		if ($context != $this->params->get('context', 'mod_quickicon')) {
			return;
		}

		return [[
			'link'   => 'index.php?option=com_lupo&amp;view=lupos',
			'image'  => 'picture fa fa-dice',
			'access' => [],
			'text'   => JText::_('PLG_QUICKICON_LUPO_TITLE'),
			'id'     => 'plg_quickicon_lupo',
		]];
	}
}
		