<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

require_once(dirname(__FILE__) . '/helper.php');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$list            = ModLupoCategoriesHelper::getList($params);

/* load uikit styles */
jimport('joomla.application.component.helper');
$uikit = JComponentHelper::getParams('com_lupo')->get('lupo_load_uikit_css', "0");
if ($uikit !== "0") {
    $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
    $wa->registerAndUseStyle('com_lupo_uikit', 'components/com_lupo/uikit/css/' . $uikit);
}

require JModuleHelper::getLayoutPath('mod_lupo_categories', $params->get('layout', 'default'));
