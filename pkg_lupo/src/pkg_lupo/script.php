<?php

/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerScript;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since   4.0.0v1
 */
class Pkg_deDEInstallerScript extends InstallerScript
{
	/**
	 * Extension script constructor.
	 *
	 * @since   4.0.0v1
	 */
	public function __construct()
	{
		// Define the minimum versions to be supported.
		$this->minimumJoomla = '4.4';
		$this->minimumPhp    = '7.2.5';

		$this->deleteFiles = [
			'/administrator/components/com_lupo/sql/updates/mysql/2.0.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/2.3.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.0.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.2.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.4.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.6.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.7.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.9.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.10.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.12.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.19.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.20.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.24.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.37.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.40.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.41.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.42.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.54.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.64.0.sql',
			'/administrator/components/com_lupo/sql/updates/mysql/3.75.0.sql',
		];
	}

	/**
	 * Function to perform changes during postflight
	 *
	 * @param   string            $type    The action being performed
	 * @param   ComponentAdapter  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   4.0.0v1
	 */
	public function postflight($type, $parent)
	{
		$this->removeFiles();
	}
}
