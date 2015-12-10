<?php

/**
 * Zikula Application Framework
 *
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Joan Guillén i Pelegay (jguille2@xtec.cat)
 * 
 * @category   Sirius Modules
 */

function SiriusXtecAuth_tables()
{
    // Inclusió de la definició de la taula IWusers 
    $tables['IWusers'] = DBUtil::getLimitedTablename('IWusers');
    $tables['IWusers_column'] = array(
        'suid'        => 'iw_suid',
        'uid'         => 'iw_uid',
        'id'          => 'iw_id',
        'nom'         => 'iw_nom',
        'cognom1'     => 'iw_cognom1',
        'cognom2'     => 'iw_cognom2',
        'naixement'   => 'iw_naixement',
        'accio'       => 'iw_accio',
        'sex'         => 'iw_sex',
        'description' => 'iw_description',
        'avatar'      => 'iw_avatar',
        'newavatar'   => 'iw_newavatar',
    );
    $tables['IWusers_column_def'] = array('suid' => "I NOTNULL AUTO PRIMARY",
        'uid' => "I NOTNULL DEFAULT '0'",
        'id' => "C(50) NOTNULL DEFAULT ''",
        'nom' => "C(25) NOTNULL DEFAULT ''",
        'cognom1' => "C(25) NOTNULL DEFAULT ''",
        'cognom2' => "C(25) NOTNULL DEFAULT ''",
        'naixement' => "C(8) NOTNULL DEFAULT ''",
        'accio' => "I(1) NOTNULL DEFAULT '0'",
        'sex' => "C(1) NOTNULL DEFAULT ''",
        'description' => "X NOTNULL",
        'avatar' => "C(50) NOTNULL DEFAULT ''",
        'newavatar' => "C(50) NOTNULL DEFAULT ''",
    );

    //Returns informació de les taules
    return $tables;
}
