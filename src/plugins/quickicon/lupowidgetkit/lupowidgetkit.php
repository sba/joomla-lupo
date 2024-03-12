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


class plgQuickiconLupowidgetkit extends JPlugin {
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);

		$app = JFactory::getApplication();

		// only in Admin and only if the component is enabled
		if ($app->getClientId() !== 1 || JComponentHelper::getComponent('com_widgetkit', true)->enabled === false) {
			return;
		}

		$this->loadLanguage();
	}

	public function onGetIcons($context) {
		if ($context != $this->params->get('context', 'mod_quickicon')) {
			return;
		}

		return [[
			'link'   => 'index.php?option=com_widgetkit',
			'image'  => 'picture fa fa-rocket',
			'access' => [],
			'text'   => 'Widgetkit',
			'id'     => 'plg_quickicon_widgetkit',
		]];
	}
}
		