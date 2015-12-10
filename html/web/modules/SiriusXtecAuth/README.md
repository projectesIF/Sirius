SiriusXtecAuth module
=====================
SiriusXtecAuth module 1.0.1 **for Zikula 1.3.x**

Implements LDAP validation
  - It's a listener: When Zikula authentication has failed, start SiriusXtecAuth.
  - It's optimized and tested for xtec-ldap.
  - Sync options with [IWusers](https://github.com/intraweb-modules13/IWusers) module (not required)
  - Diferent options on configuration: create users/only auth; new users actived/inactived; mapping options with IWusers

  - Add Http Basic Authentication possibility to two apps (login and logout)

Notes:
  - No locales made: All strings in gettext, using catalan language.
  - Http Basic Auth logout needs 'WCAG-compliant log-in and log-out' unchecked in Users module settings.

Changelog
---------

### Master branch

### SiriusXtecAuth 1.0.2 (2015/09/28)

  - Add protocol vars for the URLs on Basic Authentication

### SiriusXtecAuth 1.0.1 (2014/12/15)

  - Add Http Basic Authentication feature to two apps (checked for gtaf and e13)

### SiriusXtecAuth 1.0.0 (2014/11/21)

Init version at github
