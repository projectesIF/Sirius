<?php

/**
 * Zikula Application Framework
 *
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Joan Guillén i Pelegay (jguille2@xtec.cat)
 * 
 * @category   Sirius Modules
 */

class SiriusXtecAuth_Listeners
{
    /**
     * When Zikula authentication has failed, start SiriusXtecAuth
     * 
     * @return bool true authetication succesful
     */
    public static function trySiriusXtecAuth(Zikula_Event $event)
    {
        $authentication_info = FormUtil::getPassedValue('authentication_info', isset($args['authentication_info']) ? $args['authentication_info'] : null, 'POST');
        // Argument check
        if ($authentication_info['login_id'] == '' || $authentication_info['pass'] == '') {
            LogUtil::registerError(__('Usuari o contrasenya en blanc.'));
            return System::redirect(System::getHomepageUrl());
        }

        $uname = $authentication_info['login_id'];
        $pass = $authentication_info['pass'];

        // check if ldap is active
        if (!ModUtil::getVar('SiriusXtecAuth','ldap_active',false)) return false;
        // checking new users case
        $userid = UserUtil::getIdFromName($uname);
        if (($userid === false) && (ModUtil::getVar('SiriusXtecAuth','users_creation',false) === false)) return false;
        
        // connect to ldap server
        if (!$ldap_ds = ldap_connect(ModUtil::getVar('SiriusXtecAuth', 'ldap_server'))) {
            LogUtil::registerError(__('No ha pogut connectar amb el servidor ldap.'));
            return false;
        }        
        ///////////////////
        // Checking ldap validation
        $ldaprdn = ModUtil::getVar('SiriusXtecAuth', 'ldap_searchattr') . '=' . $uname . ',' . ModUtil::getVar('SiriusXtecAuth', 'ldap_basedn');
        $bind = @ldap_bind($ldap_ds, $ldaprdn, $pass);
        if (!$bind) {
            LogUtil::registerError(__('La informació introduïda no correspon a cap validació manual ni XTEC.'));
            return false;
        }
        LogUtil::getErrorMessages();
        // Case new users
        if ($userid === false) {
            $userLdapFields = array ('cn', 'uid', 'givenname', 'sn', 'mail');
            // search the directory for our user
            if (!$ldap_sr = ldap_search($ldap_ds, ModUtil::getVar('SiriusXtecAuth', 'ldap_basedn'), ModUtil::getVar('SiriusXtecAuth', 'ldap_searchattr') . '=' . DataUtil::formatForStore($uname),$userLdapFields)) {
                LogUtil::registerError(__('Problemes en la creació d\'un nou usuari de Sirus des de la validació XTEC (I).'));
                return false;
            }
            $info = ldap_get_entries($ldap_ds, $ldap_sr);
            if (!$info || $info['count'] == 0) {
                LogUtil::registerError('Problemes en la creació d\'un nou usuari de Sirus des de la validació XTEC (II).');
                return false;
            } else {
                if (!isset($info[0]['dn'])) {
                    LogUtil::registerError('Problemes en la creació d\'un nou usuari de Sirus des de la validació XTEC (III).');
                    return false;
                }
            }
            
            $user['zk']['uname'] =$uname;
            $user['zk']['email'] = $info[0]['mail'][0];
            if (ModUtil::getVar('SiriusXtecAuth','iw_write',false) && ModUtil::available('IWusers')) {
                $user['iw']['nom'] = ucwords(strtolower($info[0]['givenname'][0]));
                $cognom_separator = strpos($info[0]['sn'][0],' ');
                if ($cognom_separator && ModUtil::getVar('SiriusXtecAuth','iw_lastnames',false)) {
                    $user['iw']['cognom1'] = ucwords(strtolower(substr($info[0]['sn'][0],0,$cognom_separator)));
                    $user['iw']['cognom2'] = ucwords(strtolower(substr($info[0]['sn'][0],$cognom_separator+1)));
                } else{
                    $user['iw']['cognom1'] = ucwords(strtolower($info[0]['sn'][0]));
                    $user['iw']['cognom1'] = '';
                }
            }
            if (ModUtil::getVar('SiriusXtecAuth','new_users_activation', false)) {
                $user['zk']['activated'] = 1;
            }else {
                $user['zk']['activated'] = 0;
            }
            $user['gr'] = ModUtil::getVar('SiriusXtecAuth','new_users_groups');
            
            $userid = ModUtil::apifunc('SiriusXtecAuth', 'listeners', 'createUser', $user);
            if (!$userid) {
                LogUtil::registerError(__('No s\'ha pogut crear l\'usuari. Torneu a validar-vos.'));
                return false;
            }
            
        }
        
        @ldap_unbind($ldap_ds);
        UserUtil::setUserByUid($userid);
        
        if (!ModUtil::getVar('SiriusXtecAuth','loginXtecApps',false)) {
            return System::redirect(System::getHomepageUrl());
        } else {
			$pass_e = urlencode(base64_encode($pass));
            return System::redirect(ModUtil::url('SiriusXtecAuth', 'user', 'logingXtecApps',array('uname'=>$uname,'pass'=>$pass_e,'logtype'=>'in')));
        }

    }
    public static function logoutXtecApps(Zikula_Event $event)
    {
        if (!ModUtil::getVar('SiriusXtecAuth','logoutXtecApps',false)) {
            return true;
        } else {
			$pass_e = urlencode(base64_encode('logout'));
            return System::redirect(ModUtil::url('SiriusXtecAuth', 'user', 'logingXtecApps',array('uname'=>'logout','pass'=>$pass_e,'logtype'=>'out')));
        }
    }
}
