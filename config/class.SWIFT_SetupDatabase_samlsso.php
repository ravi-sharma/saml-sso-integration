<?php

/**
 * =======================================
 * ###################################
 * SWIFT Framework
 *
 * @package	SWIFT
 * @author		Kayako Infotech Ltd.
 * @copyright	Copyright (c) 2001-2009, Kayako Infotech Ltd.
 * @license		http://www.kayako.com/license
 * @link		http://www.kayako.com
 * @filesource
 * ###################################
 * =======================================
 */

/**
 * Setting up the functionality that is executed when a
 * module is either installed, upgraded or uninstalled
 *
 * @author Ashish Kataria
 */
class SWIFT_SetupDatabase_samlsso extends SWIFT_SetupDatabase
{

	/**
	 * Constructor - Calls parent class constructor which sets the module name
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __construct()
	{
		parent::__construct('samlsso');
		return true;
	}

	/**
	 * Destructor - Calls parent class destructor
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();
		return  true;
	}

	/**
	 * Creates table and indexes and import module settings
	 * Called when a module is installed
	 *
	 * @param int $_pageIndex The Page Index\
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Install($_pageIndex = 1)
	{
		parent::Install($_pageIndex);

		$this->ImportSettings();

		return true	;
	}

	/**
	 * Imports module settings and calls parent class upgrade function
	 * Called when a module is upgraded
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Upgrade()
	{
		$this->ImportSettings();

		return parent::Upgrade();
	}

	/**
	 * Calls parent class upgrade function
	 * Called when a module is uninstalled
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Uninstall()
	{
		parent::Uninstall();

		return true;
	}

	/**
	 * Loads the table into the container
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function LoadTables()
	{
		return true;
	}

	/**
	 * Loads the settings from settings.xml file
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	private function ImportSettings()
	{
		$this->Load->Library('Settings:SettingsManager');
		$this->SettingsManager->Import('./'.SWIFT_APPSDIRECTORY.'/samlsso/config/settings.xml');

		return true;
	}

}
?>