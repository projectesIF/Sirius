<?php

/**
 * @authors Josep Ferràndiz Farré <jferran6@xtec.cat>
 * @authors Jordi Fons Vilardell  <jfons@xtec.cat>
 * @authors Joan Guillén Pelegay  <jguille2@xtec.cat> 
 * 
 * @par Llicència: 
 * GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Cataleg
 * @version 1.0
 * @copyright Departament d'Ensenyament. Generalitat de Catalunya 2012-2013
 */

function Cataleg_tables() {
    // Initialise table array
    $tables = array();
    
// CATALEG ************************************************************************************************* 
    // Cataleg: definició de la taula
    //          Contindrà la informació general del catàleg i l'estat 
    //          L'estat ens indica la visualització del catàleg. Cada estat incrementa la visualització
    //          0-Només el veuen els gestors
    //          1-El veuen els editors a 'les meves activitats'
    //		2-El veuen els editors en format 'catàleg' sense activitats
    //          3-El veuen els editors en format 'catàleg' complert
    //          4-El veuen els lectors
    //		Nota: els estats definits apareixen a la 1.0 (les versions anteriors tenien un altre sistema d'estats')
    $tables['cataleg'] = DBUtil::getLimitedTablename('cataleg');
    $tables['cataleg_column'] = array(
                'catId'   => 'catId',
                'anyAcad' => 'anyAcad', // Exemple: 2012-2013
                'nom'     => 'nom',	// Exemple: Catàleg unificat de formació del curs 2012-2013
                'estat'   => 'estat',   // Estat de visualització: 0, 1, 2, 3 o 4
                'editable'=> 'editable' // True si és el catàleg és editable (en les versions inicials(abans de la 1.0) hi havia el camp actiu)
            );

    $tables['cataleg_column_def'] = array(
                'catId'   => "I NOTNULL AUTO PRIMARY",
                'anyAcad' => "C(9) NOTNULL DEFAULT ''",
                'nom'     => "C(255) NOTNULL DEFAULT ''",
                'estat'   => "I1",
                'editable'=> "L NOTNULL DEFAULT 0"
            );
    // Afegir camps estàndard
    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_column_def'],'');
    
// EIXOS *************************************************************************************************   
    // eixos: definició de taula
    //                Contindrà informació relativa als eixos de les orientacions. 
    //                Cada prioritat s'enmarca en un eix
    $tables['cataleg_eixos'] = DBUtil::getLimitedTablename('cataleg_eixos');
    $tables['cataleg_eixos_column'] = array(
                'eixId'     => 'eixId',
                'catId'     => 'catId',
                'nom'       => 'nom',
                'nomCurt'   => 'nomCurt',
                'descripcio'=> 'descripcio',
                'ordre'     => 'ordre',
                'visible'   => 'visible'
            );

    $tables['cataleg_eixos_column_def'] = array(
                'eixId'     => "I NOTNULL AUTO PRIMARY",
                'catId'     => "I NOTNULL",
                'nom'       => "C(255)",
                'nomCurt'   => "C(100)" ,
                'descripcio'=> "X",
                'ordre'     => "C(2)",
                'visible'   => "L DEFAULT 1"
            );
    // Afegir camps estàndard
    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_eixos_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_eixos_column_def']);
    
// PRIORITATS *************************************************************************************************
    // prioritats:  definició de taula
    //              Recollirà la informació relativa a les prioritats i les orientacions
    $tables['cataleg_prioritats'] = DBUtil::getLimitedTablename('cataleg_prioritats');
    $tables['cataleg_prioritats_column'] = array(
                'priId'        => 'priId',
                'eixId'        => 'eixId',
                'nom'          => 'nom',
                'nomCurt'      => 'nomCurt',
                'orientacions' => 'orientacions',
                'recursos'     => 'recursos',
                'ordre'        => 'ordre',
                'visible'      => 'visible'
            );

    $tables['cataleg_prioritats_column_def'] = array(
                'priId'        => "I NOTNULL AUTO PRIMARY",
                'eixId'        => "I",
                'nom'          => "X",
                'nomCurt'      => "C(200)",
                'orientacions' => "X",
                'recursos'     => "X" ,
                'ordre'        => "I",
                'visible'      => "L DEFAULT 1"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_prioritats_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_prioritats_column_def']);

// UNITATS IMPLICADES *************************************************************************************************    
    // unitatsImplicades:  definició de taula
    //              Recollirà llista d'unitats implicades en una prioritat
    //              i les dades de les persones responsables
    $tables['cataleg_unitatsImplicades'] = DBUtil::getLimitedTablename('cataleg_unitatsImplicades');
    $tables['cataleg_unitatsImplicades_column'] = array(
                'impunitId'     => 'impunitId',       // Camp id de la taula d'unitats implicades   
                'priId'         => 'priId',          // Id prioritat relacionada
                'uniId'         => 'uniId',          // Id Unitat implicada
                'tematica'      => 'tematica',       // Opcional. Indicació temàtica específica de la prioritat
                'pContacte'     => 'pContacte',      // Persona de contacte
                'email'		=> 'email',	     // Correu electrònic de la persona de contacte
                'telContacte'   => 'telContacte',    // Telèfon/extensió persona de contacte
                'dispFormador'  => 'dispFormador'    // La unitat organitzadora disposa de persones formadores                
            );

    $tables['cataleg_unitatsImplicades_column_def'] = array(
                'impunitId'     => "I NOTNULL AUTO PRIMARY", //Afegit a la versió 1.0
                'priId'         => "I NOTNULL ",
                'uniId'         => "I NOTNULL ",
                'tematica'      => "C(50)",          // Opcional. Indicació temàtica específica de la prioritat
                'pContacte'     => "C(120)",         // Persona de contacte
                'email'		=> "C(50)",	     // Correu electrònic de la persona de contacte
                'telContacte'   => "C(20)",          // Telefon/extensió persona de contacte
                'dispFormador'  => "L NOTNULL DEFAULT 0"    // La unitat organitzadora disposa de persones formadores  
        
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_unitatsImplicades_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_unitatsImplicades_column_def']);    
    
// SUBPRIORITATS *************************************************************************************************
    // subprioritats:  definició de taula
    //                 Recollirà llista de subprioritats
    $tables['cataleg_subprioritats'] = DBUtil::getLimitedTablename('cataleg_subprioritats');
    $tables['cataleg_subprioritats_column'] = array(
                'sprId'   => 'sprId',
                'priId'   => 'priId',  // Id de la prioritat mare
                'nom'     => 'nom',
                'nomCurt' => 'nomCurt',
                'ordre'   => 'ordre',  //a, b, c, ...
                'visible' => 'visible'
            );

    $tables['cataleg_subprioritats_column_def'] = array(
                'sprId'   => "I NOTNULL AUTO PRIMARY",
                'priId'   => "I NOTNULL",
                'nom'     => "X",
                'nomCurt' => "C(200)",
                'ordre'   => "C(4)",
                'visible' => "L DEFAULT 1"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_subprioritats_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_subprioritats_column_def']);
    
// ACTIVITATS *************************************************************************************************    
    // activitats: definició de taula
    //             Recollirà informació relativa a les activitats proposades
    $tables['cataleg_activitats'] = DBUtil::getLimitedTablename('cataleg_activitats');
    $tables['cataleg_activitats_column'] = array(
                'actId'         => 'actId',                 
                'priId'         => 'priId',                 // Id de prioritat
                'sprId'         => 'sprId',                 // Id de subprioritat
                'uniId'         => 'uniId',                 // id del grup propietari de la fitxa (unitat)
                'titol'         => 'titol',                 // Títol activitat
                'prioritaria'   => 'prioritaria',           // Indica si l'activitat està prioritzada pel Dept.
                'tGTAF'         => 'tGTAF',                 // Tipologia GTAF
                'destinataris'  => 'destinataris',          // serialització dels id's a qui s'adreça(taula auxiliar/mode = "d")
                'observacions'  => 'observacions',          // perfil de les persones destinatàries
                'curs'          => 'curs',                  // curs, taller, seminari...
                'presencialitat'=> 'presencialitat',        // presencial, semipresencial, no presencial
                'abast'         => 'abast',                 // al centre, diversos centres
                'hores'         => 'hores',                 // Hores totals activitat
                'objectius'     => 'objectius',             // serialització d'objectius
                'continguts'    => 'continguts',            // serialització de continguts
                'gestio'        => 'gestio',                // serialització de les opcions de gestió: ID taula auxiliar tipus "gest"
                'estat'         => 'estat',                 // 
                'ordre'         => 'ordre',                 // Per si es vol forçar alguna ordenació no alfabètica 
                'validador'     => 'validador',             // uid de la persona que valida la fitxa
                'dataVal'       => 'dataVal',               // Data de validació              
                'dataModif'     => 'dataModif',             // Data de modificació per publicitar
                'obs_validador' => 'obs_validador',         // Notes del validador a l'editor
                'obs_editor'    => 'obs_editor',            // Notes de l'editor al validador
                'centres'       => 'centres',               // Relació de centres (serialitzat)
                'info'          => 'info',                  // Observacions generals - visibles a la fitxa
                'activa'        => 'activa'
            );

    $tables['cataleg_activitats_column_def'] = array(
                'actId'         => "I NOTNULL AUTO PRIMARY", 
                'priId'         => "I",            
                'sprId'         => "I",             
                'uniId'         => "I",          // id del grup propietari de la fitxa (unitat)
                'titol'         => "C(255)", 
                'prioritaria'   => "L DEFAULT 0",
                'tGTAF'         => "C(4)",       // Tipologia GTAF
                'destinataris'  => "C(255)",     // serialització dels id's a qui s'adreça(taula destinataris)
                                                 //(taula destinataris) ID taula auxiliar tipus "dest"	
                'observacions'  => "C(255)",     // perfil de les persones destinatàries
                'curs'          => "I",          // ID taula auxiliar tipus:"curs" curs, taller, seminari...
                'presencialitat'=> "I",          // ID taula auxiliar tipus:"pres" presencial, semi, no presencial
                'abast'         => "I",          // ID taula auxiliar tipus:"abast" al centre, diversos centres
                'hores'		=> "I2 UNSIGNED",// Durada en hores de l'activitat
                'objectius'     => "X",          // serialització d'objectius
                'continguts'    => "X",          // serialització de continguts
                'gestio'        => "X",     // serialització de les opcions de gestió: ID taula auxiliar tipus "gest"
                                                 // tipus "gest" + gesId taula gestioActivitatDefaults
                'estat'         => "I1 DEFAULT 0",  // Estat de la fitxa codificat: 
						    // 0 -> esborrany;
						    // 1 -> enviada
						    // 2 -> cal revisar
						    // 3 -> validada
						    // 4 -> modificada
                                                    // 5 -> anul·lada
     		'ordre'   	=> "I2 UNSIGNED",// Per si es vol forçar alguna ordenació no alfabètica
                'validador'     => "I",          // uid de la persona que valida la fitxa
                'dataVal'       => "T",          // Datetime o timestamp de validació
                'dataModif'     => "T",          // Datetime de la validació de modificació per publicitar
		'obs_validador' => "X",          // Text per observacions de pers. validadora a editora
		'obs_editor'    => "X",          // Text per observacions de pers. editora a validadora 
                'centres'       => "X",
                'info'          => "X",          // Text per a observacions generals - camp ordinari de la fitxa
		'activa'        => "L DEFAULT 1"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_activitats_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_activitats_column_def']);

// ACTIVITATS ZONA *************************************************************************************************    
    // activitatsZona: definició de taula
    //            per a cada activitat recollir distribució territorial
    //            Relació amb la taula activitats pel camp actId 
    $tables['cataleg_activitatsZona'] = DBUtil::getLimitedTablename('cataleg_activitatsZona');
    $tables['cataleg_activitatsZona_column'] = array(
                'actId'   => 'actId',      // Id activitat relacionada
                'lloc'    => 'lloc',       // lloc o Id del lloc de realització
                'qtty'    => 'qtty',       // Quantitat d'activitats previstes
                'mesInici'=> 'mesInici'    // Mes d'inici de l'activitat                
            );

    $tables['cataleg_activitatsZona_column_def'] = array(
                'actId'   => "I NOTNULL PRIMARY",
                'lloc'    => "I PRIMARY",         // Id del lloc de realització (taula auxiliar: tipus "c"
                'qtty'    => "C(3)",                // Quantitat d'activitats previstes                
                'mesInici'=> "C(10)"              // Mes d'inici de l'activitat
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_activitatsZona_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_activitatsZona_column_def']);    
    
// UNITATS *************************************************************************************************    
    // unitats:  definició de taula
    //           Recollirà llista de subprioritats
    $tables['cataleg_unitats'] = DBUtil::getLimitedTablename('cataleg_unitats');
    $tables['cataleg_unitats_column'] = array(
                'uniId'      => 'uniId',
                'gzId'       => 'gzId',        // Identificador del grup Zikula ambpermís per gestionar aquesta unitat
                'catId'      => 'catId',
                'nom'        => 'nom',
                'descripcio' => 'descripcio',
                'activa'     => 'activa'
            );

    $tables['cataleg_unitats_column_def'] = array(
                'uniId'      => "I NOTNULL AUTO PRIMARY",
                'gzId'       => "I",
                'catId'      => "I",                        
                'nom'        => "C(255)",
                'descripcio' => "X",                
                'activa'     => "L DEFAULT 1"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_unitats_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_unitats_column_def']);
    

// RESPONSABLES *************************************************************************************************    
    // responsables: definició de taula
    //               recull informació de les persones responsable d'una unitat o servei
    // UNITATS <---- RESPONSABLES (camp relacionats: uniId, uniId)
    $tables['cataleg_responsables'] = DBUtil::getLimitedTablename('cataleg_responsables');
    $tables['cataleg_responsables_column'] = array(
                'respunitId'  => 'respunitId',   //Id de la taula  
                'uniId'       => 'uniId',       // Id de la unitat de la persona responsable
                'responsable' => 'responsable', // Nom i cognoms de la persona responsable
                'email'       => 'email',       // Correu electrònic de la pers. responsable
                'telefon'     => 'telefon'      // Telefon i extensió
            );

    $tables['cataleg_responsables_column_def'] = array(
                'respunitId'  => "I NOTNULL AUTO PRIMARY", //Afegit ala versió 1.0
                'uniId'       => "I NOTNULL",
                'responsable' => "C(120)",      // Nom i cognoms de la persona responsable
                'email'       => "C(50)",       // Correu electrònic de la pers. responsable
                'telefon'     => "C(20)"        // Telefon i extensió        
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_responsables_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_responsables_column_def']);    
    
// CONTACTES *************************************************************************************************    
    // contactes: definició de taula
    //               recull informació de les persones de contacte relacionades amb una activitat
    //               Fitxa: activitats
    //               
    // ACTIVITATS <---- CONTACTES (camp relacionats: actId, actId)
    $tables['cataleg_contactes'] = DBUtil::getLimitedTablename('cataleg_contactes');
    $tables['cataleg_contactes_column'] = array(
                'actId'       => 'actId',       // Id de la unitat de la persona responsable
                'pContacte'   => 'pContacte', // Nom i cognoms de la persona responsable
                'email'       => 'email',       // Correu electrònic de la pers. responsable
                'telefon'     => 'telefon'      // Telefon i extensió
            );

    $tables['cataleg_contactes_column_def'] = array(
                'actId'       => "I",
                'pContacte'   => "C(120)",      // Nom i cognoms de la persona responsable
                'email'       => "C(50)",       // Correu electrònic de la pers. responsable
                'telefon'     => "C(20)"        // Telefon i extensió        
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_contactes_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_contactes_column_def']);    
    
// AUXILIAR *************************************************************************************************    
    // auxiliar: definició de taula
    //           Contindrà informació ítul en llistats o desplegables agrupats per "tipus"
    //           tipus "dest": possibles distinatris de la formació (Infantil, Primària, CdA, ...)
    //		 tipus "abast": abast de la formació (al centre / diversos centres)
    //		 tipus "pres": modalitat de la formació (semi/presencial/no presencial)
    //		 tipus "curs": tipologia de la formació (curs, taller, seminari, jornada, ...)
    //           tipus "sstt": llistat de comarques (SSTT)
    //           tipus "gest": (gestió) Servei educatiu, Servei territorial, Unitat, PEEE, ...
    $tables['cataleg_auxiliar'] = DBUtil::getLimitedTablename('cataleg_auxiliar');
    $tables['cataleg_auxiliar_column'] = array(
                'auxId'   => 'auxId',
                'nom'     => 'nom',       
                'nomCurt' => 'nomCurt',
                'tipus'   => 'tipus',       // tipus "dest": possibles distinatris de la formació (Infantil, Primària, CdA, ...)
                                            // tipus "abast": abast de la formació (al centre / diversos centres)
                                            // tipus "pres": modalitat de la formació (semi/presencial/no presencial)
                                            // tipus "curs": tipologia de la formació (curs, taller, seminari, jornada, ...)
                                            // tipus "sstt": llistat de comarques (SSTT)
                                            // tipus "gest": (gestió) Servei educatiu, Servei territorial, Unitat, PEEE, ...
                'ordre'   => 'ordre',
                'visible' => 'visible'
                
            );

    $tables['cataleg_auxiliar_column_def'] = array(
                'auxId'   => "I NOTNULL AUTO PRIMARY",
                'nom'     => "C(50) NOTNULL",
                'nomCurt' => "C(10)",
                'tipus'   => "C(5)",
		'ordre'   => "I1 UNSIGNED DEFAULT 125",
                'visible' => "L DEFAULT 1"
        
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_auxiliar_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_auxiliar_column_def']);   

// GESTIÓ *************************************************************************************************    
    // gestio: definició de taula
    //         opcions per defecte i elements de gestió d'una activitat
    $tables['cataleg_gestioActivitatDefaults'] = DBUtil::getLimitedTablename('cataleg_gestioActivitatDefaults');
    $tables['cataleg_gestioActivitatDefaults_column'] = array(
                'gesId'    => 'gesId',
                'text'     => 'text',       //Cerca el lloc..., Difusió, Donar d'alta al GTAF, ...
                'opcions'  => 'opcions',    //Serialització dels elements a mostrar en la llista desplegable: 
                                            //ID taula auxiliar: tipus "gest" -> SE, ST, ...
                'opSE'     => 'opSE',       // Default per a servei educatiu Id
                'opST'     => 'opST',       // Default per a servei territorial Id
                'opUN'     => 'opUN',       // Default per a Unitat Id
                'ordre'    => 'ordre',      // Ordre per mostrar els diferents elements de gestió
                'visible'  => 'visible'                
            );

    $tables['cataleg_gestioActivitatDefaults_column_def'] = array(
                'gesId'    => "I NOTNULL AUTO PRIMARY",
                'text'     => "C(50)",      //Cerca el lloc..., Difusió, Donar d'alta al GTAF, ...
                'opcions'  => "C(255)",     //Serialització dels elements a mostrar en la llista desplegable: 
                                            //ID taula auxiliar: tipus "gest" -> SE, ST, ...
                'opSE'     => "I",          // Default per a servei educatiu Id
                'opST'     => "I",          // Default per a servei territorial Id
                'opUN'     => "I",          // Default per a Unitat Id
                'ordre'    => "I1 UNSIGNED",// (0 -  255) Ordre per mostrar els diferents elements de gestió
                'visible'  => "L DEFAULT 1"
        
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_gestioActivitat_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_gestioActivitat_column_def']);    
    
// CENTRESACTIVITAT *************************************************************************************************    
    // centresActivitat: definició de taula
    //         opcions per defecte i elements de gestió d'una activitat
    $tables['cataleg_centresActivitat'] = DBUtil::getLimitedTablename('cataleg_centresActivitat');
    $tables['cataleg_centresActivitat_column'] = array(
                'actId'    => 'actId',
                'centre'   => 'centre'        
            );

    $tables['cataleg_centresActivitat_column_def'] = array(
                'actId'    => "I NOTNULL PRIMARY",
                'centre'   => "C(8) NOTNULL PRIMARY"
            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_centresActivitat_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_centresActivitat_column_def']);    

// CENTRES *************************************************************************************************    
    // centres: definició de taula
    // importació de la informació del GTAF per tenir la informació dels centres de Catalunya
    $tables['cataleg_centres'] = DBUtil::getLimitedTablename('cataleg_centres');
    $tables['cataleg_centres_column'] = array(
        'CODI_ENTITAT'      => 'CODI_ENTITAT',
        'CODI_TIPUS_ENTITAT'=> 'CODI_TIPUS_ENTITAT',
	'NOM_ENTITAT'       => 'NOM_ENTITAT',
	'NOM_LOCALITAT'     => 'NOM_LOCALITAT',
	'NOM_DT'            => 'NOM_DT',
	'CODI_DT'           => 'CODI_DT',
	'NOM_TIPUS_ENTITAT' => 'NOM_TIPUS_ENTITAT'
     );

    $tables['cataleg_centres_column_def'] = array(
        'CODI_ENTITAT'      => "C(8) NOTNULL PRIMARY",
        'CODI_TIPUS_ENTITAT'=> "C(5)",
	'NOM_ENTITAT'       => "C(50)",
	'NOM_LOCALITAT'     => "C(50)",
	'NOM_DT'            => "C(50)",
	'CODI_DT'           => "C(1)",
	'NOM_TIPUS_ENTITAT' => "C(30)"
    );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_centres_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_centres_column_def']);    

// importTaules *************************************************************************************************    
    // Recull les correspondències definides entre catàlegs per a poder importar activitats
    // 
    $tables['cataleg_importTaules'] = DBUtil::getLimitedTablename('cataleg_importTaules');
    $tables['cataleg_importTaules_column'] = array(
        'importId'  => 'importId',
        'catIdOri'  => 'catIdOri',
	'catIdDest' => 'catIdDest'
	            );

    $tables['cataleg_importTaules_column_def'] = array(
        'importId'  => "I NOTNULL AUTO PRIMARY", //index, es fa servir com a referència a cataleg_importAssign
        'catIdOri'  => "I", //catId del Catàleg origen de la importació
	'catIdDest' => "I" //catId del Catàleg destinació de la importació
		            );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_importTaules_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_importTaules_column_def']);
    $tables['cataleg_importTaules_column_idx'] = array ('catsId' => array('columns' => array('catIdOri', 'catIdDest'),'options' => array('UNIQUE' => true)));
// Taula cataleg_importAssign *************************************************************************************************    
    // Recull les relacions entre prioritat-subprioritat d'un catàleg i un altre
    // Permet fer l'assignació de valors en importar activitats entre dos catàlegs
    $tables['cataleg_importAssign'] = DBUtil::getLimitedTablename('cataleg_importAssign');
    $tables['cataleg_importAssign_column'] = array(
        'importId' => 'importId',
        'idsOri'   => 'idsOri',
	'idsDest'  => 'idsDest'
     );

    $tables['cataleg_importAssign_column_def'] = array(
        'importId' => "I NOTNULL", // index de la taula cataleg_importTaules on s'estableix la correspondència
        'idsOri'   => "C(25) NOTNULL PRIMARY", //priId$$sprId del catàleg origen
	'idsDest'  => "C(25) NOTNULL PRIMARY" //priId$$sprId del catàleg destinació
    );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['cataleg_importAssign_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['cataleg_importAssign_column_def']);    

// Taula cataleg_gtafEntities *************************************************************************************************
    // Recull les entitats de gtaf utilitzaces per a l'assignació dels usuaris
    // Corresponen a unitats (ex-> 0552), st (ex-> 7) i crp (ex-> 702)
    // El camp tipus podrà ser UNI, ST o SE
    $tables['cataleg_gtafEntities'] = DBUtil::getLimitedTablename('cataleg_gtafEntities');
    $tables['cataleg_gtafEntities_column'] = array(
        'gtafEntityId'  => 'gtafEntityId',
        'nom'           => 'nom',
        'tipus'         => 'tipus',
        'gtafGroupId'   => 'gtafGroupId'
    );
    $tables['cataleg_gtafEntities_column_def'] = array(
        'gtafEntityId'  => 'C(5) NOTNULL PRIMARY',
        'nom'           => 'C(100) NOTNULL',
        'tipus'         => 'C(5) NOTNULL',
        'gtafGroupId'   => 'C(5) NOTNULL'
    );
// Taula cataleg_gtafGroups ***************************************************************************************************
    // Recull les agrupacions d'enitats de gtaf amb la seva dependència orgànica
    // Utilitzarem els mateixos codis que té la seva entitat (gtafEnitityId) principal
    $tables['cataleg_gtafGroups'] = DBUtil::getLimitedTablename('cataleg_gtafGroups');
    $tables['cataleg_gtafGroups_column'] = array(
        'gtafGroupId'   => 'gtafGroupId',
        'nom'           => 'nom',
        'resp_uid'      => 'resp_uid'
    );
    $tables['cataleg_gtafGroups_column_def'] = array(
        'gtafGroupId'   => 'C(5) NOTNULL PRIMARY',
        'nom'           => 'C(100) NOTNULL',
        'resp_uid'      => 'I'
    );

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
        'code'        => 'iw_code'
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
        'code'      => "C(5)"
    );

    //Returns informació de les taules
    return $tables;
}

/* Informació de les variables de mòdul:
 * modname = Cataleg
 * 
 *  name = actiu
 *      value = catId del catàleg actiu // ex s:1:"5";
 * 
 *  name = treball
 *      value = catId del catàleg de treball (el que mostrarà el view per defecte)
 * 
 *  name = grupsUnitats
 *      value = relació de gid del grups de zikula que són unitats del catàleg // ex a:18:{i:0;i:12;i:1;i:13;i:2;i:14;i:3;i:15;i:4;i:16;i:5;i:17;i:6;i:18;i:7;i:19;i:8;i:20;i:9;i:21;i:10;i:22;i:11;i:23;i:12;i:24;i:13;i:25;i:14;i:26;i:15;i:27;i:16;i:28;i:17;i:29;}
 *
 *  name = grupsZikula
 *      value = array amb indexs 'Sirius','ExSirus','Personals','Generics','LectorsCat','EditorsCat','Gestors','Gestform','Uni','ST','SE','Odissea','Cert','gA','gB' i com a valors els corresponents gid
 * //nota: El grup AdminGest no es gestiona des del mòdul catàleg
 * 
 *  name = novetats (array)
 *              dataPublicacio (date en format yyyy-mm-dd). Indica data de publicació del catàleg
 *              diesNovetats (int). Dies que una activitat s'ha de mostrar com a novetat o modificada
 *              showNew (boolean). Si cert,  es mostren les novetats al bloc. En cas contari no es mostren
 *              showMod (boolean). Si cert,  es mostren les activitats modificades al bloc. En cas contari no es mostren
 * 
 */

