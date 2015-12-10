<?php

/**
 * Zikula Application Framework
 *
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Joan Guillén i Pelegay (jguille2@xtec.cat)
 * 
 * @category   Sirius Modules
 */

class SiriusXtecAuth_Controller_Admin extends Zikula_AbstractController
{
    public function main()
    {
        // Security check
        if (!SecurityUtil::checkPermission('SiriusXtecAuth::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $MVars = ModUtil::getVar($this->name);
		$login_redirect = ModUtil::getVar('Users','login_redirect');
        $allGroups = UserUtil::getGroups();
        
        foreach ($allGroups as $key => $group) {
            
            if (in_array($key,$MVars['new_users_groups'])) {
                $allGroups[$key]['sel'] = 1;
            }else {
                $allGroups[$key]['sel'] = 0;
            }
        }
		$this->view->assign('login_redirect',$login_redirect);
        $this->view->assign('MVars', $MVars);
        $this->view->assign('allGroups',$allGroups);
        return $this->view->fetch('SiriusXtecAuth_admin_config.tpl');
    }
    public function updateConfig($args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('SiriusXtecAuth::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $items = array( 'ldap_active' => FormUtil::getPassedValue('ldap_active', false, 'POST')?true:false,
                'users_creation' => FormUtil::getPassedValue('users_creation', false, 'POST')?true:false,
                'new_users_activation' => FormUtil::getPassedValue('new_users_activation', false, 'POST')?true:false,
                'iw_write' => FormUtil::getPassedValue('iw_write', false, 'POST')?true:false,
                'iw_lastnames' => FormUtil::getPassedValue('iw_lastnames', false, 'POST')?true:false,
                'new_users_groups' => FormUtil::getPassedValue('new_users_groups', array(), 'POST'),
                'ldap_server' => FormUtil::getPassedValue('ldap_server', false, 'POST'),
                'ldap_basedn' => FormUtil::getPassedValue('ldap_basedn', false, 'POST'),
                'ldap_searchattr' => FormUtil::getPassedValue('ldap_searchattr', false, 'POST'),
                'loginXtecApps' => FormUtil::getPassedValue('loginXtecApps', false, 'POST'),
                'logoutXtecApps' => FormUtil::getPassedValue('logoutXtecApps', false, 'POST'),
                'gtafProtocol' => FormUtil::getPassedValue('gtafProtocol', false, 'POST'),
                'e13Protocol' => FormUtil::getPassedValue('e13Protocol', false, 'POST'),
                'gtafURL' => FormUtil::getPassedValue('gtafURL', false, 'POST'),
                'e13URL' => FormUtil::getPassedValue('e13URL', false, 'POST'),
				'loginTime' => FormUtil::getPassedValue('loginTime', false, 'POST'),
				'logoutTime' => FormUtil::getPassedValue('logoutTime', false, 'POST'));
        ModUtil::setVars($this->name,$items);
        LogUtil::registerStatus($this->__('S\'ha actualitzat la configuració del mòdul.'));
        return System::redirect(ModUtil::url('SiriusXtecAuth', 'admin', 'main'));
    }
}
