<?php
/**
 * SAML 2.0 remote SP metadata for simpleSAMLphp.
 *
 * See: http://simplesamlphp.org/docs/trunk/simplesamlphp-reference-sp-remote
 */

$metadata['php-saml'] = array (
  'AssertionConsumerService' => 'http://ver2.kayako.com/index.php?/Samlsso/Sso/Idp/Login',
 // 'SingleLogoutService' => 'http://ver2.kayako.com/samlsp/module.php/saml/sp/saml2-logout.php/default-sp',
  'certData' => 'MIIEjzCCA3egAwIBAgIJANgpElLyik6hMA0GCSqGSIb3DQEBBQUAMIGLMQswCQYDVQQGEwJJTjEQMA4GA1UECBMHR3VyZ2FvbjEQMA4GA1UEBxMHSGFyeWFuYTEPMA0GA1UEChMGa2F5YWtvMQwwCgYDVQQLEwNkZXYxDzANBgNVBAMTBmFzaGlzaDEoMCYGCSqGSIb3DQEJARYZYXNoaXNoLmthdGFyaWFAa2F5YWtvLmNvbTAeFw0xMjA3MTEwODEwNDRaFw0yMjA3MTEwODEwNDVaMIGLMQswCQYDVQQGEwJJTjEQMA4GA1UECBMHR3VyZ2FvbjEQMA4GA1UEBxMHSGFyeWFuYTEPMA0GA1UEChMGa2F5YWtvMQwwCgYDVQQLEwNkZXYxDzANBgNVBAMTBmFzaGlzaDEoMCYGCSqGSIb3DQEJARYZYXNoaXNoLmthdGFyaWFAa2F5YWtvLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKuNMPHq/iQW3cjNzCnS+Y5okNruBvZ+hNV6pPbkChxheVcYbsWOBNc5K6DGW+L6HB1QWI4OheoCuOruNS5DWU+sAb7yAUV43N4lhQ/eI/5mx/V6FGadc9CCCsJtlaVPftsGhZ+uN0U0gZ36sxX7NeC/ie+GMM+FW1myJRp3OgiAs0F46MqrM3EzzxPwbNrgfrKaeS02FEg3rv3n3uyM+t5Xr6UvYH00Km4iAyT3tbxmHvp6XbSRCu1KuWYo1L/4zENWTux0rxu6Vn7Z/0V7ZZDav8mO/WlKFYuhbGn95HJCLdb07ZCUjyISqPMUORC+Y5WLPOvkFJLrFNLelWApUbMCAwEAAaOB8zCB8DAdBgNVHQ4EFgQUmTP3/xYOYPdLrh8srzUWT/onIuswgcAGA1UdIwSBuDCBtYAUmTP3/xYOYPdLrh8srzUWT/onIuuhgZGkgY4wgYsxCzAJBgNVBAYTAklOMRAwDgYDVQQIEwdHdXJnYW9uMRAwDgYDVQQHEwdIYXJ5YW5hMQ8wDQYDVQQKEwZrYXlha28xDDAKBgNVBAsTA2RldjEPMA0GA1UEAxMGYXNoaXNoMSgwJgYJKoZIhvcNAQkBFhlhc2hpc2gua2F0YXJpYUBrYXlha28uY29tggkA2CkSUvKKTqEwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAFx9pHLdoSG1LTo2CDjaf2DDkjUrvdL0fiWauflD559x6IC1Vb4WKEjv8eJcLJlkWmg0jfieQ9pHpe379kJkae23UuDpd2G0ny9sPzQ5DlAaQTdjgTWrRxTQbYhSVuUXTuZ/rPGrmoP891t4bAIGWzN0fv9AHywEKBA1RCh+CFuH1Ba8g0qYlp3vGWOUK0co6KDJ9OfscU/xPY9b9iVdOPgdfXIe8KEG/AvVg4lERJ1ID/VG0TKgKJl7HfDUJ1xsoZk5TLYi7XIxqE7s/vJ+Cv4afGe9tlHzVe6L8OMS4zZXHXTQlNcwYoWMCVWv0qp3DILVoGDYIjTpV2hhcymZi4A==',
);

/*
 * Example simpleSAMLphp SAML 2.0 SP
 */
$metadata['https://saml2sp.example.org'] = array(
	'AssertionConsumerService' => 'https://saml2sp.example.org/simplesaml/module.php/saml/sp/saml2-acs.php/default-sp',
	'SingleLogoutService' => 'https://saml2sp.example.org/simplesaml/module.php/saml/sp/saml2-logout.php/default-sp',
);

/*
 * This example shows an example config that works with Google Apps for education.
 * What is important is that you have an attribute in your IdP that maps to the local part of the email address
 * at Google Apps. In example, if your google account is foo.com, and you have a user that has an email john@foo.com, then you
 * must set the simplesaml.nameidattribute to be the name of an attribute that for this user has the value of 'john'.
 */
$metadata['google.com'] = array(
	'AssertionConsumerService' => 'https://www.google.com/a/g.feide.no/acs',
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:email',
	'simplesaml.nameidattribute' => 'uid',
	'simplesaml.attributes' => FALSE,
);
