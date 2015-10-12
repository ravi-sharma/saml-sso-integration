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
 * Controller class for handling Simple Sign On Request
 *
 * @author Ashish Kataria
 */
class Controller_SSO extends Controller_client
{
	/**
	 * The id of the authentication source we are accessing.
	 *
	 * @var string
	 * @access private
	 */
	private $_authSource = "";

	/**
	 * Used for storing Wrapper library object
	 *
	 * @access private
	 * @var string
	 */
	private $_samlSsoWrapperObject;

	/**
	 * Constructor
	 *
	 * @author Ashish Kataria
	 * @throws SWIFT_Exception
	 */
	public function __construct()
	{
		parent::__construct();

		$this->Language->Load('samlsso', SWIFT_LanguageEngine::TYPE_FILE);

		$this->Load->Library('Samlsso:SamlSsoWrapper');

		$this->_samlSsoWrapperObject = new SWIFT_SamlSsoWrapper();

		return true;
	}

	/**
	 * Destructor
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * Function for handling login/ logout request for twitter
	 *
	 * @author Ashish Kataria
	 *
	 * @param  string $_param Request for login or logout
	 */
	public function Twitter($_param = "")
	{
		$this->_authSource = 'twitter';

		if ('Logout' == $_param) {
			self::Logout();
		}

		if ('Login' == $_param) {
			self::SetCookies();

			if (!self::Login($this->_authSource)) {
				self::ErrorMsg('invaliduser');
			} else {
				/**
				 * Retrieve the users attributes
				 */
				$_attributes = $this->_samlSsoWrapperObject->GetAttributes();

				if (isset($_attributes['twitter.name']['0']) && isset($_attributes['twitter_screen_n_realm']['0'])) {
					self::UserChecking($_attributes['twitter.name']['0'], $_attributes['twitter_screen_n_realm']['0']);
				} else {
					self::ErrorMsg('errorstring');
				}

				self::DeleteCookies();
			}
		}
	}

	/**
	 * Function for handling login/ logout request for facebook
	 *
	 * @author Ashish Kataria
	 *
	 * @param string $_param Request for login or logout
	 */
	public function Facebook($_param = "")
	{
		$_SWIFT = SWIFT::GetInstance();

		$this->_authSource = 'facebook';

		if ('Logout' == $_param) {
			self::Logout();
		}

		if ('Login' == $_param) {
			self::SetCookies();

			if (!self::Login($this->_authSource)) {
				self::ErrorMsg('invaliduser');
			} else {
				/**
				 * Retrieve the users attributes
				 */
				$_attributes = $this->_samlSsoWrapperObject->GetAttributes();

				if (isset($_attributes['facebook.name']['0']) && isset($_attributes['facebook_user']['0'])) {
					self::UserChecking($_attributes['facebook.name']['0'], $_attributes['facebook_user']['0']);
				} else {
					self::ErrorMsg('errorstring');
				}

				self::DeleteCookies();
			}
		}
	}

	/**
	 * Function for handling login/ logout request for IdP
	 *
	 * @author Ashish Kataria
	 *
	 * @param  string $_output If set means user has been authenticated
	 */
	public function Idp($_output = "")
	{
		$_SWIFT = SWIFT::GetInstance();

		$this->_samlSsoWrapperObject->SetSamlSsoSettings();

		if ("Login" == $_output) {
			$_SAML = $this->_samlSsoWrapperObject->GetSamlSsoInstance();

			if ($_SAML->isValid()) {
				$this->_authSource = "Idp";
				$_userDesignation  = $_emailAttribute = $_fullNameAttribute = '';

				$_emailAttribute    = $_SWIFT->Settings->Get('sso_attribute_email');
				$_fullNameAttribute = $_SWIFT->Settings->Get('sso_attribute_fullname');

				if (empty($_emailAttribute) || empty($_fullNameAttribute)) {
					self::ErrorMsg('sso_noAttributeDefined');
				}

				// Retrieve the users attributes
				$attributes = $_SAML->getAttributes();

				// Check to see if a user designation has been provided
				if (isset($attributes['designation']['0'])) {
					$_userDesignation = $attributes['designation']['0'];
				}

				if (isset($attributes[$_fullNameAttribute]['0']) && isset($attributes[$_emailAttribute]['0'])) {
					self::UserChecking($attributes[$_fullNameAttribute]['0'], $attributes[$_emailAttribute]['0'], $_userDesignation);
				} else {
					self::ErrorMsg('idp_invalidResponse');
				}
			} else {
				self::ErrorMsg('sso_invalidResponse');
			}
		} else {
			if ("Logout" == $_output) {
				$this->_authSource = $_SWIFT->Settings->Get('sso_entityid');

				self::Logout();
			} else {
				self::SetCookies();

				$this->_samlSsoWrapperObject->SendAuthRequest();
			}
		}
	}

