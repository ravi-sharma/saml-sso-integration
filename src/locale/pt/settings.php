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

$__LANG = array(
	// ====== General ========
	"settings_sso"           => "Single Sign On",
	"sso_enable"             => "Enable Single Sign On",

	"sso_facebook_tab"       => "Facebook",
	"sso_facebook"           => "Enable Facebook Authentication",
	"sso_facebook_key"       => "Facebook API Key",
	"sso_facebook_secret"    => "Facebook API Secret",

	"sso_twitter_tab"        => "Twitter",
	"sso_twitter"            => "Enable Twitter Authentication",
	"sso_twitter_key"        => "Twitter Consumer key",
	"sso_twitter_secret"     => "Twitter Consumer secret",

	"sso_idp_tab"            => "SAML IdP",
	"sso_entityid"           => "Entity ID i.e. the index of your IdP metadata array",
	"sso_url"                => "SingleSignOnService URL that Kayako will invoke to redirect users to your Identity Provider<br>Note :-<br>i) Your IdP should return Name and Email address with keys as fullname and email respectively <br>ii) Our Assertion Consumer Service (ACS) URL is http://yourservername.kayako.com/index.php?/Samlsso/Sso/Idp/Login ",
	"sso_cer"                => "Path for CertFingerprint i.e. SAML certificate (obtain this from your SAML identity provider)",
	"sso_attribute_fullname" => "IDP attribute name for fullname",
	"sso_attribute_email"    => "IDP attribute name for email",
);
?>