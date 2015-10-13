Kayako SAML Single Sign-On Integration
=======================

This library is maintained by Kayako.

Overview
=======================

Security Assertion Markup Language is an XML-based open standard data format for exchanging authentication and authorization data between parties, in particular, between an identity provider and a service provider. The SAML specification defines three roles: the principal (typically a user), the identity provider (aka IdP), and the service provider (aka SP). In the use case addressed by SAML, the principal requests a service from the service provider. The service provider requests and obtains an identity assertion from the identity provider. On the basis of this assertion, the service provider can make an access control decision - in other words it can decide whether to perform some service for the connected principal.
(ref. http://en.wikipedia.org/wiki/Security_Assertion_Markup_Language and http://simplesamlphp.org/)

This is a Kayako module for SAML Single Sign-On integration for Kayako version 4.50. Using single sign-on (SSO) will permit a single action of user authentication and authorization to access all computers and systems where he has access permission, without the need to enter multiple passwords.

Features
=======================

* User can login with facebook credentials
* User can login with twitter credentials
* Can easily integrate with your IdP so that users in your organization can login with there IdP credentials
* Kayako admin can anytime disable Single Sign On from Admin->Settings->Single Sign On
