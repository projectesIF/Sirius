<?php

/**
 * Zikula Application Framework
 *
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Joan Guillén i Pelegay (jguille2@xtec.cat)
 * 
 * @category   Sirius Modules
 */

class SiriusXtecAuth_Api_Listeners extends Zikula_AbstractApi
{
    public function createUser($user){
        if ($user) {
            $user['zk']['approved_date'] = DateUtil::getDatetime();
            $user['zk']['user_regdate'] = DateUtil::getDatetime();
            $user['zk']['approved_by'] = 2;
            DBUtil::insertObject($user['zk'], 'users', 'uid');
            $insertUserId = $user['zk']['uid'];
            $user['iw']['uid'] = $insertUserId;
            $user['iw']['suid'] = $insertUserId;
            DBUtil::insertObject($user['iw'], 'IWusers', 'suid');
            //Assignem els grups indicats en el formulari
            foreach ($user['gr'] as $grup) {
                $item = array('gid' => $grup, 'uid' => $user['zk']['uid']);
                DBUtil::insertObject($item, 'group_membership');
            }
            return $insertUserId;
        }else {
            return false;
        }
    }    
}
