<?php
/**
 * SWIFT Framework
 * _______________________________________________
 *
 * @author      Varun Shoor
 *
 * @package     SWIFT
 * @copyright   Copyright (c) 2001-2012, Kayako
 * @license     http://www.kayako.com/license
 * @link        http://www.kayako.com
 */

/**
 * Wrapper class for using SAML SSO
 *
 * @author Ashish Kataria
 */
class SWIFT_SamlSsoWrapper extends SWIFT_Library
{
    /**
     * Used for storing SAML IdP class object
     *
     * @access private
     * @var string
     */
    private $_samlIdpObject = false;

    /**
     * Used for storing saml SSO class object
     *
     * @access private
     * @var string
     */
    private $_samlSsoSettings = false;

    /**
     * Used for storing third party php-saml object
     *
     * @access private
     * @var string
     */
    private $_phpSamlObject = false;

    /**
     * Constructor
     *
     * @author Ashish Kataria
     * @return bool "true" on Success
     */
    public function __construct()
    {
        parent::__construct();

        $_SWIFT = SWIFT::GetInstance();

        $_libPath = './' . SWIFT_APPSDIRECTORY . '/samlsso/' . SWIFT_THIRDPARTYDIRECTORY . '/';

        /*
        * We need access to the various simpleSAMLphp classes. These are loaded
        * by the simpleSAMLphp autoloader.
        */
        require_once $_libPath . 'samlidp/lib/_autoload.php';
        require_once $_libPath . 'php-saml/ext/xmlseclibs/xmlseclibs.php';
        require_once $_libPath . 'php-saml/src/OneLogin/Saml/AuthRequest.php';
        require_once $_libPath . 'php-saml/src/OneLogin/Saml/Response.php';
        require_once $_libPath . 'php-saml/src/OneLogin/Saml/Settings.php';
        require_once $_libPath . 'php-saml/src/OneLogin/Saml/XmlSec.php';

        return true;
    }

    /**
     * Destructor
     *
     * @author Ashish Kataria
     * @return bool "true" on Success
     */
    public function __destruct()
    {
        parent::__destruct();

        return true;
    }

    /**
     * Set SAML IdP instance
     *
     * @author Ashish Kataria
     */
    public function SetSamlIdpInstance($_authSource)
    {
        $this->_samlIdpObject = new SimpleSAML_Auth_Simple($_authSource);
    }


    /**
     * Create and return Saml 12Response
     *
     * @author Ashish Kataria
     * @return object OneLogin_Saml_Response on Success, "false" otherwise
     */
    public function GetSamlSsoInstance()
    {
        if ($this->_samlSsoSettings && isset($_POST['SAMLResponse']) && !empty($_POST['SAMLResponse']))
        {
            return new OneLogin_Saml_Response($this->_samlSsoSettings, $_POST['SAMLResponse']);
        }

        return false;
    }

    /**
     * Function to set SAML IdP instance
     *
     * @author Ashish Kataria
     * @return bool "true" on Success, "false" otherwise
     */
    public function CheckLogin()
    {
        /*
        * If the login parameter is requested, it means that we should log
        * the user in. We do that by requiring the user to be authenticated.
        *
        * Note that the requireAuth-function will preserve all GET-parameters
        * and POST-parameters by default.
        *
        * This function will only return if the user is authenticated.
        */
        $this->_samlIdpObject->requireAuth();

        /*
        * Check whether the user is authenticated or not
        */
        if ($this->_samlIdpObject->isAuthenticated())
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns attributes
     *
     * @author Ashish Kataria
     * @return array The users attributes
     */
    public function GetAttributes()
    {
        return $this->_samlIdpObject->getAttributes();
    }

    /**
     * Set settings values
     *
     * @author Ashish Kataria
     */
    public function SetSamlSsoSettings()
    {
        $_SWIFT = SWIFT::GetInstance();

        $this->_samlSsoSettings = new OneLogin_Saml_Settings();

        /**
         * When using Service Provider Initiated SSO, this URL asks the IdP to authenticate the user
         */
        $this->_samlSsoSettings->idpSingleSignOnUrl = rtrim($_SWIFT->Settings->Get('sso_url'), '\/');

        /**
         * The certificate for the users account in the IdP
         */
        $this->_samlSsoSettings->idpPublicCertificate = file_get_contents($_SWIFT->Settings->Get('sso_cer'));

        /**
         * The URL where to the SAML Response/SAML Assertion will be posted
         */
        $this->_samlSsoSettings->spReturnUrl = SWIFT::Get('basepath') . '/Samlsso/Sso/Idp/Login';

        /**
         * Name of this application
         */
        $this->_samlSsoSettings->spIssuer = $_SWIFT->Settings->Get('sso_entityid');

        /**
         *  Tells the IdP to return the email address of the current user
         */
        $this->_samlSsoSettings->requestedNameIdFormat = OneLogin_Saml_Settings::NAMEID_EMAIL_ADDRESS;

    }

    /**
     * Send Auth Request
     *
     * @author Ashish Kataria
     */
    public function SendAuthRequest()
    {
        $_authRequest = new OneLogin_Saml_AuthRequest($this->_samlSsoSettings);
        $_url = $_authRequest->getRedirectUrl();

        header("Location: $_url");
    }

    /**
     *
     * Access IdP
     *
     * @author Ashish Kataria
     */
    public function AccessIdp()
    {
        SimpleSAML_Utilities::redirect(SimpleSAML_Module::getModuleURL('core/frontpage_welcome.php'));
    }

    /**
     *
     * Logout
     *
     * @author Ashish Kataria
     */
    public function SignOut($_authSource)
    {
        $_session = SimpleSAML_Session::getInstance();

        if ($_session->isValid($_authSource))
        {
            $_state = $_session->getAuthData($_authSource, 'LogoutState');
            if ($_state !== null)
            {
                $_params = array_merge($_state, $_params);
            }

            $_session->doLogout($_authSource);

            $_params['LogoutCompletedHandler'] = array(get_class(), 'logoutCompleted');

            $_samlAuthID = SimpleSAML_Auth_Source::getById($_authSource);
            if ($_samlAuthID !== null)
            {
                $_samlAuthID->logout($_params);
            }
        }
    }


}
