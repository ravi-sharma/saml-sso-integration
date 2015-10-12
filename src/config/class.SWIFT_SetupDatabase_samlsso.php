<?php

/**
 * =======================================
 * ###################################
 * SWIFT Framework
 *
 * @package        SWIFT
 * @author         Kayako Infotech Ltd.
 * @copyright      Copyright (c) 2001-2015, Kayako Infotech Ltd.
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
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
	 */
	public function __construct()
	{
		parent::__construct('samlsso');
	}
}