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
 * Helper for mod_lupo_loginlink
 *
 */
class ModLupoLoginlinkHelper
{
	function __construct() {
		if (!class_exists( 'LupoModelLupoClient' )){
			JLoader::import( 'lupo', JPATH_BASE . '/components/com_lupo/models' );
		}
	}


	/**
	 * kills client login-session
	 */
	public static function clientLogout()
	{
		$model = new LupoModelLupoClient();
		$model->clientLogout();
		return;
	}




}
