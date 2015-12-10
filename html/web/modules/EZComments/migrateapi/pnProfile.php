<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link https://github.com/zikula-modules/EZComments
 * @license See license.txt
 */

/**
 * Do the migration
 * 
 * With this function, the actual migration is done.
 * 
 * @return   boolean   true on sucessful migration, false else
 */
function EZComments_migrateapi_pnProfile()
{
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError('pnProfile comments migration: Not Admin');
    }

    $columnArray = array('id', 'modname', 'objectid');
    $comments = DBUtil::selectObjectArray('EZComments', '', '', -1, -1, '', null, null, $columnArray);

    $counter  = 0;
    foreach ($comments as $comment) {
        if ($comment['modname'] == 'pnProfile') {
            $comment['modname']  = 'MyProfile';
            $comment['url']      = ModUtil::url('MyProfile', 'user', 'display', array('uid' => $comment['objectid']));
            $comment['owneruid'] = $comment['objectid'];
            if (DBUtil::updateObject($comment, 'EZComments')) {
                $counter++;
            }
        }
    }

    return LogUtil::registerStatus("Updated / migrated: $counter comments from pnProfile to MyProfile, the successor of pnProfile");
}
