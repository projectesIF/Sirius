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
function EZComments_migrateapi_pnComments()
{
    if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    if (!ModUtil::available('pnComments')) {
        return LogUtil::RegisterError('pnComments not available');
    }
    ModUtil::dbInfoLoad('pnComments');

    $comments = DBUtil::SelectObjectArray('pncomments');

    $counter  = 0;
    foreach ($comments as $c) {
        $obj = array (
            'modname'   => $c['module'],
            'objectid'  => $c['objectid'],
            'comment'   => $c['text'],
            'replyto'   => -1,
            'subject'   => $c['subject'],
            'uid'       => $c['uid'],
            'date'      => $c['date'].' 00:00:00'
        );

        if (!DBUtil::insertObject($obj, 'EZComments')) {
            return LogUtil::registerError('error inserting comments in ezcomments table');
        }

        $counter++;
    }

    return LogUtil::registerStatus("Migrated: $counter comments successfully.");
}
