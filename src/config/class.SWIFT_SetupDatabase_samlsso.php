<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/saml-sso-integration
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