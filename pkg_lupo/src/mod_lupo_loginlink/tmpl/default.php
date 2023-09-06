<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$class_hidden   = $nbr_reservations === 0 ? 'uk-hidden' : '';
$res            = '<i class="uk-icon-shopping-cart uk-align-right lupo_loginlink_reservations ' . $class_hidden . '" id="lupo_loginlink_reservations"> 
                    <span class="uk-badge uk-badge-notification" id="lupo_loginlink_reservations_nbr">' . $nbr_reservations . '</span>
                   </i>';
$login_linktext = "<span  class=\"lupo_loginlink_reservations $class_hidden\">".JText::_('MOD_LUPO_LOGINLINK_CART').'</span>';

$componentParams = JComponentHelper::getParams('com_lupo');
if ($componentParams->get('detail_show_toy_res_hide_login', '0') == 0) {
	if ($client_logged_in) {
		$login_linktext = '<i class="uk-icon-user"></i> ' . JText::_('MOD_LUPO_LOGINLINK_ACCOUNT');
	} else {
		$login_linktext = '<i class="uk-icon-sign-in"></i> ' . JText::_('MOD_LUPO_LOGINLINK_LOGIN');
	}
}

?>
<h3 id="lupo_loginlink"><a href="<?= $loginlink_url ?>"><?= $login_linktext . $res ?>&nbsp;</a></h3>

