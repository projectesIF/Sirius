<?php

/**
 * Zikula Application Framework
 *
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Joan Guillén i Pelegay (jguille2@xtec.cat)
 * 
 * @category   Sirius Modules
 */

class SiriusXtecAuth_Installer extends Zikula_AbstractInstaller
{
    /**
    * Initialise SiriusXtecAuth module.
    */
    public function install()
    {
        // create module vars
        // ldap configuration
        $this->setVars(array('ldap_server'  => 'host.domain',
                             'ldap_basedn'  => 'cn=users,dc=host,dc=domain',
                             'ldap_searchattr' => 'cn'));
        // module configutation
        $defaultGroupId = ModUtil::getVar('Groups', 'defaultgroup');
        $initGroups = array($defaultGroupId);
        $this->setVars(array('ldap_active' => false,
                             'users_creation' => false,
                             'new_users_activation' => false,
                             'new_users_groups' => $initGroups,
                             'iw_write' => false,
                             'iw_lastnames' => false,
                             'loginXtecApps' => false,
                             'logoutXtecApps' => false,
                             'gtafProtocol' => 'http',
                             'e13Protocol' => 'http',
                             'gtafURL' => 'aplitic.xtec.cat/pls/gafoas/pk_for_mod_menu.p_for_opcions_menu?p_perfil=RES',
                             'e13URL' => 'aplitic.xtec.cat/pls/e13_formacio_gaf/formacio_gaf.inici',
							 'loginTime' => 200,
							 'logoutTime' => 200));
        // register handler
        EventUtil::registerPersistentModuleHandler('SiriusXtecAuth', 'module.users.ui.login.failed', array('SiriusXtecAuth_Listeners', 'trySiriusXtecAuth'));
        EventUtil::registerPersistentModuleHandler('SiriusXtecAuth', 'module.users.ui.logout.succeeded', array('SiriusXtecAuth_Listeners', 'logoutXtecApps'));
        // finish
        return true;
    }
    /**
    * Remove SiriusXtecAuth module and all associative information.
    */
    public function uninstall()
    {
        $this->delVars;
        EventUtil::unregisterPersistentModuleHandler('SiriusXtecAuth');
        return true;
    }
    public function upgrade($oldversion)
    {
        switch ($oldVersion) {
            case '1.0.0':
                EventUtil::registerPersistentModuleHandler('SiriusXtecAuth', 'module.users.ui.logout.succeeded', array('SiriusXtecAuth_Listeners', 'logoutXtecApps'));
                $this->setVars(array('loginXtecApps' => false,
                                    'logoutXtecApps' => false,
                                    'gtafURL' => 'aplitic.xtec.cat/pls/gafoas/pk_for_mod_menu.p_for_opcions_menu?p_perfil=RES',
                                    'e13URL' => 'aplitic.xtec.cat/pls/e13_formacio_gaf/formacio_gaf.inici',
									'loginTime' => 200,
							 		'logoutTime' => 200));
            case '1.0.1':
                $this->setVars(array('gtafProtocol' => 'http',
                                     'e13Protocol' => 'http'));
            case '1.0.2':
                //This is the current version. Here next changes will be added
        }        
        return true;
    }
}