	/**
	 * Function for checking various conditions for login user
	 *
	 * @author Ashish Kataria
	 *
	 * @param string $_name        User name
	 * @param string $_email       User email
	 * @param string $_designation User designation
	 *
	 * @throws SWIFT_User_Exception
	 */
	public function UserChecking($_name, $_email, $_designation = "")
	{
		$_SWIFT = SWIFT::GetInstance();

		if (!$this->GetIsClassLoaded()) {
			throw new SWIFT_User_Exception(SWIFT_CLASSNOTLOADED);
		}

		// First retrieve the user id based on email address
		$_userID = SWIFT_UserEmail::RetrieveUserIDOnUserEmail($_email);

		if (!$_userID) {
			$_languageID = $_SWIFT->TemplateGroup->GetProperty('languageid');

			// No user found then create one
			$_SWIFT_UserObject = SWIFT_User::Create($_SWIFT->TemplateGroup->GetRegisteredUserGroupID(), '', SWIFT_User::SALUTATION_NONE, $_name, $_designation, '', true, true, array($_email), substr(BuildHash(), 0, 12), $_languageID, false, true, false, false, false, true, true);

			$_userID = SWIFT_UserEmail::RetrieveUserIDOnUserEmail($_email);

			$_SWIFT_UserObject = new SWIFT_User(new SWIFT_DataID($_userID));
		} else {
			$_SWIFT_UserObject = new SWIFT_User(new SWIFT_DataID($_userID));

			$_SWIFT_UserObject->UpdatePool('fullname', $_name);
			$_SWIFT_UserObject->UpdatePool('lastupdate', DATENOW);
			$_SWIFT_UserObject->ProcessUpdatePool();

			if (!empty($_designation)) {
				$_SWIFT_UserObject->UpdateUserDesignation($_designation);
			}
		}

		// Is user disabled?
		if ($_SWIFT_UserObject->GetProperty('isenabled') == '0' || $_SWIFT_UserObject->GetProperty('isvalidated') == '0') {
			self::ErrorMsg('invaliduserdisabled');
		} // Has user expired?
		else {
			if ($_SWIFT_UserObject->GetProperty('userexpirytimeline') != '0' && $_SWIFT_UserObject->GetProperty('userexpirytimeline') < DATENOW) {
				self::ErrorMsg('invaliduserexpired');
			}
		}

		$_SWIFT_UserObject->LoadIntoSWIFTNameSpace();

		$_SWIFT_UserObject->UpdateLastVisit();

		// Check for template group restriction..
		if ($_SWIFT->TemplateGroup->GetProperty('restrictgroups') == '1' && $_SWIFT->TemplateGroup->GetRegisteredUserGroupID() != $_SWIFT->User->GetProperty('usergroupid')) {
			self::ErrorMsg('invalidusertgroupres');
		}

		// So by now we have the user object, we need to update the session record..
		$_SWIFT->Session->Update($_userID);

		$_templateGroupCache  = $this->Cache->Get('templategroupcache');
		$_templateGroupString = '';

		// If we dont have a custom template group to load and theres one set in cookie.. attempt to use it..
		if ($this->Cookie->GetVariable('client', 'templategroupid')) {
			$_templateGroupID     = intval($this->Cookie->GetVariable('client', 'templategroupid'));
			$_templateGroupString = '/' . $_templateGroupCache[$_templateGroupID]['title'];
		}

		if ($_SWIFT->TemplateGroup->GetRegisteredUserGroupID() != $_SWIFT->User->GetProperty('usergroupid')) {
			foreach ($_templateGroupCache as $_key => $_val) {
				if ($_val["regusergroupid"] == $_SWIFT->User->GetProperty('usergroupid')) {
					$_templateGroupString = '/' . $_val["title"];
					break;
				}
			}
		}

		$this->Cookie->Set('authSource', ucfirst($this->_authSource));

		header("location: " . SWIFT::Get('basename') . $_templateGroupString . '/Base/UserAccount/Profile');
		exit();
	}

