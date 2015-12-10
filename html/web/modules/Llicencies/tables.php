<?php

/**
 * @authors Josep Ferràndiz Farré <jferran6@xtec.cat>
 * @authors Jordi Fons Vilardell  <jfons@xtec.cat>
 * @authors Joan Guillén Pelegay  <jguille2@xtec.cat>
 *
 * @par Llicència:
 * GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Llicencies
 * @version 1.0
 * @copyright Departament d'Ensenyament. Generalitat de Catalunya 2013-2014
 */

function Llicencies_tables() {
    // Initialise table array
    $tables = array();

// LLICENCIES *************************************************************************************************
    // llicencies: 
    //          Contindrà la informació de les llicències d'estudi realitzades i l'estat de finalització
    
    $tables['llicencies'] = DBUtil::getLimitedTablename('llicencies');
    $tables['llicencies_column'] = array(
                'codi_treball'     => 'codi_treball',
                'titol'            => 'titol',
                'caracteristiques' => 'caracteristiques',
                'orientacio'       => 'orientacio',
                'nivell'           => 'nivell' ,
                'resum'            => 'resum' ,
                'annexos'          => 'annexos' ,
                'web'              => 'web' ,
                'altres'           => 'altres' ,
                'tema'             => 'tema' ,
                'subtema'          => 'subtema' ,
                'tipus'            => 'tipus',
                'curs'             => 'curs',
                'capsa'            => 'capsa',
                'url'              => 'url',
                'modalitat'        => 'modalitat',
                'cognoms'          => 'cognoms',
                'nom'              => 'nom',
                'correuel'         => 'correuel',
                'estat'            => 'estat'                                
            );

    $tables['llicencies_column_def'] = array(
                'codi_treball'     => "I NOTNULL AUTO PRIMARY",
                'titol'            => "C(255) NOTNULL",
                'caracteristiques' => "X",
                'orientacio'       => "X",
                'nivell'           => "C(255)",
                'resum'            => "X",
                'annexos'          => "C(255)" ,
                'web'              => "C(255)" ,
                'altres'           => "C(255)" ,
                'tema'             => "I" ,
                'subtema'          => "I" ,
                'tipus'            => "I" ,
                'curs'             => "C(10)",
                'capsa'            => "C(10)",
                'url'              => "C(255)" ,
                'modalitat'        => "C(3)" ,
                'cognoms'          => "C(255)" ,
                'nom'              => "C(100)" ,
                'correuel'         => "C(255)",
                'estat'            => "I"            
            );
    // Afegir camps estàndard
    ObjectUtil::addStandardFieldsToTableDefinition($tables['llicencies_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['llicencies_column_def']);

// CURS *************************************************************************************************
    // curs: llistat de cursos disponibles format yyyy/yy
    $tables['llicencies_curs'] = DBUtil::getLimitedTablename('llicencies_curs');
    $tables['llicencies_curs_column'] = array(
                'curs'     => 'curs'
            );

    $tables['llicencies_curs_column_def'] = array(
                'curs'     => "C(10) NOTNULL PRIMARY"
            );
    // Afegir camps estàndard
    ObjectUtil::addStandardFieldsToTableDefinition($tables['llicencies_curs_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['llicencies_curs_column_def']);

// MODALITAT *************************************************************************************************
// modalitat de llicència A, B1, B2, B3 o C
    $tables['llicencies_modalitat'] = DBUtil::getLimitedTablename('llicencies_modalitat');
    $tables['llicencies_modalitat_column'] = array(
                'id_mod'     => 'id_mod',
                'descripcio' => 'descripcio'
            );

    $tables['llicencies_modalitat_column_def'] = array(
                'id_mod'     => "C(3) NOTNULL PRIMARY",
                'descripcio' => "C(255) NOTNULL"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['llicencies_modalitat_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['llicencies_modalitat_column_def']);


// TEMA *************************************************************************************************
    $tables['llicencies_tema'] = DBUtil::getLimitedTablename('llicencies_tema');
    $tables['llicencies_tema_column'] = array(
                'codi_tema' => 'codi_tema',
                'nom'       => 'nom'
            );

    $tables['llicencies_tema_column_def'] = array(
                'codi_tema' => "I NOTNULL PRIMARY",
                'nom'       => "C(255) NOTNULL"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['llicencies_tema_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['llicencies_tema_column_def']);

// SUBTEMA *************************************************************************************************
    $tables['llicencies_subtema'] = DBUtil::getLimitedTablename('llicencies_subtema');
    $tables['llicencies_subtema_column'] = array(
                'codi_subt' => 'codi_subt',
                'nom'          => 'nom'
            );

    $tables['llicencies_subtema_column_def'] = array(
                'codi_subt' => "I NOTNULL PRIMARY",
                'nom'          => "C(255) NOTNULL"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['llicencies_subtema_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['llicencies_subtema_column_def']);
    
// TIPUS *************************************************************************************************
//     Més categories relatives a l'àmbit del treball
    $tables['llicencies_tipus'] = DBUtil::getLimitedTablename('llicencies_tipus');
    $tables['llicencies_tipus_column'] = array(
                'codi_tipus'   => 'codi_tipus',
                'nom'          => 'nom'
            );

    $tables['llicencies_tipus_column_def'] = array(
                'codi_tipus'   => "I NOTNULL PRIMARY",
                'nom'          => "C(255) NOTNULL"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['llicencies_tipus_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['llicencies_tipus_column_def']);

// ESTATS *************************************************************************************************
// estat de la llicència: 
// sense memòria pdf, preparada, pròrroga, renúncia, expedient, desapareguda, pendent, en elaboració
    
    $tables['llicencies_estats'] = DBUtil::getLimitedTablename('llicencies_estats');
    $tables['llicencies_estats_column'] = array(
                'id_estat'   => 'id_estat',
                'descripcio' => 'descripcio'
            );

    $tables['llicencies_estats_column_def'] = array(
                'id_estat'   => "I NOTNULL PRIMARY",
                'descripcio' => "C(255) NOTNULL"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['llicencies_estats_column']);
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['llicencies_estats_column_def']);

    //Returns informació de les taules
    return $tables;
}


