<?php

if (!isset($_COOKIE['SWIFT_sso_cookie']))
{
    $_SWIFT = SWIFT::GetInstance();
    $_COOKIE['SWIFT_sso_fb_key']    = $_SWIFT->Settings->Get('sso_facebook_key');
    $_COOKIE['SWIFT_sso_fb_secret'] = $_SWIFT->Settings->Get('sso_facebook_secret');
    $_COOKIE['SWIFT_sso_tw_key']    = $_SWIFT->Settings->Get('sso_twitter_key');
    $_COOKIE['SWIFT_sso_tw_secret'] = $_SWIFT->Settings->Get('sso_twitter_secret');
    $_COOKIE['SWIFT_sso_cookie']    = true;
}

$config = array(
	'example-userpass' => array(
		'exampleauth:UserPass',
		'ashish:somepwd' => array(
			'uid' => array('ashish'),
			'fullname' => array('Ashish Kataria'),
			'email' => array('ashish.k.kataria@gmail.com'),
			'designation' => array('Sr. Software Engineer'),
		),
		'mahesh:somepwd' => array(
			'uid' => array('mahesh'),
			'fullname' => array('Mahesh Salaria'),
			'email' => array('mahesh.salaria@kayako.com'),
			'designation' => array('Team Lead'),
		),
		'customer:somepwd' => array(
			'uid' => array('customer'),
			'eduPersonAffilation' => array('customer'),
		),
	 ),

	'twitter' => array(
		'authtwitter:Twitter',
		'key' => $_COOKIE['SWIFT_sso_tw_key'],
		'secret' => $_COOKIE['SWIFT_sso_tw_secret'],
	),

	'facebook' => array(
		'authfacebook:Facebook',
		// Register your Facebook application on http://www.facebook.com/developers
		// App ID or API key (requests with App ID should be faster; https://github.com/facebook/php-sdk/issues/214)
		'api_key' => $_COOKIE['SWIFT_sso_fb_key'],
		// App Secret
		'secret' => $_COOKIE['SWIFT_sso_fb_secret'],
		// which additional data permissions to request from user
		// see http://developers.facebook.com/docs/authentication/permissions/ for the full list
		// 'req_perms' => 'email,user_birthday',
	),

/*
	// Example of an authsource that authenticates against Google.
	// See: http://code.google.com/apis/accounts/docs/OpenID.html
	'google' => array(
		'openid:OpenIDConsumer',
		// Googles OpenID endpoint.
		'target' => 'https://www.google.com/accounts/o8/id',
		// Custom realm
		// 'realm' => 'http://*.example.org',
		// Attributes that google can supply.
		'attributes.ax_required' => array(
			//'http://axschema.org/namePerson/first',
			//'http://axschema.org/namePerson/last',
			//'http://axschema.org/contact/email',
			//'http://axschema.org/contact/country/home',
			//'http://axschema.org/pref/language',
		),
		// custom extension arguments
		'extension.args' => array(
			//'http://specs.openid.net/extensions/ui/1.0' => array(
			//	'mode' => 'popup',
			//	'icon' => 'true',
			//),
		),
	),
	*/
);