	/**
	 * This handles login requests.
	 *
	 * @author Ashish Kataria
	 *
	 * @param  string $_idP SSO IdP
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Login($_idP)
	{
		$this->_samlSsoWrapperObject->SetSamlIdpInstance($_idP);

		if ($this->_samlSsoWrapperObject->CheckLogin()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *  This handles logout requests
	 *
	 * @author Ashish Kataria
	 * @return bool "true"
	 */
	public function Logout()
	{
		$_SWIFT = SWIFT::GetInstance();

		$this->_samlSsoWrapperObject->SignOut($this->_authSource);

		// Update session
		$_SWIFT->Session->Update(0);

		// Unset cookies
		$this->Cookie->Delete('authSource');

		// Redirect..
		header("location: " . SWIFT::Get('basename'));

		return true;
	}

	/**
	 * For handling error
	 *
	 * @author Ashish Kataria
	 *
	 * @param string $_errMsg Error Message
	 *
	 * @return bool "false"
	 */
	public function ErrorMsg($_errMsg)
	{
		$_SWIFT = SWIFT::GetInstance();

		$this->UserInterface->Error(true, $_SWIFT->Language->Get($_errMsg));

		$this->Load->Controller('Default', 'base')->Load->Index();

		return false;
	}

	/**
	 * For Accessing SP and IdP
	 *
	 * @author Ashish Kataria
	 */
	public function Access()
	{
		$this->_samlSsoWrapperObject->AccessIdp();
	}

	/**
	 * For setting values in cookie
	 *
	 * @author Ashish Kataria
	 */
	private function SetCookies()
	{
		$_SWIFT = SWIFT::GetInstance();

		$this->Cookie->Set('sso_cookie', true);
		if ($_SWIFT->Settings->Get('sso_twitter_key') != '') {
			$this->Cookie->Set('sso_tw_key', $_SWIFT->Settings->Get('sso_twitter_key'));
		} else {
			$this->Cookie->Set('sso_tw_key', 0);
		}

		if ($_SWIFT->Settings->Get('sso_twitter_secret') != '') {
			$this->Cookie->Set('sso_tw_secret', $_SWIFT->Settings->Get('sso_twitter_secret'));
		} else {
			$this->Cookie->Set('sso_tw_secret', 0);
		}

		if ($_SWIFT->Settings->Get('sso_facebook_key') != '') {
			$this->Cookie->Set('sso_fb_key', $_SWIFT->Settings->Get('sso_facebook_key'));
		} else {
			$this->Cookie->Set('sso_fb_key', 0);
		}

		if ($_SWIFT->Settings->Get('sso_facebook_secret') != '') {
			$this->Cookie->Set('sso_fb_secret', $_SWIFT->Settings->Get('sso_facebook_secret'));
		} else {
			$this->Cookie->Set('sso_fb_secret', 0);
		}
	}

	/**
	 * For deleting values set in cookie
	 *
	 * @author Ashish Kataria
	 */
	private function DeleteCookies()
	{
		$this->Cookie->Delete('sso_cookie');
		$this->Cookie->Delete('sso_tw_key');
		$this->Cookie->Delete('sso_tw_secret');
		$this->Cookie->Delete('sso_fb_key');
		$this->Cookie->Delete('sso_fb_secret');
	}
}