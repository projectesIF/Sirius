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
class Cataleg_Api_User extends Zikula_AbstractApi {

    /** 
     * Obtenir enllaços per filtar activitats, segons el seu estat, en la vista de "Les meves activitats".\n
     * Aquests enllaços s'utilitzaran per crear un menú emergent per aplicar el filtre escollit    
     *
     * @param array $args Array amb els paràmetres de la funció
     * 
     * ### Paràmetres de l'array $args
     * * integer **id** Identificador del catàleg
     * 
     * @return array $links array d'enllaços'URLs per aplicar els filtres possibles
     */
    public function getFilterlinks($args) {

        $catId = isset($args['id']) ? $args['id'] : null;
        $links = array();
        // Equivalències en text dels estats numèrics de les activitast
        $estats = ModUtil::apiFunc($this->name, 'user', 'getEstatsActivitat');
        if ($catId && SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url('Cataleg', 'user', 'view', array('catId' => $catId, 'filter' => -1)), 'text' => $this->__('Tots els estats'), 'class' => 'z-icon-es-view');
            $links[] = array('url' => ModUtil::url('Cataleg', 'user', 'view', array('catId' => $catId, 'filter' => Cataleg_Constant::ESBORRANY)), 'text' => $estats[Cataleg_Constant::ESBORRANY], 'class' => 'z-icon-es-new');
            $links[] = array('url' => ModUtil::url('Cataleg', 'user', 'view', array('catId' => $catId, 'filter' => Cataleg_Constant::PER_REVISAR)), 'text' => $estats[Cataleg_Constant::PER_REVISAR], 'class' => 'z-icon-es-new');
            $links[] = array('url' => ModUtil::url('Cataleg', 'user', 'view', array('catId' => $catId, 'filter' => Cataleg_Constant::ENVIADA)), 'text' => $estats[Cataleg_Constant::ENVIADA], 'class' => 'z-icon-es-help');
            $links[] = array('url' => ModUtil::url('Cataleg', 'user', 'view', array('catId' => $catId, 'filter' => Cataleg_Constant::VALIDADA)), 'text' => $estats[Cataleg_Constant::VALIDADA], 'class' => 'z-icon-es-config');
            $links[] = array('url' => ModUtil::url('Cataleg', 'user', 'view', array('catId' => $catId, 'filter' => Cataleg_Constant::MODIFICADA)), 'text' => $estats[Cataleg_Constant::MODIFICADA], 'class' => 'z-icon-es-new');
            $links[] = array('url' => ModUtil::url('Cataleg', 'user', 'view', array('catId' => $catId, 'filter' => Cataleg_Constant::ANULLADA)), 'text' => $estats[Cataleg_Constant::ANULLADA], 'class' => 'z-icon-es-help');
        }

        return $links;
    }


    /**
     * Generar url per establir la pàgina de retorn
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **catId** Identificador de catàleg
     * * string **o** Posibles valors "view" o "acts". El primer indica que es tornarà a "Les meves activitat" i el segon al llistat general de les activitats del catàleg
     * 
     * @return string $url URL amb la funció a executar segons el paràmetre $args['o']
     */
    public function genUrl($args) {
        $url = null;
        if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            $catId = isset($args['catId']) ? $args['catId'] : null;
            $origin = isset($args['o']) ? $args['o'] : null;
            switch ($origin) {
                case 'view':
                    $url = ModUtil::url($this->name, 'user', 'view', array('catId' => $catId));
                    break;
                case 'acts':
                    $priId = isset($args['priId']) ? $args['priId'] : null;
                    if ($priId) {
                        $url = ModUtil::url($this->name, 'user', 'activitats', array('priId' => $priId));
                    }
                    break;
            }
        }
        return $url;
    }

    /**
     * Actualitza les orientacions d'una prioritat       
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **priId** Identificador de prioritat
     * 
     * @return integer Retorn de updateObject o boolean **false** si no ha reeixit
     */
    public function updateOri($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }
        $priId = isset($args['priId']) ? $args['priId'] : null;
        if ($priId) {
            $where = "priId=" . $priId;
            return DBUtil::updateObject($args, 'cataleg_prioritats', $where);
        } else
            return false;
    }

    /**
     * Comprovar si una activitat ha estat validada o no    
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **actId** Identificador de l'activitat
     * 
     * @return Data de validació o null si l'activitat no ha estat validada
     */
    public function isValidated($actId) {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        if ($actId)
            return DBUtil::selectField('cataleg_activitats', 'dataVal', 'actId = ' . $actId);
        else
            return LogUtil::registerError($this->__('S\'ha validat directament una nova activitat.'));
    }

    /** 
     *  Obtenir tota la informació relativa a una activitat
     * 
     * @param integer actId Identificador de l'activitat)
     * 
     * @return array $item Array amb la informació de l'activitat actId
     */ 
     
     /*  
      [actId] integer id de l'activitat
      [cataleg] => Array amb informació del catàleg
      [catId] integer id del catàleg
      [anyAcad] string any acadèmic del catàleg
      [nom] string nom del catàleg
      [estat] integer (0: TANCAT | 1: LES_MEVES | 2: ORIENTACIONS | 3: ACTIVITATS | 4: OBERT)
      [editable] boolean (1: editable | 0: no editable)

      [eix] => Array amb informació de l'eix
      [eixId] integer id de l'eix
      [catId] integer id del catàleg al qual pertany l'eix
      [nom] string nom descriptiu de l'eix
      [nomCurt]string nom descriptiu de l'eix (text reduït)
      [descripcio] string text descriptiu de l'eix
      [ordre] integer ordre entre els eixos
      [visible] boolean (1 visible | 0 ocult)

      [pri] => Array amb informació de la prioritat
      [priId] integer id de la prioritat
      [eixId] integer id de l'eix al qual pertany la prioritat
      [nom] string nom descriptiu de la prioritat
      [nomCurt] string nom descriptiu de la prioritat (text reduït)
      [orientacions] string descripció de les orientacions relatives a la prioritat
      [recursos] string enllaços a recursos i/o materials relacionats amb la prioritat i les orientacions
      [ordre] integer ordre de la prioritat
      [visible] boolean (1 visible | 0 oculta)

      [eix] => Array informació relativa a l'eix de pertinença de la prioritat
      [eixId] integer id de l'eix
      [catId] integer id del catàleg al qual pertany l'eix
      [nom] string nom descriptiu de l'eix
      [nomCurt]string nom descriptiu de l'eix (text reduït)
      [descripcio] string text descriptiu de l'eix
      [ordre] integer ordre entre els eixos
      [visible] boolean (1 visible | 0 ocult)

      [cataleg] => Array amb informació del catàleg
      [catId] integer id del catàleg
      [anyAcad] string any acadèmic del catàleg
      [nom] string nom del catàleg
      [estat] integer (0: TANCAT | 1: LES_MEVES | 2: ORIENTACIONS | 3: ACTIVITATS | 4: OBERT)
      [editable] boolean (1: editable | 0: no editable)

      [spr] => Array amb informació de la subprioritat associada a l'activitat
      [sprId] integer id de la subprioritat
      [priId] integer id de la prioritat
      [nom] string descriptiu de la subprioritat
      [nomCurt] string descriptiu de la subprioritat (text reduït)
      [ordre] string (a, b, c ...) ordre que ocupa dins el grup de subprioritats de la prioritat a la qual pertany
      [visible] boolean (1 visible | 0 ocult)

      [priId] integer id de la prioritat a la que està associada l'activitat
      [sprId] integer id de la subprioritat a la que està associada l'activitat (pot ser null)
      [uniId] integer id de la unitat responsable de l'activitat
      [titol] string títol de l'activitat
      [prioritaria] boolean (1: l'activitat està prioritzada per Departament d'E. | 0: no ho està)
      [tGTAF] string codi GTAF de l'activitat (pot no tenir-ne)
      [destinataris] string (serialitzat) Professorat destinatari de l'activitat segons nivell educatiu
      [observacions] string observacions sobre els destinataris de la formació
      [curs] integer id de la tipologia de formació
      [presencialitat] integer id de la tipologia del tipus de formació segons la presencialitat requerida
      [abast] integer id del tipus d'abast (formació en centre o en diversos centres)
      [hores] integer durada en hores de l'activitat
      [objectius] => Array string Text dels objectius de l'activitat (màxim 5)
      [1] string descripció d'un dels objectius de l'activitat
      [2] ...

      [continguts] => Array string Text dels continguts a impartir a l'activitat (màxim 10)
      [1] string descripció d'un dels continguts de l'activitat
      [2] ...

      [gestio] => Array recull, per a cadascun dels aspectes organitzatius de l'activitat, qui s'encarrega
      [1] => Array
      [txt] integer id del text a mostrar: element a organitzar
      [srv] integer id del servei a mostrar: servei encarregat

      [2] => Array
      ...

      [estat] integer estat de l'activitat (0: ESBORRANY; 1: ENVIADA; 2: CAL_REVISAR; 3: VALIDADA; 4: ANUL·LADA)
      [ordre] integer (actualment sense ús)
      [validador] id de l'usuari ha validat l'activitat
      [dataVal] datetime data i hora de la validació
      [dataModif] datetime data i hora de la modificació (només per activitats modificades que es volen mostrar al bloc de novetats)
      [obs_validador] string observacions de la persona editora cap a les gestores
      [obs_editor] string observacions de gestors cap a editors de l'activitat
      [centres] string observacions adreçades als centres destinataris de la formació
      [info] string observacions generals de l'activitat
      [activa] boolean
      [cr_date] datetime data i hora de la creació del registre
      [cr_uid] integer id de l'usuari creador
      [lu_date] datetime data i hora de la darrera actualització del registre
      [lu_uid] integer id de l'usuari de la darrera actualització del registre
      [centresAct] => string codis de centre a qui s'adreça l'activitat separats per comes (,) Pot estar buida
      [creador] string nom i cognoms de l'usuari creador de l'activitat
      [modificador] string nom i cognoms del darrer usuari que ha introduit modificacions a l'activitat
      [contactes] => Array d'arrays amb informació de les persones de contacte de l'activitat
      [0] => Array
      [actId] => 161
      [pContacte] string nom i cognoms de la persona de contacte
      [email] string correu electrònic de la persona de contacte
      [telefon] string telèfon de la persona de contacte
      [1] => Array
      ...

      [activitatsZona] => Array d'arrays amb informació sobre activitats a realitzar per zones o SSTT. Una entrada per a cada zona
      [x] => Array l'ìndex associatiu x és l'identificador del lloc de realització = [lloc]
      [actId] integer id de l'activitat
      [lloc] integer id del lloc de realització (SSTT o Centralitzada)
      [qtty] integer quantitat d'activitats previstes a la zona
      [mesInici] integer mes d'inici previst de les activitats a la zona
      [y] ...
     */
    public function getActivitat($id) {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $item = array();
        if ($id && is_numeric($id)) {
            // Seleccionar la info de l'activitat
            $activitat = DBUtil::selectObject('cataleg_activitats', 'actId=' . $id);
            // Obtenir dades relacionades de taula activitatsZona
            $actZona = DBUtil::selectObjectArray('cataleg_activitatsZona', 'actId=' . $id);
            // Canviar l'índex de l'array per reonstruir el formulari
            $az = array();
            foreach ($actZona as $a) {
                $az[$a['lloc']] = $a;
            }
            $item['activitatsZona'] = $az;
            // Obtenir dades de les persones de contacte
            $contactes = DBUtil::selectObjectArray('cataleg_contactes', 'actId=' . $id, 'ORDER BY pContacte');
            // Obtenir dades dels centres que faran l'activitat
            $cent = DBUtil::selectObjectArray('cataleg_centresActivitat', 'actId =' . $id);
            // Provisionalment ho farem així --------------------------------------------
            $ac = array();
            foreach ($cent as $c) {
                $ac[] = $c['centre'];
            }
            //$cent = unserialize($activitat['centres']);
            if (count($ac)) {
                $centres = implode(',', $ac);
                $activitat['centresAct'] = $centres;
            } else {
                $activitat['centresAct'] = "";
            }
            // --------------------------------------------------------------------------
            // Obtenir informació de catàleg, eix i prioritat
            $info = ModUtil::apiFunc('Cataleg', 'user', 'getAuxiliarInfo', $id);
            // Processar els elements serialitzats per convertir-los en arrays.
            $dest = unserialize($activitat['destinataris']);
            $activitat['destinataris'] = $dest;
            $obj = unserialize($activitat['objectius']);
            $activitat['objectius'] = $obj;
            $cont = unserialize($activitat['continguts']);
            $activitat['continguts'] = $cont;
            $gest = unserialize($activitat['gestio']);
            $activitat['gestio'] = $gest;

            // Obtenir noms i cognoms de la persona validadora a partir del seu uid
            if (!is_null($activitat['validador'])) {
                $where = "WHERE iw_uid=" . $activitat['validador'];
                $usrVal = DBUtil::selectObject('IWusers', $where, array('nom', 'cognom1', 'cognom2'));
                $activitat['validador'] = $usrVal['nom'] . " " . $usrVal['cognom1'] . " " . $usrVal['cognom2'];
            } else
                $activitat['validador'] = "";
            //Obtenir noms i cognoms de la persona que va crear l'activitat
            if (!is_null($activitat['cr_uid'])) {
                $where = "WHERE iw_uid=" . $activitat['cr_uid'];
                $usrCrea = DBUtil::selectObject('IWusers', $where, array('nom', 'cognom1', 'cognom2'));
                $activitat['creador'] = $usrCrea['nom'] . " " . $usrCrea['cognom1'] . " " . $usrCrea['cognom2'];
            } else
                $activitat['cr_uid'] = "";
            //Obtenir noms i cognoms de la darrera persona que va modificar l'activitat
            if (!is_null($activitat['lu_uid'])) {
                $where = "WHERE iw_uid=" . $activitat['lu_uid'];
                $usrMod = DBUtil::selectObject('IWusers', $where, array('nom', 'cognom1', 'cognom2'));
                $activitat['modificador'] = $usrMod['nom'] . " " . $usrMod['cognom1'] . " " . $usrMod['cognom2'];
            } else
                $activitat['lu_uid'] = "";
            // Canvi format dates            
            $activitat['lu_date'] = date('d/m/Y H:i:s', strtotime($activitat['lu_date']));
            $activitat['cr_date'] = date('d/m/Y H:i:s', strtotime($activitat['cr_date']));
            $activitat['dataVal'] = date('d/m/Y H:i:s', strtotime($activitat['dataVal']));

            $item = array_merge($item, $info);
            $item = array_merge($item, $activitat);
            $item['contactes'] = $contactes;

            return $item;
        } else {
            LogUtil::registerArgsError();
            return LogUtil::registerError($this->__('No s\'han pogut carregar les dades de l\'activitat.'));
        }
    }

    /**
     * Obtenir informació d'una activitat però només d'aquells camps especificats a "fields"
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **fields** Llista de camps dels quals es vol incloure informació
     * * integer **actId** Identificador de l'activitat
     * 
     * @return array amb la informació sol·licitada
     */
    public function getActivitatDigest($args) {
        // Verifiquem que té permissos d'addició
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $fields = isset($args['fields']) ? $args['fields'] : null;
        $actId = isset($args['actId']) ? $args['actId'] : null;

        return DBUtil::selectObject('cataleg_activitats', 'actId = ' . $actId, $fields);
    }

    /**
     * Comprova que els codis de centre introduïts existeixin realment
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **centres** Cadena amb els codis de centre separats per comes
     * 
     * @return array amb camps [**codis**], array[**exist**] i array[**no_exist**]. L'array[exist] amb informació dels centres existents i aray[no_exist] amb els codis erronis.\n
     * El camp "codis" conté la llista, separada per comes, dels codis de centre vàlids 
     */
    public function checkCentres($args) {
        // Verifiquem que té permissos d'addició
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $centres = isset($args['centres']) ? $args['centres'] : null;
        $aux = explode(',', $centres);
        $arr_centres = array_filter($aux);
        $valids = "";
        $result = array();
        if ($arr_centres) {
            // per a cada codi de centre verificar l'existència
            foreach ($arr_centres as $centre) {
                $info = ModUtil::apiFunc($this->name, 'user', 'centre', $centre);
                if ($info) {
                    $result['exist'][] = $info;
                    $valids.= $valids == "" ? $info['CODI_ENTITAT'] : "," . $info['CODI_ENTITAT'];
                } else {
                    $result['no_exist'][] = $centre;
                }
            }
        }
        $result['codis'] = $valids;
        return $result;
    }

    /**
     *  Afegeix una nova activitat a la taula cataleg_activitats
     * 
     * > S'afegeixen també els registres associats a 'cataleg_contactes', 'cataleg_activitatsZona' i 'centresActivitat'
     * 
     * @param  array $activitat Conté totes les dades de l'activitat
     * 
     * @return integer Id de la nova activitat o boolean false en cas d'error
     */
    public function addActivitat($activitat) {
        // Verifiquem que té permissos d'addició
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD));

        // Obtenim l'array amb les dades de l'activitat
        $item = isset($activitat) ? $activitat : null;
        $result = array();
        $success = false;
        if (!is_null($item)) {
            $rs0 = DBUtil::insertObject($item, 'cataleg_activitats', 'actId');
            if ($rs0) {
                $result['act'] = DBUtil::getInsertID('cataleg_activitats', 'actId');
                $actsZona = array();
                $contactes = array();
                $centres = array();
                // Afegim actId a l'array d'activitas-zona
                if (!is_null($item['actsPerZona'])) {
                    foreach ($item['actsPerZona'] as $az) {
                        $az['actId'] = $result['act'];
                        $actsZona[] = $az;
                    }
                }
                // Afegim actId a l'array de contactes de l'activitat
                if (!is_null($item['contactes'])) {
                    foreach ($item['contactes'] as $ctte) {
                        // Si no és un fila buida -> es guarda el contacte
                        if (!((strlen($ctte['pContacte']) + strlen($ctte['email']) + strlen($ctte['telefon'])) == 0)) {
                            $ctte['actId'] = $item['actId'];
                            $contactes[] = $ctte;
                        }
                    }
                }
                // Afegim actId a l'array de centres de realització de l'activitat
                if (count($item['llocs']) > 0) {
                    foreach ($item['llocs'] as $lloc) {
                        $aux = array();
                        $aux['actId'] = $result['act'];
                        $aux['centre'] = $lloc;
                        if (!in_array($aux, $centres))
                            $centres[] = $aux;
                    }
                    $rs3 = DBUtil::insertObjectArray($centres, 'cataleg_centresActivitat');
                } else
                    $rs3 = true; // No hi ha informació sobre centres
                    
                // Afegim els registres corresponents a les taules relacionades
                $rs1 = DBUtil::insertObjectArray($actsZona, 'cataleg_activitatsZona');
                $rs2 = DBUtil::insertObjectArray($contactes, 'cataleg_contactes');
            }
            $success = (!is_null($rs0) && !is_null($rs1) && !is_null($rs2) && !is_null($rs3));
        }
        return $success;
    }

    /**
     *  Actualitza una activitat existent a la taula cataleg_activitats
     * 
     * > S'actualitzen igualment els registres associats a 'cataleg_contactes',\n
     * > 'cataleg_activitatsZona' i 'centresActivitat'
     * 
     * @param  array $activitat Conté totes les dades de l'activitat
     * 
     * @return integer Id de la nova activitat || boolean false en cas d'error
     */
    public function updateActivitat($activitat) {
        // Verifiquem que té permissos d'addició
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD));

        // Obtenim l'array amb les dades de l'activitat
        $item = isset($activitat) ? $activitat : null;
        $where = "WHERE actId = " . $item['actId'];

        $result = array();
        $success = false;
        if (!is_null($item)) {
            //DBUTil::updateObject ($obj, 'customers', $where);
            $rs0 = DBUtil::updateObject($item, 'cataleg_activitats', $where);
            if ($rs0) {
                $actsZona = array();
                $contactes = array();
                $centres = array();
                // Afegim actId a l'array d'activitas-zona
                if (!is_null($item['actsPerZona'])) {
                    foreach ($item['actsPerZona'] as $az) {
                        $az['actId'] = $item['actId'];
                        $actsZona[] = $az;
                    }
                }
                // Afegim actId a l'array de contactes de l'activitat
                if (!is_null($item['contactes'])) {
                    foreach ($item['contactes'] as $ctte) {
                        // Si no és un fila buida -> es guarda el contacte
                        if (!((strlen($ctte['pContacte']) + strlen($ctte['email']) + strlen($ctte['telefon'])) == 0)) {
                            $ctte['actId'] = $item['actId'];
                            $contactes[] = $ctte;
                        }
                    }
                }
                // Afegim actId a l'array de centres de realització de l'activitat
                $delete = DBUtil::deleteWhere('cataleg_centresActivitat', $where);
                if (!is_null($item['llocs'])) {
                    //if (count($item['llocs'])>0) {
                    foreach ($item['llocs'] as $lloc) {
                        $aux = array();
                        $aux['actId'] = $item['actId'];
                        $aux['centre'] = $lloc;
                        if (!in_array($aux, $centres))
                            $centres[] = $aux;
                    }
                    // Esborrem tots els registres corresponents a l'activitat
                    if ($delete) {
                        // Afegim els nous registres
                        $rs3 = DBUtil::insertObjectArray($centres, 'cataleg_centresActivitat');
                    }
                }
                if (DBUtil::deleteWhere('cataleg_activitatsZona', $where))
                    $rs1 = DBUtil::insertObjectArray($actsZona, 'cataleg_activitatsZona');
                if (DBUtil::deleteWhere('cataleg_contactes', $where))
                    $rs2 = DBUtil::insertObjectArray($contactes, 'cataleg_contactes');
            }
            $success = !is_null($rs0) && !is_null($rs1) && !is_null($rs2) && !is_null($delete);
        }
        return $success;
    }

    /**
     *  Esborra un element indicant el tipus i la seva id
     * 
     * > També s'esborren els registres associats d'altres taules.
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **que** Indica allò que volem esborrar: activitat, unitat, catàleg, orientació, ...
     * * integer **id** Identificador de l'element a esborrar
     * 
     * @return boolean true si tot ha anat bé || false en cas d'error
     */
    public function delete($args) {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_DELETE));

        $que = $args['que'] ? $args['que'] : null;
        $id = $args['id'] ? $args['id'] : null;


        if (isset($id)) {
            switch ($que) {
                case 'activitat':
                    $where = 'actId =' . $id;
                    //return LogUtil::registerError($id." -".$que." - WHERE: ". $where);         
                    // Esborrem de la taula activitats

                    if ((DBUtil::deleteWhere('cataleg_activitats', $where)) &&
                            (DBUtil::deleteWhere('cataleg_contactes', $where)) &&
                            (DBUtil::deleteWhere('cataleg_activitatsZona', $where)) &&
                            (DBUtil::deleteWhere('cataleg_centresActivitat', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'cataleg':
                    $where = 'catId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'eix':
                    $where = 'eixId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_eixos', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'subprioritat':
                    $where = 'sprId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_subprioritats', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'impunit':
                    $where = 'impunitId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_unitatsImplicades', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'unitat':
                    $where = 'uniId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_unitats', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'responsable':
                    $where = 'respunitId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_responsables', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'allResponsablesUnitat':
                    $where = 'uniId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_responsables', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'prioritat':
                    $where = 'priId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_prioritats', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'allSubprioritatsPrioritat':
                    $where = 'priId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_subprioritats', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'allImpunitsPrioritat':
                    $where = 'priId =' . $id;
                    if ((DBUtil::deleteWhere('cataleg_unitatsImplicades', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'gtafEntity':
                    $where = "gtafEntityId ='" . $id ."'";
                    if ((DBUtil::deleteWhere('cataleg_gtafEntities', $where)))
                        return true;
                    else
                        return false;
                    break;
                case 'gtafGroup':
                    $where = "gtafGroupId ='" . $id ."'";
                    if ((DBUtil::deleteWhere('cataleg_gtafGroups', $where)))
                        return true;
                    else
                        return false;
                    break;
            }
        }
        return true;
    }

    /**
     *  Determina a quin catàleg pertany un determinat element: prioritat, activitat, unitat
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * string **id** Id tipus d'identificador (eixId, priId, actId, uniId)
     * integer **value** Valor de l'identificador
     * 
     * @return integer Id del catàleg || null Si no l'ha trobat
     */
    public function getParent($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        // Obtenció de paràmetres
        $id = isset($args['id']) ? $args['id'] : null; // Tipus d'identificador que es passa
        $value = isset($args['value']) ? $args['value'] : null; // valor de l'identificador

        switch ($id) {

            case 'eixId':
                $sql = "SELECT `catId` FROM `cataleg_eixos` WHERE `eixId` = $value";
                //$info = ModUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $value));
                //return $info['catId'];
                break;
            case 'priId':
                $sql = "SELECT cataleg_eixos.catId AS catId FROM cataleg_prioritats " .
                        "LEFT JOIN cataleg_eixos ON (cataleg_prioritats.eixId = cataleg_eixos.eixId) " .
                        "WHERE cataleg_prioritats.priId = $value";
                break;
            case 'sprId':
                $sql = "SELECT cataleg_eixos.catId AS catId FROM cataleg_eixos" .
                        "LEFT JOIN cataleg_prioritats ON cataleg_eixos.eixId = cataleg_prioritats.eixId" .
                        "LEFT JOIN cataleg_subprioritats ON cataleg_prioritats.priId = cataleg_subprioritats.priId" .
                        "WHERE  cataleg_subprioritats.sprId = $value";
                break;
            case 'actId':
                $sql = "SELECT cataleg_unitats.catId AS catId FROM cataleg_unitats " .
                        "LEFT JOIN cataleg_activitats ON (cataleg_unitats.uniId = cataleg_activitats.uniId) " .
                        "WHERE cataleg_activitats.actId = $value";

                break;
            case 'uniId':
                $sql = "SELECT catId FROM cataleg_unitats WHERE uniId =" . $value;
                break;
        }

        $result = DBUtil::executeSQL($sql);
        $cataleg = DBUtil::marshallObjects($result);

        return $cataleg[0]['catId'];
    }

    /**
     * Obtenir tota la informació d'un catàleg
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **catId** [opcional] Identificador del catàleg
     * 
     * @return array Tota la informació relativa al catàleg: dades grals, orientacions, línies prioritàries, activitats, ...
     */
    public function get($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $catId = isset($args['catId']) ? $args['catId'] : null;

        if ($catId) {
            if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD)) {
                // te permisos d'edició o +"; 
                $where = "catId = " . $catId;
            } else {
                // L'usuari és Lector -> Només mostar catàleg si és public
                $where = "estat = " . Cataleg_Constant::OBERT . " AND catId = " . $catId;
            }

            $cat = array();
            $cat = DBUtil::selectObject('cataleg', $where);

            if (!is_null($cat)) {

                $cat['eixos'] = ModUtil::apiFunc('Cataleg', 'user', 'getAllEixos', array('catId' => $cat['catId']));
                $gestor = ModUtil::apiFunc('Cataleg', 'user', 'isUserGestor', array('uid' => UserUtil::getVar('uid')));
                $prioritats = array();
                foreach ($cat['eixos'] as $eix) {
                    $items = ModUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsEix', array('eixId' => $eix['eixId']));

                    foreach ($items as $key => $item) {
                        $n = ModUtil::apiFunc('Cataleg', 'user', 'countActivitatsPrioritat', array('priId' => $item['priId']));
                        $options = array();
                        if ($n > 0) {
                            if (($cat['estat'] >= Cataleg_Constant::ACTIVITATS) || $gestor) {
                                $options[] = array('url' => ModUtil::url('Cataleg', 'user', 'activitats', array('catId' => $cat['catId'],
                                        'priId' => $item['priId'])),
                                    'image' => 'activitats_xs.png',
                                    'title' => $this->__('Activitats'));
                                $item['prioritatsUrl'] = array('url' => ModUtil::url('Cataleg', 'user', 'activitats', array('catId' => $cat['catId'], 'priId' => $item['priId'])),
                                    'title' => $this->__('Cliqueu per veure les activitats relacionades amb aquesta prioritat'));
                            }
                        }
                        if (($cat['estat'] >= Cataleg_Constant::ORIENTACIONS) || $gestor) {
                            $options[] = array('url' => ModUtil::url('Cataleg', 'user', 'display', array('catId' => $cat['catId'], 'priId' => $item['priId'])),
                                'image' => 'orientacions_xs.png',
                                'title' => $this->__('Orientacions'));

                            $item['options'] = $options;
                        }
                        $prioritats[] = $item;
                    }
                }
                $cat['prioritats'] = $prioritats;
            }
        }
        // retornem totes les dades del catàleg
        return $cat;
    }

    /**
     * Obtenir l'identificador del catàleg actiu
     * 
     * @return integer $catIdAc Identificador del catàleg actiu
     */
    public function getActiveCataleg() {
        // Verificació de seguretat
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        $catIdAc = ModUtil::getVar("Cataleg", "actiu", -1);
        return $catIdAc;
    }

    /**
     *  Obté un llistat d'activitats considerades com a novetats per mostrar-les 
     *  al bloc "Novetats" del catàleg. 
     * 
     * > Es consideren novetats tant les validades recentment com les modificades marcades per informar dels canvis introduïts.
     * 
     * @return array Conté dos arrays (showNew i showMod) amb les activitats a mostrar al bloc de "novetats del catàleg"
     */
    public function getNovetats() {
        // Verificació de seguretat
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        $novetats = ModUtil::getVar($this->name, 'novetats');
        $result = array();
        $result['novetats'] = array();
        $result['canvis'] = array();
        if (isset($novetats['dataPublicacio'])) {
            // Hi ha una catàleg consultable amb data de publicació
            // Obtenim id del catàleg actiu
            $actId = ModUtil::apiFunc($this->name, 'user', 'getActiveCataleg');
            if (!is_null($actId)) {
                // Hi ha catàleg actiu
                $table = 'cataleg_activitats';
                $validStates = ' AND (estat ='.Cataleg_Constant::VALIDADA.' OR estat = '.Cataleg_Constant::MODIFICADA.')';
                if ($novetats['showNew']) {
                    // Obtenir activitats noves
                    $where = 'DATE(dataVal)>= "' . $novetats['dataPublicacio'] . '" AND ABS(DATEDIFF(CURDATE(), DATE(dataVal))) <= ' . $novetats['diesNovetats']. $validStates;
                    $orderby = 'dataVal DESC';
                    $new = DBUtil::selectObjectArray($table, $where, $orderby, -1, -1, 'actId', null, null, array('actId', 'uniId', 'titol', 'tGTAF', 'prioritaria', 'activa'));
                    $result['novetats'] = $new;
                }

                if ($novetats['showMod']) {
                    //Obtenir activitats modificades
                    $where = 'DATE(dataModif)>= "' . $novetats['dataPublicacio'] . '" AND ABS(DATEDIFF(CURDATE(), DATE(dataModif))) <= ' . $novetats['diesNovetats']. $validStates;
                    $orderby = 'dataModif DESC';
                    $canvis = DBUtil::selectObjectArray($table, $where, $orderby, -1, -1, 'actId', null, null, array('actId', 'uniId', 'titol', 'tGTAF', 'prioritaria', 'activa'));
                    $result['canvis'] = $canvis;
                }
            }
        }
        $result['showNew'] = $novetats['showNew'];
        $result['showMod'] = $novetats['showMod'];
        return $result;
    }

    /** 
     *  Retorna l'identificador del catàleg de treball
     * 
     * @return integer Identificador del catàleg de treball
     */
    public function getTreballCataleg() {
        // Verificació de seguretat
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        $catIdTr = ModUtil::getVar("Cataleg", "treball", -1);
        return $catIdTr;
    }

    /**
     * Retorna la llista de catàlegs disponibles
     * 
     * @param boolean $all Si $all = true retorna tots els catàlegs, si false només aquells amb l'estat > TANCAT
     * 
     * @return:    Array amb els catàlegs que complexin l'estat.
     */
    public function getCatalegList($all = false) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $cats = array();

        if (($all) && SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN))
            $where = 'estat > -1';
        else
            $where = 'estat > 0';

        $cats = DBUtil::selectObjectArray('cataleg', $where, 'ORDER BY anyAcad DESC', -1, -1, 'catId');
        if (!$cats) {
            return LogUtil::registerError($this->__('Atenció: no hi ha catàlegs disponibles.'));
        }
        return $cats;
    }

    /** 
     * Retorna els registres de la taula cataleg_auxiliar del tipus especificat
     * 
     * > Serveix per obtenir els registres d'un determinat tipus 
     * > de la taula auxiliar (SSTT, persones destinatàries d'una formació, modalitats de cursos, ... 
     * 
     * @param string tipus 
     * > Valors possibles:\n
     * > * "dest": possibles distinatris de la formació (Infantil, Primària, CdA, ...)
     * > * "abast": abast de la formació (al centre / diversos centres)
     * > * "pres": modalitat de la formació (semi/presencial/no presencial)
     * > * "curs": tipologia de la formació (curs, taller, seminari, jornada, ...)
     * > * "sstt": llistat de comarques (SSTT)
     * > * "gest": (gestió) Servei educatiu, Servei territorial, Unitat, PEEE, ...
     * 
     * @return array amb els registres del tipus "tipus"
     */
    public function getTipus($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $tipus = isset($args['tipus']) ? $args['tipus'] : null;
        $all = isset($args['all']) ? true : false;

        $items = array();
        if ($tipus) {
            if ($all) {
                $where = "tipus= " . $tipus;
            } else {
                $where = "tipus= '$tipus' and visible = 1";
            }
            $items = DBUtil::selectObjectArray('cataleg_auxiliar', $where, 'ORDER BY ordre, nom, nomCurt');
            return $items;
        } else {
            return LogUtil::registerError($this->__('Atenció: Manca un paràmetre. No s\'ha triat cap tipus de dada.'));
        }
    }

    /** ???
     * Retorna els grups als quals pertany un usuari
     * @author: Albert Pérez Monfort (aperezm@xtec.cat)
     * @return: array amb els grups
     */
    public function getAllUserGroups($uid) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        /* $items = array();
          // argument needed
          if ($uid != null && is_numeric($uid)) {

          $table = DBUtil::getTables();
          $c = $table['group_membership_column'];
          $where = "$c[uid]=" . $uid;
          // get the objects from the db
          $items = DBUtil::selectObjectArray('group_membership', $where);

          // Check for an error with the database code, and if so set an appropriate
          // error message and return
          if ($items === false)
          return LogUtil::registerError($this->__('S\'ha produit una errada. L\'usuari no pertany a cap grup.'));
          // Return the items
          }
          return $items;
         * 
         */
        return UserUtil::getGroupsForUser(UserUtil::getVar('uid'));
    }

    /*
     * Retorna array amb totes les unitats relacionades amb un catàleg
     * 
     * @param : integer catId id del catàleg
     * @return: Array amb les uniId i nom de les unitats
     */

    /**
     *  Retorna array amb totes les unitats relacionades amb un catàleg
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **catId** Identificador del catàleg.
     * * boolean **all** Si **all=true**: totes les unitats; si no: només les actives 
     * * array **fields** arra amb els camps es volen obtenir
     * 
     * @return array amb totes les unitats relacionades amb un catàleg
     */
    public function getAllUnits($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        //$catId = isset($args['catId']) ? $args['catId'] : ModUtil::getVar("Cataleg", "actiu"); // Per defecte catàleg actiu
        $catId = isset($args['catId']) ? $args['catId'] : null;
        $fields = isset($args['fields']) ? $args['fields'] : null;
        $all = isset($args['all']) ? true : false;
        // Si no s'especifica la id del catàleg es busca el valor del catàleg actiu

        if ($catId) {
            $unitats = array();
            if ($catId) {
                if ($all) {
                    $where = "catId = " . $catId;
                } else {
                    $where = "catId = " . $catId . " and activa = 1";
                }
                // Obtenció de les dades de les unitats del catàleg
                $unitats = DBUtil::selectObjectArray('cataleg_unitats', $where, 'nom', '-1', '-1', 'uniId', null, null, $fields);
            }
            return $unitats;
        } else {
            return LogUtil::registerError($this->__('No es pot obtenir informació de les unitats.<br />No s\'ha especificat cap catàleg .'));
        }
    }

    /*
     * Funció: getAllUnitatActivitats
     * Retorna les activitats d'una unitat i un catàleg
     * 
     * @param: $args: integer uniId: id de la unitat
     *                integer catId: id del catàleg
     *                integer filter: limita la selecció a activitats en aquest estat
     * @return: Array amb les uniId i nom de les unitats a les quals pertany
     */
    //TODO:

    /** ???
     *  Retorna les activitats d'una unitat i un catàleg
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **uniId** Identificador de la unitat.
     * * ??? **filter** 
     * 
     * @return array Amb les uniId i nom de les unitats a les quals pertany
     */
    public function getAllUnitatActivitats($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $uniId = isset($args['uniId']) ? $args['uniId'] : null;
        $filter = isset($args['filter']) ? $args['filter'] : -1;

        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }
        if (!isset($startnum) || empty($startnum)) {
            $startnum = 1;
        }
        if (!isset($itemsperpage) || empty($itemsperpage)) {
            $itemsperpage = -1;
        }

        if ($uniId) {
            // Afegim camps a la consulta i reformatem            
            switch ($filter) {
                case Cataleg_Constant::ESBORRANY:
                    $f = " AND cataleg_activitats.estat =" . Cataleg_Constant::ESBORRANY;
                    break;
                case Cataleg_Constant::ENVIADA:
                    $f = " AND cataleg_activitats.estat = 1 ";
                    break;
                case Cataleg_Constant::PER_REVISAR:
                    $f = " AND cataleg_activitats.estat = 2 ";
                    break;
                case Cataleg_Constant::VALIDADA:
                    $f = " AND cataleg_activitats.estat = 3 ";
                    break;
                case Cataleg_Constant::MODIFICADA:
                    $f = " AND cataleg_activitats.estat = 4 ";
                    break;
                case Cataleg_Constant::ANULLADA:
                    $f = " AND cataleg_activitats.estat =" . Cataleg_Constant::ANULLADA;
                    break;
                default:
                    $f = " AND cataleg_activitats.estat >= 0 ";
            }
            $sql = "SELECT cataleg_unitats.nom AS nomunitat, 
                    cataleg_unitats.uniId,
                    cataleg.catId, 
                    cataleg.estat AS catEstat, 
                    cataleg.editable, 
                    cataleg_activitats.actId,              
                    cataleg_activitats.titol,
                    cataleg_activitats.tGTAF,
                    cataleg_activitats.priId,
                    cataleg_activitats.sprId,
                    cataleg_activitats.estat,
                    cataleg_activitats.prioritaria,
                    cataleg_activitats.activa
                  FROM ((cataleg INNER JOIN cataleg_eixos ON cataleg.catId = cataleg_eixos.catId) 
                  INNER JOIN (cataleg_activitats INNER JOIN cataleg_prioritats ON 
                  cataleg_activitats.priId = cataleg_prioritats.priId) 
                  ON cataleg_eixos.eixId = cataleg_prioritats.eixId) 
                  INNER JOIN cataleg_unitats ON cataleg_activitats.uniId = cataleg_unitats.uniId 
                  WHERE cataleg_unitats.uniId=" . $uniId . $f .
                    " ORDER BY cataleg_activitats.estat, cataleg_activitats.tGTAF";

            if ($itemsperpage > 0) {
                $sql .= " LIMIT $startnum , $itemsperpage ";
            }

            $connection = Doctrine_Manager::getInstance()->getConnection('default');
            $result = $connection->prepare($sql);
            try {
                $result->execute();
            } catch (Exception $e) {
                LogUtil::registerError($this->__('No s\'ha pogut accedir a les dades sol·licitades.'));
            }

            $recordset = DBUtil::marshallObjects($result);
        } else {
            return LogUtil::registerError($this->__('No s\'ha obtingut cap resultat. Falten paràmetres.'));
        }

        // Return the items
        return $recordset;
    }

    /**
     *  Dades relatives a un centre educatiu
     *
     * ### Paràmetres rebuts per POST:
     * * integer **codi** Codi identificador del centre.
     * 
     * @return array Dades del centre sol·licitat
     */
    public function centre($codi) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $cc = FormUtil::getPassedValue('$codi', isset($codi) ? $codi : null, 'POST');

        $rs = array();
        if ((isset($cc)) && is_numeric($cc)) {
            $where = "CODI_ENTITAT=" . $cc;
            $rs = DBUtil::selectObject('cataleg_centres', $where);
        }
        return $rs;
    }

    /*
     * Funció: getCentres
     *         Informació dels centres relacionats amb una determinada activitat
     * @author: Josep Ferràndiz i Farré (jferran6@xtec.cat)
     * @param: actId (codi de l' centre'activitat)
     * @return: Array amb les dades dels centres
     */

    /**
     *  Informació dels centres relacionats amb una determinada activitat
     *
     * ### Paràmetres rebuts per POST:
     * * integer **actId** Identificador de l'activitat.
     * 
     * @return array Dades dels centres
     */
    public function getCentres($actId) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $id = FormUtil::getPassedValue('$actId', isset($actId) ? $actId : null, 'POST');

        $result = array();
        if ((isset($id)) && is_numeric($id)) {
            $where = "actId=" . $id;
            $rs = array();
            $rs = DBUtil::selectObjectArray('cataleg_centresActivitat', $where);
            foreach ($rs as $centre) {
                $result[] = ModUtil::apiFunc('Cataleg', 'user', 'centre', $centre['centre']);
            }
        }
        return $result;
    }

    /*
     * Funció: getActsZona
     *         Informació de la distribució territorial de l'acivitat
     * @author: Josep Ferràndiz i Farré (jferran6@xtec.cat)
     * @param: actId (codi de l'activitat)
     * @return: Array amb les dades de distribució
     */

    /**
     *  Informació de la distribució territorial de l'activitat
     *
     * @param int $actId Identificador de l'activitat
     * 
     * @return array Dades de distribució territorial de l'activitat.
     */
    public function getActsZona($actId) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        //$id = FormUtil::getPassedValue('$actId', isset($actId) ? $actId : null, 'POST');
        $id = isset($actId) ? $actId : null;
        $result = array();
        if ((isset($id)) && is_numeric($id)) {
            $mesos = array('', 'gener', 'febrer', 'març', 'abril', 'maig', 'juny', 'juliol', 'agost', 'setembre', 'octubre', 'novembre', 'desembre');
            $where = "actId=" . $id . " AND (qtty <> '' OR mesInici <> '')";
            $sstt = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'sstt'));
            $rs = array();
            $rs = DBUtil::selectObjectArray('cataleg_activitatsZona', $where, 'lloc', -1, -1, 'lloc');

            foreach ($sstt as $st) {
                if (isset($rs[$st['auxId']])) {
                    $aux = array();
                    $aux['mes'] = isset($mesos[$rs[$st['auxId']]['mesInici']]) ? $mesos[$rs[$st['auxId']]['mesInici']] : "";
                    $aux['lloc'] = $st['nom'];
                    $aux['qtty'] = $rs[$st['auxId']]['qtty'];
                    //if (($aux['mes']!= "") OR ($aux['qtty']))
                    $result[] = $aux;
                }
            }
        }
        return $result;
    }

    /** 
     *  Converteix l'identificador d'un element en el text descriptiu equivalent 
     *
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **mode** Mode sol·licitat (array, actId, gestio, unitat)
     * * integer o array **valor** identificador o array d'identificadors dels elements a traduir
     * 
     * @return array amb el text equivalent als identificadors
     */
    public function gettxtInfo($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $mode = FormUtil::getPassedValue('mode', isset($args['mode']) ? $args['mode'] : null, 'POST');
        $data = FormUtil::getPassedValue('valor', isset($args['valor']) ? $args['valor'] : null, 'POST');

        $result = array();
        if (count($data)) {
            if ($mode == 'array') {
                while (list($key, $value) = each($data)) {
                    if ($value)
                        $result[] = DBUtil::selectField('cataleg_auxiliar', 'nom', 'auxId=' . $value);
                }
            }
            if ($mode == 'actId') {
                // Obtenim el valor del camp "destinataris" de la taula cataleg_activitats del registre actId
                //$sdests conté l'array serialitzat
                $sdests = DBUtil::selectField('cataleg_activitats', 'destinataris', 'actId=' . $data);
                $data = unserialize($sdests);
                while (list($key, $value) = each($data)) {
                    if ($value)
                        $result[] = DBUtil::selectField('cataleg_auxiliar', 'nom', 'auxId=' . $value);
                }
            }
            if ($mode == 'gestio') {
                while (list($key, $value) = each($data)) {
                    $aux = array();
                    if (($value['txt']) && ($value['srv'])) {
                        $aux['text'] = DBUtil::selectField('cataleg_gestioActivitatDefaults', 'text', 'gesId=' . $value['txt']);
                        $aux['srv'] = DBUtil::selectField('cataleg_auxiliar', 'nom', 'auxId=' . $value['srv']);
                    }
                    $result[] = $aux;
                }
            }
            if ($mode == 'unitat') {
                $result = DBUtil::selectField('cataleg_unitats', 'nom', 'uniId=' . $data);
            }
        }
        return $result;
    }

    /**
     *  Comprova si una activitat és del mateix grup que el de l'usuari
     *
     * @param int $id Identificaor de l'activitat
     *
     * @return boolean **true** si coincideixen el grup de l'activitat i de l'usuari
     */
    public function isMine($id) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN))
            return true; // Admin accés total
        else {
            $uid = UserUtil::getVar('uid');
            $joinInfo = array();
            $joinInfo[] = array('join_table' => 'cataleg_activitats',
                'join_field' => array(),
                'object_field_name' => array('uniId'),
                'compare_field_table' => 'uniId',
                'compare_field_join' => 'uniId');
            $where = "actId =$id";
            // Grup zikula de l'activitat
            $ga = DBUtil::selectExpandedObject('cataleg_unitats', $joinInfo, $where, array('gzId'));
            // Grups de l'usuari
            $userGroups = ModUtil::apiFunc('Cataleg', 'user', 'getAllUserGroups', UserUtil::getVar('uid'));
            /* $ug = UserUtil::getGroupsForUser(UserUtil::getVar('uid'));
              echo '<pre>'; print_r($ug); echo '</pre>';
              echo '<pre>'; print_r($userGroups); echo '</pre>';
             * 
             */
            $match = false;
            // Recorregut dels grups de l'usuari per si algun coincideix amb el de l'activitat
            /* foreach ($userGroups as $gId) {
              if ($gId['gid'] == $ga['gzId'])
              $match = true;
              } */
            // Recorregut dels grups de l'usuari per si algun coincideix amb el de l'activitat
            foreach ($userGroups as $gId) {
                if ($gId == $ga['gzId'])
                    $match = true;
            }
            // L'usuari és de la mateixa unitat que l'activitat i és editor
            return ($match && SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD));
        }
    }

    /**
     *  Comprovar si un usuari és membre d'una unitat.
     * 
     * @param integer $uId Id. de la unitat.
     * 
     * @return boolean **true** si l'usuari pertany a la unitat
     */
    public function isMember($uId) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $uniId = isset($uId) ? $uId : null;
        $match = false;

        if (!is_null($uniId) && is_numeric($uniId)) {
            $userGroups = ModUtil::apiFunc('Cataleg', 'user', 'getAllUserGroups', UserUtil::getVar('uid'));
            $gzId = DBUtil::selectField('cataleg_unitats', 'gzId', 'uniId=' . $uniId);

            // Recorregut dels grups de l'usuari per si algun coincideix amb el de l'activitat
            foreach ($userGroups as $ug) {
                if ($ug == $gzId)
                    $match = true;
            }
        }
        return $match;
    }

    
    /**
     *  Comprova si un usuari pot realitzar una acció amb una activitat
     *
     * > El resultat dependrà de l'estat i editabilitat del catàleg, de l'estat de\n
     * > l'activitat (en els casos d'edició d'activitat existent) i de si l'usuari \n
     * > pertany al grup propietari de l'activitat
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **accio** Nom de la funció de la qual se'n vol verificar l'accessibilitat
     * * integer **id** Id de l'element per verificar (catId, actId o uniId)
     * 
     * @return aboolean **true** Si es pot executar la funció demanada o **false** en cas contrari
     */
    public function haveAccess($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $accio = isset($args['accio']) ? $args['accio'] : null;
        $id = isset($args['id']) ? $args['id'] : null;

        $result = false; // retorn per defecte
        if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN))
            $result = true; // Gestors accés total
        else {
            if (!is_null($id) && is_numeric($id)) {
                switch ($accio) {
                    // funcions d'edició d'activitats
                    case "edit": // Mostrar el formulari d'edició d'activitats. Espera actId
                        $catId = ModUtil::apiFunc($this->name, 'user', 'getParent', array('id' => 'actId', 'value' => $id));
                        //Obtenir editabilitat i estat del catàleg
                        $catDisp = DBUtil::selectObject('cataleg', 'catId=' . $catId, array('estat', 'editable'));
                        // Una activitat és editable si catàleg no està TANCAT i editable = 1
                        $catEditable = $catDisp['editable'] && ($catDisp['estat'] >= Cataleg_Constant::LES_MEVES);
                        // Obtenim estat de l'activitat
                        $estat = DBUtil::selectField('cataleg_activitats', 'estat', 'actId=' . $id);
                        // Un usuari només pot editar activitats d'una unitat a la qual pertanyi
                        $isMine = ModUtil::apiFunc($this->name, 'user', 'isMine', $id);
                        $result = ($estat != Cataleg_Constant::ENVIADA && $estat != Cataleg_Constant::ANULLADA && $catEditable && $isMine);
                        break;
                    case "addnew": // Mostrar el formulari de creació d'activitats si cataleg editable i no tancat
                        // Espera catId
                        $catDisp = DBUtil::selectObject('cataleg', 'catId=' . $id, array('estat', 'editable'));
                        $result = ( $catDisp['estat'] > Cataleg_Constant::TANCAT ) && $catDisp['editable'];
                        break;
                    case "save": // Desar canvis en una activitat exitent. Espera actId
                        $catId = ModUtil::apiFunc($this->name, 'user', 'getParent', array('id' => 'actId', 'value' => $id));
                        $catDisp = DBUtil::selectObject('cataleg', 'catId=' . $catId, array('estat', 'editable'));
                        $isMine = ModUtil::apiFunc($this->name, 'user', 'isMine', $id);

                        $result = $isMine && ($catDisp['estat'] > Cataleg_Constant::TANCAT ) && $catDisp['editable'] && ($estat == Cataleg_Constant::ESBORRANY);
                        break;
                    case "new": // Crear una activitat nova (no hi ha actId). Espera uniId
                        // Obtenir catàleg a partir uniId 
                        $catId = ModUtil::apiFunc($this->name, 'user', 'getParent', array('id' => 'uniId', 'value' => $id));
                        // Disponibilitat del catàleg
                        $catDisp = DBUtil::selectObject('cataleg', 'catId=' . $catId, array('estat', 'editable'));
                        // Verificar que usuari és membre de uniId 
                        $isMember = ModUtil::apiFunc($this->name, 'user', 'isMember', $id);
                        $result = $isMember && ( $catDisp['estat'] > Cataleg_Constant::TANCAT ) && $catDisp['editable'];
                        break;
                    case "delete": // Esborrar una activitat. Espera actId
                        $catId = ModUtil::apiFunc($this->name, 'user', 'getParent', array('id' => 'actId', 'value' => $id));
                        $catDisp = DBUtil::selectObject('cataleg', 'catId=' . $catId, array('estat', 'editable'));
                        $isMine = ModUtil::apiFunc($this->name, 'user', 'isMine', $id);
                        // Estat de l'activitat ESBORRANY
                        $estat = DBUtil::selectField('cataleg_activitats', 'estat', 'actId=' . $id);
                        $result = $isMine && ($catDisp['estat'] > Cataleg_Constant::TANCAT ) && $catDisp['editable'] && ($estat == Cataleg_Constant::ESBORRANY);
                        break;

                    // Opcions de lectura
                    case "cataleg": // Mostra les línies prioritàries i enllaços a orientacions i activitats.                            
                    case "display": // orientacions de línia prioritària. Espera catId
                        $catEstat = DBUtil::selectField('cataleg', 'estat', 'catId=' . $id);
                        // Els editors accedeixen al catàleg si l'estat és ORIENTACIONS o més
                        if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD)) // És editor
                            $result = ($catEstat >= Cataleg_Constant::ORIENTACIONS);
                        else
                            $result = ($catEstat == Cataleg_Constant::OBERT);
                        break;

                    case "show": // Detall d'una fitxa d'activitat
                        $catId = ModUtil::apiFunc($this->name, 'user', 'getParent', array('id' => 'actId', 'value' => $id));
                        $catEstat = DBUtil::selectField('cataleg', 'estat', 'catId=' . $catId);
                        // Els editors accedeixen al catàleg si l'estat és ACTIVITATS o més
                        if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD)) {// És editor
                            if (ModUtil::apiFunc($this->name, 'user', 'isMine', $id)) {
                                $result = ($catEstat >= Cataleg_Constant::LES_MEVES);
                            } else {
                                $result = ($catEstat >= Cataleg_Constant::ACTIVITATS);
                            }
                        } else {
                            $result = ($catEstat == Cataleg_Constant::OBERT);
                        }
                        break;
                    case "activitats": // Llistat d'activitats per prioritat
                        $catEstat = DBUtil::selectField('cataleg', 'estat', 'catId=' . $id);
                        // Els editors accedeixen al catàleg si l'estat és ACTIVITATS o més
                        if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD)) // És editor
                            $result = ($catEstat >= Cataleg_Constant::ACTIVITATS);
                        else
                            $result = ($catEstat == Cataleg_Constant::OBERT);
                        break;
                    case "view": // Accés a "Les meves activitats"
                        $catEstat = DBUtil::selectField('cataleg', 'estat', 'catId=' . $id);
                        // Els editors accedeixen al catàleg si l'estat és ACTIVITATS o més
                        if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD)) // És editor
                            $result = ($catEstat >= Cataleg_Constant::LES_MEVES);
                        break;
                }
            }
        }
        return $result;
    }

    /**
     * Retorna array amb les activitats d'una prioritat i catàleg
     *
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:     
     * * integer **catId** Identificador de catàleg
     * * integer **priId** Identificador de prioritat
     * * integer **startnum** Número de registre inicial 
     * * integer **itemsperpage** Activitats per pàgina 
     * 
     * @return array $recordset. Conté les activitats resultants
     */
    public function getAllPrioritatActivitats($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $catId = isset($args['catId']) ? $args['catId'] : null;
        $priId = isset($args['priId']) ? $args['priId'] : null;

        //$startnum = (int) FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : null, 'GETPOST');
        //$itemsperpage = isset($args['itemsperpage']) ? $args['itemsperpage'] : (int) FormUtil::getPassedValue('itemsperpage', $defaultItemsPerPage, 'GET');
        $startnum = (int) isset($args['startnum']) ? $args['startnum'] : null;
        $itemsperpage = isset($args['itemsperpage']) ? $args['itemsperpage'] : Cataleg_Constant::ITEMSPERPAGE;

        extract($args);

        if (!isset($catId)) {
            $catId = ModUtil::getVar('Cataleg', 'actiu');
        }

        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }
        if (!isset($startnum) || empty($startnum)) {
            $startnum = 0;
        }
        if (!isset($itemsperpage) || empty($itemsperpage)) {
            $itemsperpage = -1;
        }

        if ($catId && $priId) {
            // Afegim camps a la consulta i reformatem
            $sql = "SELECT
                        cataleg_activitats.actId,
                        cataleg_activitats.priId,
                        cataleg_activitats.sprId,
                        cataleg_activitats.uniId,
                        cataleg_activitats.titol,
                        cataleg_activitats.prioritaria,
                        cataleg_activitats.tGTAF,
                        cataleg_activitats.destinataris,
                        cataleg_activitats.observacions,
                        cataleg_activitats.curs,
                        cataleg_activitats.presencialitat,
                        cataleg_activitats.abast,
                        cataleg_activitats.hores,
                        cataleg_activitats.objectius,
                        cataleg_activitats.continguts,
                        cataleg_activitats.gestio,
                        cataleg_activitats.estat,
                        cataleg_activitats.ordre,
                        cataleg_activitats.validador,
                        cataleg_activitats.dataVal,
                        cataleg_activitats.obs_validador,
                        cataleg_activitats.obs_editor,
                        cataleg_activitats.centres,
                        cataleg_activitats.activa,
                        cataleg_prioritats.nom AS nomPrio,
                        cataleg_prioritats.nomCurt AS nomCurtPri,
                        cataleg_prioritats.orientacions,
                        cataleg_prioritats.recursos,
                        cataleg_prioritats.ordre AS ordrePri,
                        cataleg_prioritats.visible AS visPri,
                        cataleg_eixos.nom AS nomEix,
                        cataleg_eixos.nomCurt AS nomCurtEix,
                        cataleg_eixos.descripcio,
                        cataleg_eixos.ordre AS ordreEix,
                        cataleg_eixos.visible AS visEix,
                        cataleg.catId,
                        cataleg.anyAcad,
                        cataleg.nom AS nomCat,
                        cataleg.estat,
                        cataleg.editable,
                        cataleg_subprioritats.priId,
                        cataleg_subprioritats.nom AS nomSpr,
                        cataleg_subprioritats.nomCurt AS nomCurtSpr,
                        cataleg_subprioritats.ordre AS ordreSpr,
                        cataleg_unitats.nom AS nomUni,
                        cataleg_subprioritats.visible AS visSpr
                    FROM
                        cataleg_activitats
                    INNER JOIN cataleg_prioritats ON cataleg_activitats.priId = cataleg_prioritats.priId
                    INNER JOIN cataleg_eixos ON cataleg_prioritats.eixId = cataleg_eixos.eixId
                    INNER JOIN cataleg ON cataleg_eixos.catId = cataleg.catId
                    LEFT JOIN cataleg_subprioritats ON cataleg_activitats.sprId = cataleg_subprioritats.sprId
                    LEFT JOIN cataleg_unitats ON cataleg_activitats.uniId = cataleg_unitats.uniId
                    WHERE
                        cataleg_activitats.priId = '$priId' AND (cataleg_activitats.estat = '" . Cataleg_Constant::VALIDADA . "' OR cataleg_activitats.estat = '" . Cataleg_Constant::MODIFICADA . "')
                    ORDER BY
                        cataleg_activitats.sprId ASC,
                        cataleg_activitats.ordre ASC";
            if ($itemsperpage > 0) {
                $sql .= " LIMIT $startnum , $itemsperpage ";
            }

            $result = DBUtil::executeSQL($sql);
            $recordset = DBUtil::marshallObjects($result);
        } else {
            return LogUtil::registerError($this->__('No s\'ha obtingut cap resultat. Falten paràmetres.'));
        }
        // Return the items   
        return $recordset;
    }

    /**
     * Informació sobre el catàleg, l'eix, la prioritat i la subprioritat d'una activitat
     *
     * @param integer $id Identificador de l'activitat
     * 
     * @return array Informació del catàleg, eix, pri i subpr de l'activitat donada
     */
    public function getAuxiliarInfo($id) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $actId = FormUtil::getPassedValue('id', isset($id) ? $id : null, 'POST');
        $item = array();
        if ($actId != null && is_numeric($actId)) {
            $act = DBUtil::selectObject('cataleg_activitats', 'actId=' . $id);
            $pri = ModUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $act['priId']));
            $spr = ModUtil::apiFunc('Cataleg', 'user', 'getSubprioritat', array('sprId' => $act['sprId']));
            $eix = ModUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $pri['eixId']));
            $cat = ModUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
            $item['cataleg'] = $cat;
            $item['eix'] = $eix;
            $item['pri'] = $pri;
            $item['spr'] = $spr;
            return $item;
        }
    }

    /*
     * Retorna les unitats a les quals pertany un usuari en un determinat catàleg
     * 
     * @param: uid    id de l'usuari. Si no n'hi ha s'agafa per defecte l'usuari que fa la petició 
     * @param: catId  id del catàleg. 
     * @return: Array amb les uniId i nom de les unitats a les quals pertany
     */

    /**
     * Retorna les unitats a les quals pertany un usuari en un determinat catàleg
     *
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:     
     * * integer **catId** Identificador de catàleg.
     * * integer **uid** Id de l'usuari. Si no n'hi ha, s'agafa per defecte l'usuari que fa la petició      
     * * ??? **fields** Sembla que no s'utilitza ???
     * 
     * @return: array Conté les uniId i nom de les unitats a les quals pertany.
     */
    public function getAllUserUnits($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $catId = isset($args['catId']) ? $args['catId'] : null;
        $fields = isset($args['fields']) ? $args['fields'] : null;
        $uid = isset($args['uid']) ? $args['uid'] : UserUtil::getVar('uid');

        $esGestor = SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN);

        $items = array();
        // Es necessita argument
        if ($uid && is_numeric($uid)) {
            if ($catId && is_numeric($catId)) { // Hi ha catàleg
                // 1. Obtenim els grups de pertinença de l'usuari
                $userGroups = ModUtil::apiFunc('Cataleg', 'user', 'getAllUserGroups', $uid);
                // 2. Obtenim totes les unitats disponibles        
                $units = ModUtil::apiFunc('Cataleg', 'user', 'getAllUnits', array('catId' => $catId, 'fields' => $fields));
                // 3. Del conjunt de grups als quals pertany l'usuari només deixem aquells
                //    que es corresponen a unitats relacionades amb el catàleg
                if (!$esGestor) {
                    foreach ($userGroups as $group) {
                        foreach ($units as $unit) {
                            if ($group == $unit['gzId'])
                                $items[] = $unit;
                        }
                    }
                } else {
                    $items = $units;
                }
            } else {
                LogUtil::registerError($this->__('No es pot accedir a la informació: paràmetrers insuficients. <br />No s\'ha especificat cap catàleg,'));
            }
        } else {
            LogUtil::registerError($this->__('No es pot accedir a la informació: paràmetrers insuficients. No s\'ha especificat cap unitat,'));
        }
        // Return unitats                          
        return $items;
    }

    /** ???
     *  Informació de totes les unitats implicades en una determinada prioritat
     * 
     * @param: integer $priId    id de la prioritat
     * @return: Array amb informació de les unitats a les quals pertany l'usuari.
     *          Per a cada unitat:
     *              [uniId]      int id de la unitat
     *              [gzId]       int id del grup corresponent a Zikula
     *              [catId]      int id del catàleg de pertinença
     *              [nom]        string nom de la unitat
     *              [descripcio] string Descripció de l'àmbit i tasques de la unitat
     *              [activa]     (1|0) actualment sense ús            
     *              [cr_date]    string hora i data de creació del registre
     *              [cr_uid]     int id de l'usuari creador del registre
     *              [lu_date]    hora i data de la darrera actualització
     *              [lu_uid]     id de l'usuari que geena la darrera actualització
     *              [numresp]    int quantitat de responsables de la unitat
     *              [resp] => array amb informació de les persones responsables de la unitat
     *                      [responsable] string Nom i cognoms
     *                      [email]       string correu electrònic
     *                      [telefon]     string número de telèfon de contacte
     */
    public function getUnitatsInfoByPriId($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $result = array();
        $priId = isset($args['priId']) ? $args['priId'] : null;

        // Obtenim les unitats relacionades amb una prioritat
        if ((!is_null($priId)) && is_numeric($priId)) {
            $where = "priId =" . $priId;
            // array amb les unitats implicades en la prioritat priId
            $unitats = DBUtil::selectFieldArray('cataleg_unitatsImplicades', 'uniId', $where);
            //print_r ($unitats);
            foreach ($unitats as $unitat) {
                $result[] = ModUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $unitat));
            }
        }
        return $result;
    }

    /* ???
     * Funció: getOpcionsGestio
     * Retorna dades necessàries del camp gestió de la fitxa activitats
     * @author:  Josep Ferràndiz i Farré (jferran6@xtec.cat)
     * @param: null
     * @return array amb les opcions de gestió d'una activitat (qui fa què)
     *         i les opcions a mostrar per defecte en funció de l'entitat de gestió triada:
     *         entitats de gestió:
     *              - Servei educatiu
     *              - Servei territorial
     *              - Unitat
     *         Opcions de gestió:
     *              - Cerca del lloca de realització
     *              - Difusió
     *              - Donar d'alta al GTAF
     *              - ...
     *         També contindrà les opcions per defecte i les opcions a triar en 
     *         funció de l'entitat de gestió triada 
     *   
     */

    public function getOpcionsGestio() {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        // Obtenció de les dades
        $items = array();
        $result = array();
        // Possibles valors a les llistes deplegables dels aspectes a gestionar
        $gestors = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'gest'));
        $opts = array();
        foreach ($gestors as $gestor) {
            $i = $gestor['auxId'];
            $opts[$i]['id'] = $i;
            $opts[$i]['nom'] = $gestor['nom'];
            $opts[$i]['nomCurt'] = $gestor['nomCurt'];
            $opts[$i]['ordre'] = $gestor['ordre'];
        }
        // Obtenim els elements de gestió i les opcions per defecte que tenen
        $items = DBUtil::selectObjectArray('cataleg_gestioActivitatDefaults', 'visible = 1', 'order by ordre');
        if ($items) {
            // Recorrem l'array retornat i desfem la serialització dels camps
            foreach ($items as $item) {
                $aux = array();
                $default = unserialize($item['opcions']);
                $aux['gesId'] = $item['gesId'];
                $aux['text'] = $item['text'];
                // Crear la llista d'opcions desplegables per a cada element de gestió
                foreach ($default as $element) {
                    $it = array_merge((array) $opts[$element], array('id' => $element));
                    $aux['opcions'][] = $it;
                }
                // Afegim les opcions per defecte segons opció escollida al formulari
                $aux['op1'] = $opts[$item['opSE']];
                $aux['op2'] = $opts[$item['opST']];
                $aux['op3'] = $opts[$item['opUN']];

                $result[] = $aux;
            }
        }
        return $result;
    }

    /**
     * Retorna tots els eixos definits a la BD.
     *
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:     
     * * integer **catId** [opcional] Identificador de catàleg. Amb catId (=eixos corresponents a un catàleg) o res (= tots els eixos)
     * * boolean **all**. Si **false** es mostren només els eixos visibles.    
     * * booelan **resum**  si true només retorna els valors dels camps 'eixId', 'catId', 'nom', 'nomCurt', 'ordre' i 'visible'.
     * 
     * @return array $recordset Conté informació dels eixos del catàleg catId
     */
    public function getAllEixos($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $catId = isset($args['catId']) ? $args['catId'] : null;
        $all = isset($args['all']) ? true : false;
        $resum = isset($args['resum']) ? true : false;

        // Si s'ha passat un identificador de catàleg, filtrem només els eixos corresponents al catàleg
        // Si en canvi no s'ha passat $catId es retornaran tots els eixos
        
        if ($catId) {
            if ($all) {
                $where = "catId= " . $catId;
            } else {
                $where = "catId = " . $catId . " and visible = 1";
            }
        } else $where = "";
        
        $orderby = " ordre, eixId, catId, nom ";
        if ($resum) {
            $recordset = DBUtil::selectObjectArray('cataleg_eixos', $where, $orderby, '-1', '-1', 'eixId', null, null, array('eixId', 'catId', 'nom', 'nomCurt', 'ordre', 'visible'));
        } else {
            $recordset = DBUtil::selectObjectArray('cataleg_eixos', $where, $orderby, '-1', '-1', 'eixId');
        }
        // Verificar si hi ha hagut errors -> retornar missatge error o resultat de la consulta
        if ($recordset === false) {
            return LogUtil::registerError($this->__('No s\'ha pogut obtenir la informació demanada.'));
        } else {
            // Return the items
            return $recordset;
        }
    }

    /*
     * Retorna totes les prioritats definides a la BD
     * @author: Jordi Fons Vilardell (jfons@xtec.cat)
     * @param: args. Array amb eixId (=prioritats corresponents a un eix) o res (= totes les prioritats)
     * @return array amb tots els eixos
     */

    /**
     * Retorna prioritats pertanyents a l'eix sol·licitat
     *  
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **exiId** Identificador d'eix
     * * boolean **all** Si **all=true**: totes les prioritats; si no: només les visibles 
     * * boolean **resum** ???
     * 
     * @return array Conté les prioritats pertanyents a l'eix sol·licitat
     */
    public function getAllPrioritatsEix($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $eixId = isset($args['eixId']) ? $args['eixId'] : null;
        $all = isset($args['all']) ? true : false;
        $resum = isset($args['resum']) ? true : false;
        // Si s'ha passat un identificador d'eix ($eixId), filtrem només els eixos corresponents a l'eix demanat
        // Si en canvi no s'ha passat $eixId es retornaran totes les prioritats
        // $where = '';
        if ($eixId) {
            if ($all) {
                $where = "eixId= " . $eixId;
            } else {
                $where = "eixId = " . $eixId . " and visible = 1";
            }
        }

        $orderby = " ordre ASC";
        if ($resum) {
            $recordset = DBUtil::selectObjectArray('cataleg_prioritats', $where, $orderby, -1, -1, 'priId', null, null, array('priId', 'eixId', 'nom', 'nomCurt', 'ordre', 'visible'));
        } else {
            $recordset = DBUtil::selectObjectArray('cataleg_prioritats', $where, $orderby, -1, -1, 'priId');
        }
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($recordset === false) {
            return LogUtil::registerError($this->__('No s\'ha pogut obtenir la informació demanada.'));
        }

        // Return the items
        return $recordset;
    }

    /**
     * Obtenir totes les prioritats relacionades amb el catàleg sol·licitat
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **catId** Identificador de catàleg
     * * boolean **all** Si **all=true**: totes les prioritats; si **all=false**: només les visibles 
     * 
     * @return array $recordset Conté les prioritats relacionades amb el catàleg sol·licitat
     */
    public function getAllPrioritatsCataleg($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $catId = isset($args['catId']) ? $args['catId'] : null;
        $all = isset($args['all']) ? true : false;
        if (!isset($catId)) {
            //$cat = ModUtil::apiFunc('Cataleg', 'user', 'getActiveCataleg');
            //$cat = ModUtil::apiFunc('Cataleg', 'user', 'get');
            ModUtil::apiFunc('Cataleg', 'user', 'get', array('cataleg' => $catId));
            $catId = $cat['catId'];
        }

        $sql = "SELECT DISTINCT eix.catId, pri.*  FROM ( cataleg_prioritats AS pri 
                  INNER JOIN cataleg_eixos AS eix ON pri.eixId=eix.eixId )
                  INNER JOIN cataleg AS cat ON eix.catId = $catId ORDER BY priId";

        //$result = DBUtil::executeSQL($sql,-1,-1,true, false);
        $recordset = false;
        if ($catId) {
            $result = DBUtil::executeSQL($sql);
            $recordset = DBUtil::marshallObjects($result);
        }
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($recordset === false) {
            return LogUtil::registerError($this->__('No s\'ha pogut obtenir la informació demanada.'));
        }

        // Return the items
        return $recordset;
    }

    /**
     * Obtenir les subprioritats d'una determinada prioritat
     *      
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **priId** Identificador de prioritat
     * * boolean **all** Si **all=true**: totes les subprioritats; 
     *                   si **all=false**: només les visibles 
     * 
     * @return array Conté les subrioritats relacionades amb la prioritat sol·licitada
     */
    public function getAllSubprioritats($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        //extract($args);
        $priId = isset($args['priId']) ? $args['priId'] : null;
        $all = isset($args['all']) ? true : false;
        $resum = isset($args['resum']) ? true : false;
        // Si s'ha passat un identificador de prioritat ($priId), filtrem només per la prioritat demanada
        // Si en canvi no s'ha passat $priId es retornaran totess les subprioritats
        //$where = '';
        if ($priId) {
            if ($all) {
                $where = "priId= " . $priId;
            } else {
                $where = "priId = " . $priId . " and visible = 1";
            }
        }

        $orderby = " ordre, nom ";
        if ($resum) {
            $recordset = DBUtil::selectObjectArray('cataleg_subprioritats', $where, $orderby, '-1', '-1', 'sprId', null, null, array('sprId', 'priId', 'nom', 'nomCurt', 'ordre', 'visible'));
        } else {
            $recordset = DBUtil::selectObjectArray('cataleg_subprioritats', $where, $orderby, '-1', '-1', 'sprId');
        }

        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($recordset === false) {
            return LogUtil::registerError($this->__('No s\'ha pogut obtenir la informació demanada.'));
        }

        // Return the items
        return $recordset;
    }

    /**
     * Obtenir informació d'una unitat implicada en una línia prioritària.
     *
     * @param integer $impunitId Identificador de la unitat implicada.
     * @return: array Amb tota la informació d'aquell registre
     */
    public function getImpunit($impunitId) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        if ($impunitId) {
            $where = "impunitId= " . $impunitId;
            $recordset = DBUtil::selectObject('cataleg_unitatsImplicades', $where);
        }
        if ($recordset === false) {
            return LogUtil::registerError($this->__('No s\'ha pogut obtenir la informació demanada.'));
        }
        // Return the items
        return $recordset;
    }

    /**
     * Obtenir informació de les unitats implicades en una línia prioritària.
     *
     * @param integer $priId Identificador de la prioritat.
     * @return: array Amb totes les unitats implicades corresponents
     */
    public function getImpunits($priId) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        if ($priId) {
            $where = "priId= " . $priId;
            $orderby = "tematica,pContacte";
            $impunits = DBUtil::selectObjectArray('cataleg_unitatsImplicades', $where, $orderby, '-1', '-1', 'impunitId');
        }
        if ($impunits === false) {
            return LogUtil::registerError($this->__('No s\'ha pogut obtenir la informació demanada.'));
        }
        // Return the items
        return $impunits;
    }

    /** Obtenir tots els registres relacionats amb una unitat a la taula cataleg_unitatsImplicades
     * 
     * @param integer $uniId Identificador de la unitat
     * @return array amb els registres que compleixen la condició
     */
    public function getImpunitsUnitat($uniId) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        if ($uniId) {
            $where = "uniId= " . $uniId;
            $orderby = "tematica,pContacte";
            $impunits = DBUtil::selectObjectArray('cataleg_unitatsImplicades', $where, $orderby, '-1', '-1', 'impunitId');
        }
        if ($impunits === false) {
            return LogUtil::registerError($this->__('No s\'ha pogut obtenir la informació demanada.'));
        }
        // Return the items
        return $impunits;
    }

    /**
     * Obtenir la informació bàsica d'un catàleg sol·licitat
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **catId** Identificador de catàleg
     * 
     * @return array amb la informació sol·licitada: camps catId, anyAcad, nom, estat i editable
     */
    public function getCataleg($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }
        $catId = isset($args['catId']) ? $args['catId'] : null;

        //Comprovem que el paràmetre hagi arribat correctament
        if (!isset($catId) || !is_numeric($catId)) {
            LogUtil::registerError($this->__('No s\'han pogut obtenir les dades sol·licitades (getCataleg)'));
            return false;
        }

        $registre = DBUtil::selectObject('cataleg', " catId = '$catId' ");
        //Retormem una matriu amb la informació
        return $registre;
    }

    /**
     * Retorna la informació d'un eix sol·licitat
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **eixId** Identificador d'eix
     * 
     * @return array Contingut del registre corresponent a l'eix sol·licitat
     */
    public function getEix($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }

        $eixId = isset($args['eixId']) ? $args['eixId'] : null;
        //Comprovem que el paràmetre hagi arribat correctament
        if (!isset($eixId) || !is_numeric($eixId)) {
            return LogUtil::registerError($this->__('No s\'han pogut obtenir les dades sol·licitades (getEix)'));
        }

        $registre = DBUtil::selectObject('cataleg_eixos', 'eixId=' . $eixId);

        //Retormem una matriu amb la informació
        return $registre;
    }

    /**
     *  Retorna la llista de catàlegs amb els quals s'ha definit una relació d'importació
     *  per al catàleg $catId
     * 
     * @param integer $catId id del catàleg destí de la importació
     * @return array $result informació dels catàlegs associats
     */

    public function getCompatCats($catId) {
        $result = array();
        if (isset($catId) && is_numeric($catId)) {
            $where = 'catIdDest = ' . $catId;
            $orderby = 'catIdOri';
            $rs = DBUtil::selectObjectArray('cataleg_importTaules', $where, $orderby, -1, -1, 'catIdOri');
            foreach ($rs as $cat) {
                $result[] = ModUtil::apiFunc($this->name, 'user', 'getCataleg', array('catId' => $cat['catIdOri']));
            }
        }
        return $result;
    }

    /** 
     * Obtenir llistat d'equivalències entre prioritats/subprioritats de dos catàlegs origen i destí de la importació d'activitats
     *  
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **catOrigId** Identificador del catàleg orígen de la importació
     * * integer **catDestId** Identificador del catàleg destí de la importació
     * 
     * @return type
     */
    public function getCompatTable($args) {
        $catOrigId = $args['catOrigId'] ? $args['catOrigId'] : null;
        $catDestId = $args['catDestId'] ? $args['catDestId'] : null;
        $result = array();
        if (isset($catOrigId) && isset($catDestId)) {
            $where = 'catIdDest = ' . $catDestId . " AND catIdOri=" . $catOrigId;
            $orderby = 'catIdOri';
            $id = DBUtil::selectfield('cataleg_importTaules', 'importId', $where);
            $result = DBUtil::selectObjectArray('cataleg_importAssign', 'importId=' . $id, 'idsOri', -1, -1, 'idsOri', null, null, array('idsOri', 'idsDest'));
        } else {
            LogUtil::registerError($this->__('getCompatTable error'));
        }
        return $result;
    }

    /** 
     * Obtenir informació sobre les persones responsables d'una unitat
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **uniId** Identificador de la unitat
     * 
     * @return array $registre amb la informació de les persones responsables de la unitat
     */
    public function getAllResponsablesUnitat($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }

        $uniId = isset($args['uniId']) ? $args['uniId'] : null;

        //Comprovem que el paràmetre hagi arribat correctament
        if (!isset($uniId) || !is_numeric($uniId)) {
            return LogUtil::registerError($this->__('No s\'han pogut obtenir les dades sol·licitades (getAllResponsablesUnitat)'));
        }

        $registre = DBUtil::selectObjectArray('cataleg_responsables', 'uniId=' . $uniId, '', '-1', '-1', 'respunitId');

        //Retormem una matriu amb la informació
        return $registre;
    }

    /**
     * Retorna la informació d'una prioritat sol·licitada.
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **priId** Identificador de prioritat
     * 
     * @return array Conté la informació de la prioritat sol·licitada
     */
    public function getPrioritat($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }

        $priId = isset($args['priId']) ? $args['priId'] : null;
        //Comprovem que el paràmetre hagi arribat correctament
        $registre = array();
        if (isset($priId) && is_numeric($priId)) {
            $registre = DBUtil::selectObject('cataleg_prioritats', 'priId=' . $priId);
            $eix = ModUtil::apifunc('Cataleg', 'user', 'getEix', array('eixId' => $registre['eixId']));
            $registre['eix'] = $eix;
            $cataleg = ModUtil::apifunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
            $registre['cataleg'] = $cataleg;
        }
        //Retormem una matriu amb la informació del cataleg, l'eix i la prioritat
        return $registre;
    }

    /**
     * Retorna la informació de la subprioritat sol·licitada.
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **sprId** Identificador de subprioritat
     * 
     * @return array Conté la informació de la subprioritat sol·licitada
     */
    public function getSubprioritat($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }

        $sprId = isset($args['sprId']) ? $args['sprId'] : null;

        //Comprovem que el paràmetre hagi arribat correctament
        $registre = array();
        if (isset($sprId) && is_numeric($sprId)) {
            $where = 'sprId=' . $sprId;
            $registre = DBUtil::selectObject('cataleg_subprioritats', $where);
        }
        //Retormem una matriu amb la informació
        return $registre;
    }

    /** ???
     * 
     * @param type $args
     * @return type
     */
    public function getResponsable($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }

        $respunitId = FormUtil::getPassedValue('$respunitId', isset($args['respunitId']) ? $args['respunitId'] : null, 'GET');

        //Comprovem que el paràmetre hagi arribat correctament
        /* if (!isset($sprId) || !is_numeric($sprId)) {
          return LogUtil::registerError($this->__('No s\'han pogut obtenir les dades sol·licitades (getSubprioritat)'));
          }
         */
        $registre = array();
        if (isset($respunitId) && is_numeric($respunitId)) {
            $where = 'respunitId=' . $respunitId;
            $registre = DBUtil::selectObject('cataleg_responsables', $where);
        }
        //Retormem una matriu amb la informació
        return $registre;
    }

    /*
     * Retorna els possibles estats d'una activitat
     * @author: Jordi Fons Vilardell (jfons@xtec.cat)
     * @param: cap
     * @return array amb definició dels diferents estats d'activitat    
     * L'índex de l'array es correspon amb el codi d'estat
     * 
     */

    /**
     * Retorna array amb els  valors de conversió dels codis d'estat d'una activitat a format llegible.
     * 
     * > Array amb definició dels diferents estats d'activitat.\n   
     * > L'índex de l'array es correspon amb el codi d'estat\n
     * >       $estats = array(\n
     * >       __('Esborrany'), // = 0\n
     * >       __('Enviada'), // = 1\n
     * >       __('Cal revisar'), // = 2\n
     * >       __('Validada'), // = 3\n          
     * >       __('Modificada'), // = 4\n  
     * >       __('Anul·lada')); // = 5\n
     * 
     * @return array Conté la informació de la subprioritat sol·licitada
     */
    public function getEstatsActivitat($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }

        $estats = array(
            $this->__('Esborrany'), // = 0
            $this->__('Enviada'), // = 1            
            $this->__('Cal revisar'), // = 2
            $this->__('Validada'), // = 3            
            $this->__('Modificada'), // = 4  
            $this->__('Anul·lada')); // = 5
        //Retormem una matriu amb la informació
        return $estats;
    }

    /**
     *  Retorna un recompte del nombre d'activitats relacionats amb una unitat donada.
     *
     * > Aquesta funció es fa servir per al 'pager' de les pantalles de visualització.
     *  
     * ### Paràmetres rebuts per POST:
     * * integer **catId** Identificador de categoria
     * * integer **uniId** Identificador d'unitat
     * 
     * @return integer Nombre de registres que compleixen la sol·licitud
     */
    public function countActivitatsCatUni($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $catId = FormUtil::getPassedValue('$catId', isset($args['catId']) ? $args['catId'] : null, 'POST');
        $uniId = FormUtil::getPassedValue('$uniId', isset($args['uniId']) ? $args['uniId'] : null, 'POST');

        $sql = " SELECT COUNT(*) AS comptat "
                . "FROM ((cataleg INNER JOIN cataleg_eixos ON cataleg.catId = cataleg_eixos.catId) "
                . "INNER JOIN (cataleg_activitats INNER JOIN cataleg_prioritats ON "
                . "cataleg_activitats.priId = cataleg_prioritats.priId) "
                . "ON cataleg_eixos.eixId = cataleg_prioritats.eixId) "
                . "INNER JOIN cataleg_unitats ON cataleg_activitats.uniId = cataleg_unitats.uniId"
                . " WHERE (((cataleg_unitats.uniId)=$uniId) AND ((cataleg.catId)= $catId))";

        $result = DBUtil::executeSQL($sql);
        $recordset = DBUtil::marshallObjects($result);

        return $recordset[0]['comptat'];
    }

    /**
     *  Retorna un recompte del nombre d'activitats relacionats amb una prioritat donada.
     *
     * > Aquesta funció es fa servir per al 'pager' de les pantalles de visualització.
     * > Només es compten les activitats en estat **Validada** o bé **Modificada**
     *  
     * ### Paràmetres rebuts per POST:
     * * integer **priId** Identificador de prioritat
     * 
     * @return integer Nombre de registres que compleixen la sol·licitud
     */
    public function countActivitatsPrioritat($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $priId = FormUtil::getPassedValue('$priId', isset($args['priId']) ? $args['priId'] : null, 'POST');
        $sql = "SELECT COUNT(*) AS comptat
                    FROM
                        cataleg_activitats
                    INNER JOIN cataleg_prioritats ON cataleg_activitats.priId = cataleg_prioritats.priId
                    INNER JOIN cataleg_eixos ON cataleg_prioritats.eixId = cataleg_eixos.eixId
                    INNER JOIN cataleg ON cataleg_eixos.catId = cataleg.catId
                    WHERE
                        cataleg_activitats.priId = '$priId' AND (cataleg_activitats.estat = '" . Cataleg_Constant::VALIDADA . "' 
                                                                 OR 
                                                                 cataleg_activitats.estat = '" . Cataleg_Constant::MODIFICADA . "')";

        $result = DBUtil::executeSQL($sql);
        $recordset = DBUtil::marshallObjects($result);
        return $recordset[0]['comptat'];
    }

    /**
     * Retorna les unitats implicades en en alguna de les activitats d'una prioritat.
     *
     * > Aquesta funció es fa servir per al 'pager' de les pantalles de visualització.
     * > Només es compten les activitats en estat **Validada** o bé **Modificada**
     *  
     * ### Paràmetres rebuts per POST:
     * * integer **priId** Identificador de prioritat
     * 
     * @return integer Nombre de registres que compleixen la sol·licitud
     */
    public function getUnitatsImplicades($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        extract($args);
        $sql = "SELECT
                    cataleg_unitats.uniId,
                    cataleg_unitats.gzId,
                    cataleg_unitats.catId,
                    cataleg_unitats.nom AS nomUni,
                    cataleg_unitats.descripcio,
                    cataleg_unitats.activa,
                    cataleg_unitatsImplicades.impunitId,
                    cataleg_unitatsImplicades.priId,
                    cataleg_unitatsImplicades.uniId,
                    cataleg_unitatsImplicades.tematica,
                    cataleg_unitatsImplicades.pContacte,
                    cataleg_unitatsImplicades.email,
                    cataleg_unitatsImplicades.telContacte,
                    cataleg_unitatsImplicades.dispFormador,
                    cataleg_unitatsImplicades.pn_obj_status,
                    cataleg_unitatsImplicades.pn_cr_date,
                    cataleg_unitatsImplicades.pn_cr_uid,
                    cataleg_unitatsImplicades.pn_lu_date,
                    cataleg_unitatsImplicades.pn_lu_uid,
                    cataleg_prioritats.priId,
                    cataleg_prioritats.eixId,
                    cataleg_prioritats.nom AS nomPri,
                    cataleg_prioritats.nomCurt,
                    cataleg_prioritats.orientacions,
                    cataleg_prioritats.recursos,
                    cataleg_prioritats.ordre,
                    cataleg_prioritats.visible
                FROM
                    cataleg_unitats
                INNER JOIN cataleg_unitatsImplicades ON cataleg_unitats.uniId = cataleg_unitatsImplicades.uniId
                INNER JOIN cataleg_prioritats ON cataleg_unitatsImplicades.priId = cataleg_prioritats.priId
                WHERE
                    cataleg_unitatsImplicades.priId = '$priId' ";
        if ($uniId) $sql .= 
              " AND cataleg_unitatsImplicades.uniId = '$uniId' ";     
        $sql .="ORDER BY 
                    cataleg_prioritats.ordre";

        $result = DBUtil::executeSQL($sql);
        $recordset = DBUtil::marshallObjects($result);

        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($recordset === false) {
            return LogUtil::registerError($this->__('La consulat no ha obtingut cap resultat.'));
        }

        // Return the items
        return $recordset;
    }

    /**
     * funció: isUserGestor
     * Retorna  0 o 1 segon si l'usuari forma part del grup Gestors o no
     * @author: Jordi Fons Vilardell (jfons@xtec.cat)
     * @param: cap
     * @return boolean true or false
     */

    /**
     * Retorna si l'usuari forma part del grup Gestors o no
     * 
     * @return  boolean true or false, segon si l'usuari forma part del grup Gestors o no
     */
    public function isUserGestor() {

        return SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_ADMIN);
    }

    /**
     * Torna array amb les dades de la unitat sol·licitada
     *
     * > Torna les valors de la unitat  i, si **simple!=true**, a més, dos elements addicionals:
     * > **resp** -> array amb les dades de tots els responsables
     * > **numresp** -> nombre de responsables que té la unitat
     *  
     * ### Paràmetres rebuts per GET:
     * * integer **uniId** Identificador d'unitat
     * * boolean **simple** 
     *  
     * @return array Informació sobre 
     */
    public function getUnitat($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));


        $uniId = FormUtil::getPassedValue('uniId', isset($args['uniId']) ? $args['uniId'] : null, 'GET');
        $simple = FormUtil::getPassedValue('simple', false, 'GET');

        if (isset($uniId) && is_numeric($uniId)) {
            $where = 'uniId=' . $uniId;
        } else {
            $where = null;
        }

        $registre = DBUtil::selectObject('cataleg_unitats', $where);
        if ($registre === false) {
            return LogUtil::registerError($this->__('La consulta no ha obtingut cap resultat.'));
        }
        if (!$simple) {
            if (count($registre) > 0) {
                $where = " uniId= '" . $registre['uniId'] . "'  ";
                $respon = DBUtil::selectObjectArray('cataleg_responsables', $where);
                if ($respon === false) {
                    return LogUtil::registerError($this->__('La consulta no ha obtingut cap resultat.'));
                }

                foreach ($respon as $re) {
                    $registre['resp'][] = array('responsable' => $re['responsable'],
                        'email' => $re['email'],
                        'telefon' => $re['telefon']);
                }
                $registre['numresp'] = count($respon);
            } else {
                return LogUtil::registerError($this->__('La consulta no ha obtingut cap resultat.'));
            }
        }
        return $registre;
    }

    /** ???
     * 
     * @param type $args
     * @return type
     */
    public function getAllSubprioritatActivitats($args) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        $where = "sprId =" . $args['sprId'];
        return DBUtil::selectObjectArray('cataleg_activitats', $where);
    }

    /** ???
     * 
     * @param type $args
     * @return type
     */
    public function getImportAssigns($importId) {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        $where = 'importId = ' . $importId;
        return DBUtil::selectFieldArray('cataleg_importAssign', 'idsDest', $where, '', false, 'idsOri');
    }

    /**
     * Retorna array amb les activitats que responen a la sol·licitud de cerca
     *   
     * > Només es contemplen les activItats en estat VALIDADA O MODIFICADA 
     *     
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **titol** Text a comp'rovar si és contingt en el títol de l'activitat
     * * integer **eix** Identificador de l'eix
     * * integer **prioritat** Identificador de la prioritat
     * * integer **subprioritat** Identificador de la subprioritat
     * * integer **unitat** Identificador de la unitat
     * * integer **modcurs** Codi de la modalitat d'activitat
     * * integer **presencial** Codi de presencialitat
     * * array **destinatari** Destinataris de l'activitat
     * * integer **lloc** Zona de l'activitat
     * * integer **catId** Identificador del catàleg
     * 
     * @return array Activitats que compleixen la demanda
     */
    public function cercaconsulta($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        extract($args);

        $items = array();

        if ($titol == '' && $eix == 0 && $prioritat == 0 && $subprioritat == 0 && $unitat == 0 &&
                $modcurs == 0 && $presencial == 0 && $destinatari == 0 && $lloc == 0) {
            return NULL;
        }

        // filtratge de cataleg sol·licitat, estat del catàleg i estat d'activitats
        $where = " cataleg_eixos.catId = $catId AND (cataleg_activitats.estat = " . Cataleg_Constant::VALIDADA . " OR cataleg_activitats.estat = " . Cataleg_Constant::MODIFICADA . ") ";

        $where .= " AND titol LIKE  '%$titol%'  ";

        if ($eix > 0)
            $where .= " AND cataleg_prioritats.eixId = $eix ";

        if ($prioritat > 0)
            $where .= " AND cataleg_activitats.priId = $prioritat ";

        if ($subprioritat > 0)
            $where .= " AND cataleg_activitats.sprId = $subprioritat ";

        if ($unitat > 0)
            $where .= " AND cataleg_activitats.uniId = $unitat ";

        if ($modcurs > 0)
            $where .= " AND cataleg_activitats.curs = $modcurs ";

        if ($presencial > 0)
            $where .= " AND cataleg_activitats.presencialitat = $presencial ";

        $joinlloc = '';
        if ($lloc > 0) {
            $fieldslloc = ", cataleg_activitatsZona.lloc, cataleg_activitatsZona.qtty";
            $joinlloc = " INNER JOIN cataleg_activitatsZona ON cataleg_activitats.actId = cataleg_activitatsZona.actId";
            $where .= " AND cataleg_activitatsZona.qtty > 0 AND cataleg_activitatsZona.lloc = $lloc";
        }

        if ($destinatari > 0) {
            $where .= " AND (";
            foreach ($destinatari as $dest) {
                $where .= " cataleg_activitats.destinataris LIKE '%\"" . $dest . "\"%' OR ";
            }
            $where = substr($where, 0, -3);
            $where .= ")";
        }

        $orderby = "";

        $sql = " SELECT
                    cataleg_activitats.actId,
                    cataleg_activitats.priId,
                    cataleg_activitats.sprId,
                    cataleg_activitats.uniId,
                    cataleg_activitats.titol,
                    cataleg_activitats.presencialitat,
                    cataleg_activitats.curs,
                    cataleg_activitats.estat,
                    cataleg_activitats.destinataris,
                    cataleg_activitats.prioritaria,
                    cataleg_prioritats.eixId,
                    cataleg_activitats.activa,
                    cataleg_eixos.catId                   
                    $fieldslloc
                    FROM
                    cataleg_activitats
                 INNER JOIN cataleg_prioritats ON cataleg_activitats.priId = cataleg_prioritats.priId
                 INNER JOIN cataleg_eixos ON cataleg_prioritats.eixId = cataleg_eixos.eixId
                 INNER JOIN cataleg ON cataleg_eixos.catId = cataleg.catId
                 $joinlloc 
                 WHERE
                    $where 
                ORDER BY
                    cataleg_activitats.titol ";

        //  echo $sql;
        // print_r($items);    

        $result = DBUtil::executeSQL($sql);
        $items = DBUtil::marshallObjects($result);

        if ($items === false) {
            return LogUtil::registerError($this->__('La consulta no ha obtingut cap resultat.'));
        }

        //   print_r($items);

        return $items;
    }

    /**
     * Retorna el nom d'un element auxiliar contingut a la taula cataleg_auxiliar
     * a partir del seu auxId 
     *       
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **auxId** Identificador de  l'element auxiliar
     * 
     * @return string Nom de l'auxiliar
     */
    public function getNomAux($auxId) {
        if (!SecurityUtil::checkPermission('Cataleg::', "::", ACCESS_READ)) {
            return false;
        }

        //Comprovem que el paràmetre hagi arribat correctament
        if (!isset($auxId) || !is_numeric($auxId)) {
            return LogUtil::registerError($this->__('No s\'han pogut obtenir les dades sol·licitades (getNomAux)'));
        }

        $registre = DBUtil::selectObjectByID('cataleg_auxiliar', $auxId, 'auxId');

        // Retornem el nom corresponent al auxId sol·licitat
        return $registre['nom'];
    }

    /**
     * Obtenir informació tots els centres de la taula **cataleg_centres**
     * 
     * @return array $result Tots els centres continguts a la taula **cataleg_centres**
     */
    public function getAllCentres($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $result = DBUtil::selectObjectArray('cataleg_centres');

        return $result;
    }

    /**
     * Torna un array de centres amb format utilitzable en un select
     *
     * > Torna l'array **items** amb el format adequat per a fer un select\n
     * > amb el codi de l'entitat (centre) com a valor d'índex i el codi de tipus i \n
     * > i nom de centre com a valor a mostra en el select
     *       
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * string **tria** Identificador del tipus d'entitat. Es pot filtrar i es passa aquest paràmetre
     * 
     * @return array 
     */
    public function getCentresSelect($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        if (($args['tria'])) {
            $where = " CODI_TIPUS_ENTITAT = " . $args['tria'] . " ";
        } else {
            $where = '';
        }
        $centres = DBUtil::selectObjectArray('cataleg_centres', $where);

        $items[0] = '';
        foreach ($centres as $centre) {
            $items[$centre['CODI_ENTITAT']] = $centre['CODI_TIPUS_ENTITAT'] . " " . $centre['NOM_ENTITAT'] . " (" . $centre['NOM_LOCALITAT'] . ")";
        }

        return $items;
    }

    /**
     * Retorna array amb les activitats que responen a la sol·licitud de cerca en centre
     *
     * > Recull les activitats que es realitzen en els centres que s'han demanat
     * > Només es contemplen les activtats en estat VALIDADA O MODIFICADA 
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * array **centres** Conté un array amb els codis de centre sol·licitats al formulari de cerca en centre
     * 
     * @return array Activitas que es realiTzen en els centres demanats
     */
    public function cercaconsultacentre($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        extract($args);

        $items = array();

        foreach ($centres as $centre) {

            $sql = "SELECT cataleg.catId, 
                    cataleg_activitats.actId,
                    cataleg_activitats.priId,
                    cataleg_activitats.sprId,
                    cataleg_activitats.uniId,
                    cataleg_activitats.titol,
                    cataleg_activitats.prioritaria,
                    cataleg_activitats.tGTAF,
                    cataleg_activitats.destinataris,
                    cataleg_activitats.observacions,
                    cataleg_activitats.curs,
                    cataleg_activitats.presencialitat,
                    cataleg_activitats.abast,
                    cataleg_activitats.hores,
                    cataleg_activitats.objectius,
                    cataleg_activitats.continguts,
                    cataleg_activitats.gestio,
                    cataleg_activitats.estat,
                    cataleg_activitats.ordre,
                    cataleg_activitats.validador,
                    cataleg_activitats.dataVal,
                    cataleg_activitats.obs_validador,
                    cataleg_activitats.obs_editor,
                    cataleg_activitats.centres,
                    cataleg_activitats.activa,
                    cataleg_centresActivitat.centre,
                    cataleg_centres.CODI_TIPUS_ENTITAT,
                    cataleg_centres.NOM_ENTITAT,
                    cataleg_centres.NOM_LOCALITAT,
                    cataleg_centres.NOM_DT,
                    cataleg_centres.CODI_DT,
                    cataleg_centres.NOM_TIPUS_ENTITAT
                FROM
                    cataleg
                LEFT JOIN cataleg_eixos ON cataleg_eixos.catId = cataleg.catId
                LEFT JOIN cataleg_prioritats ON cataleg_prioritats.eixId = cataleg_eixos.eixId 
                LEFT JOIN cataleg_activitats ON  cataleg_activitats.priId = cataleg_prioritats.priId
                LEFT JOIN cataleg_centresActivitat ON cataleg_centresActivitat.actId = cataleg_activitats.actId
                LEFT JOIN cataleg_centres ON cataleg_centres.CODI_ENTITAT  = cataleg_centresActivitat.centre
                WHERE cataleg.catId = $catId AND cataleg_centresActivitat.centre = $centre 
                    AND (cataleg_activitats.estat = " . Cataleg_Constant::VALIDADA . " OR cataleg_activitats.estat =" . Cataleg_Constant::MODIFICADA . ") ";
          
            $result = DBUtil::executeSQL($sql);
            $recordset = DBUtil::marshallObjects($result);

            if ($recordset === false) {
                return LogUtil::registerError($this->__('La consulta no ha obtingut cap resultat.'));
            }
            $items = array_merge($items, $recordset);
        }
        return $items;
    }

    /**
     * Retorna array amb les activitats que responen a la sol·licitud de cerca en centre per zona
     *
     * > Recull les activitats que es realitzen en la zona de la Delegació Territorial que s'ha demanat.\n
     * > Només es contemplen les activtats en estat VALIDADA O MODIFICADA 
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * char **dt** Identificador de Delegació territorial (1-2-3-4-5-6-7-8-9-A-B) 
     * 
     * @return array Activitas que es realitzen a la DT demanada
     */
    public function cercaconsultazona($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        extract($args);

        $items = array();
/*
        $sql = "SELECT cataleg_centresActivitat.centre,
                    cataleg_centres.CODI_TIPUS_ENTITAT,
                    cataleg_centres.CODI_ENTITAT,
                    cataleg_centres.NOM_ENTITAT,
                    cataleg_centres.NOM_LOCALITAT,
                    cataleg_centres.NOM_DT,
                    cataleg_centres.CODI_DT,
                    cataleg_centres.NOM_TIPUS_ENTITAT,
                    cataleg_activitats.actId,
                    cataleg_activitats.priId,
                    cataleg_activitats.sprId,
                    cataleg_activitats.uniId,
                    cataleg_activitats.titol,
                    cataleg_activitats.prioritaria,
                    cataleg_activitats.tGTAF,
                    cataleg_activitats.destinataris,
                    cataleg_activitats.observacions,
                    cataleg_activitats.curs,
                    cataleg_activitats.presencialitat,
                    cataleg_activitats.abast,
                    cataleg_activitats.hores,
                    cataleg_activitats.objectius,
                    cataleg_activitats.continguts,
                    cataleg_activitats.gestio,
                    cataleg_activitats.estat,
                    cataleg_activitats.ordre,
                    cataleg_activitats.validador,
                    cataleg_activitats.dataVal,
                    cataleg_activitats.dataModif,
                    cataleg_activitats.obs_validador,
                    cataleg_activitats.obs_editor,
                    cataleg_activitats.centres,
                    cataleg_activitats.info,
                    cataleg_activitats.activa
                  FROM
                    cataleg_activitats
                  INNER JOIN cataleg_centresActivitat ON cataleg_activitats.actId = cataleg_centresActivitat.actId
                  INNER JOIN cataleg_centres ON cataleg_centresActivitat.centre = cataleg_centres.CODI_ENTITAT
                  WHERE
                    cataleg_centres.CODI_DT = '$dt'
                    AND (cataleg_activitats.estat = " . Cataleg_Constant::VALIDADA . " OR cataleg_activitats.estat = " . Cataleg_Constant::MODIFICADA . ")
                  ORDER BY
                    cataleg_centres.NOM_LOCALITAT ASC,
                    cataleg_centres.CODI_TIPUS_ENTITAT ASC,
                    cataleg_centres.NOM_ENTITAT ASC ";
*/
         $sql ="SELECT cataleg.catId, cataleg_activitats.actId,
                    cataleg_activitats.priId,
                    cataleg_activitats.sprId,
                    cataleg_activitats.uniId,
                    cataleg_activitats.titol,
                    cataleg_activitats.prioritaria,
                    cataleg_activitats.tGTAF,
                    cataleg_activitats.destinataris,
                    cataleg_activitats.observacions,
                    cataleg_activitats.curs,
                    cataleg_activitats.presencialitat,
                    cataleg_activitats.abast,
                    cataleg_activitats.hores,
                    cataleg_activitats.objectius,
                    cataleg_activitats.continguts,
                    cataleg_activitats.gestio,
                    cataleg_activitats.estat,
                    cataleg_activitats.ordre,
                    cataleg_activitats.validador,
                    cataleg_activitats.dataVal,
                    cataleg_activitats.obs_validador,
                    cataleg_activitats.obs_editor,
                    cataleg_activitats.centres,
                    cataleg_activitats.activa,
                    cataleg_centresActivitat.centre,
                    cataleg_centres.CODI_TIPUS_ENTITAT,
                    cataleg_centres.NOM_ENTITAT,
                    cataleg_centres.NOM_LOCALITAT,
                    cataleg_centres.NOM_DT,
                    cataleg_centres.CODI_DT,
                    cataleg_centres.NOM_TIPUS_ENTITAT
                FROM
                    cataleg
                LEFT JOIN cataleg_eixos ON cataleg_eixos.catId = cataleg.catId
                LEFT JOIN cataleg_prioritats ON cataleg_prioritats.eixId = cataleg_eixos.eixId 
                LEFT JOIN cataleg_activitats ON  cataleg_activitats.priId = cataleg_prioritats.priId
                LEFT JOIN cataleg_centresActivitat ON cataleg_centresActivitat.actId = cataleg_activitats.actId
                LEFT JOIN cataleg_centres ON cataleg_centres.CODI_ENTITAT  = cataleg_centresActivitat.centre
                WHERE cataleg.catId = $catId AND 
                    cataleg_centres.CODI_DT = '$dt'
                    AND (cataleg_activitats.estat = " . Cataleg_Constant::VALIDADA . " OR cataleg_activitats.estat = " . Cataleg_Constant::MODIFICADA . ")
                ORDER BY
                    cataleg_centres.NOM_LOCALITAT ASC,
                    cataleg_centres.CODI_TIPUS_ENTITAT ASC,
                    cataleg_centres.NOM_ENTITAT ASC ";
                
        $result = DBUtil::executeSQL($sql);
        $recordset = DBUtil::marshallObjects($result);

        if ($recordset === false) {
            return LogUtil::registerError($this->__('La consulta no ha obtingut cap resultat.'));
        }

        return $recordset;
    }

    /*
     * Torna select amb id DT | Nom DT
     * 
     * @param array $args
     * @return string HTML string
     */

    /**
     * Torna select de tria amb totes les Delegacions Territorials en format: **id DT | Nom DT**
     *
     * > El select s'obté a partir de la taula de definició de centres.
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * array **centres** Conté un array amb els codis de centre sol·licitats al formulari de cerca en centre
     * 
     * @return array Delegacions territorials per a Select (id|Nom)
     */
    public function getDTSelect($args) {

        $sql = "SELECT DISTINCT
                    cataleg_centres.CODI_DT AS codi,
                    cataleg_centres.NOM_DT AS nom
                FROM
                    cataleg_centres
                ORDER BY
                    cataleg_centres.NOM_DT ASC";

        $result = DBUtil::executeSQL($sql);
        $recordset = DBUtil::marshallObjects($result);

        if ($recordset === false) {
            return LogUtil::registerError($this->__('La consulta no ha obtingut cap resultat.'));
        }
        $torna[0] = '';
        foreach ($recordset as $dt) {
            $torna[$dt['codi']] = $dt['nom'];
        }

        return $torna;
    }

    /**
     * Retorna un array que conté els diferents arrays necessaris per als selects del formulari de cerca general
     *
     * > L'array obtingut conté diversos subarrays que retornen la informació  per a selects de:\n
     * > destinataris, modalitats de curs, presencialitat, zones 
     *  
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * integer **catId** Identificador de catàleg
     * 
     * @return array Subarrays amb selects de tria per a la cerca general
     */
    public function parametresCerca($catId) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        // recuperem destinataris...       
        //    $destinataris[0] = '';
        $destinatarisTot = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'dest'));
        foreach ($destinatarisTot as $dest) {
            $sql = "SELECT COUNT(*) AS comptat FROM cataleg_activitats WHERE cataleg_activitats.destinataris LIKE CONCAT('%\"','" . $dest['auxId'] . "','\"%')  AND (cataleg_activitats.estat = 3 OR cataleg_activitats.estat =4)AND cataleg_activitats.priId IN (SELECT priId FROM cataleg_prioritats WHERE cataleg_prioritats.eixId IN (SELECT eixId FROM cataleg_eixos WHERE cataleg_eixos.catId = $catId))";
            $result = DBUtil::executeSQL($sql);
            $recordset = DBUtil::marshallObjects($result);
            $count = $recordset[0]['comptat'];
            if ($count > 0) {
                $cerca['pdestinataris'][$dest['auxId']] = $dest['nom'];
            }
        }
        // recuperem modalitats de curs...       
        $cerca['pmodscurs'][0] = '';
        $modscursTot = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'curs'));
        foreach ($modscursTot as $mod) {
            $sql = "SELECT COUNT(*) AS comptat FROM cataleg_activitats WHERE cataleg_activitats.curs =" . $mod['auxId'] . " AND (cataleg_activitats.estat = 3 OR cataleg_activitats.estat =4)AND cataleg_activitats.priId IN (SELECT priId FROM cataleg_prioritats WHERE cataleg_prioritats.eixId IN (SELECT eixId FROM cataleg_eixos WHERE cataleg_eixos.catId = $catId))";
            $result = DBUtil::executeSQL($sql);
            $recordset = DBUtil::marshallObjects($result);
            $count = $recordset[0]['comptat'];
            if ($count > 0) {
                $cerca['pmodscurs'][$mod['auxId']] = $mod['nom'];
            }
        }
        // recuperem presencialitat...       
        $cerca['ppresencials'][0] = '';
        $presenTot = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'pres'));
        foreach ($presenTot as $pres) {
            $sql = "SELECT COUNT(*) AS comptat FROM cataleg_activitats WHERE cataleg_activitats.presencialitat = " . $pres['auxId'] . " AND (cataleg_activitats.estat = 3 OR cataleg_activitats.estat =4)AND cataleg_activitats.priId IN (SELECT priId FROM cataleg_prioritats WHERE cataleg_prioritats.eixId IN (SELECT eixId FROM cataleg_eixos WHERE cataleg_eixos.catId = $catId))";
            $result = DBUtil::executeSQL($sql);
            $recordset = DBUtil::marshallObjects($result);
            $count = $recordset[0]['comptat'];
            if ($count > 0) {
                $cerca['ppresencials'][$pres['auxId']] = $pres['nom'];
            }
        }
        // recuperem zones o lloc...       
        $cerca['psstt'][0] = '';
        $ssttTot = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'sstt'));
        foreach ($ssttTot as $st) {
            $sql = "SELECT COUNT(*) AS comptat FROM cataleg_activitatsZona INNER JOIN cataleg_activitats ON cataleg_activitatsZona.actId=cataleg_activitats.actId  WHERE cataleg_activitatsZona.lloc = " . $st['auxId'] . " AND cataleg_activitatsZona.qtty >0 AND (cataleg_activitats.estat = 3 OR cataleg_activitats.estat =4)AND cataleg_activitats.priId IN (SELECT priId FROM cataleg_prioritats WHERE cataleg_prioritats.eixId IN (SELECT eixId FROM cataleg_eixos WHERE cataleg_eixos.catId = $catId))";
            $result = DBUtil::executeSQL($sql);
            $recordset = DBUtil::marshallObjects($result);
            $count = $recordset[0]['comptat'];
            if ($count > 0) {
                $cerca['psstt'][$st['auxId']] = $st['nom'];
            }
        }
        return $cerca;
    }

    /**
     *  Crea les activitats seleccionades per a la importació
     * 
     * @param array $acts Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $acts:
     * integer **priId** Id de la prioritat
     * integer **sprId** Id de la subprioritat
     * integer **uniId** Id de la unitat
     * string **titol** Títol de l'activitat
     * string **tGTAF** Tipus GTAF de l'activitat
     * string **destinataris** Serialització de codis de destinataris segons la taula cataleg_auxiliar del tipus "dest"
     * string **observacions** Observacions relatives als destinataris de l'activitat
     * integer **curs** Codi coresponent a una tipologia de formació. Els diferents codis vàlids es troben a la taula cataleg_auxiliar amb tipus "curs"
     * integer **presencialitat** Codi coresponent a la presencialitat de l'activitat. Els diferents codis vàlids es troben a la taula cataleg_auxiliar amb tipus "pres"
     * integer **abast** Codi coresponent a l'abast de l'activitat (un o diversos centres). Els diferents codis vàlids es troben a la taula cataleg_auxiliar amb tipus "abast"
     * integer **hores** Durada en hores de l'activitat
     * string **objectius** Camp serialitzat amb els objectius de l'activitat
     * string **continguts** Camp serialitzat amb els continguts de l'activitat
     * string **gestio** Serialització codificada dels elements a gestionar (txt) de l'activitat i de l'entitat/servei que els gestiona (srv). Els primers es troben a la taula cataleg_gestioActivitatDefaults i els segons a cataleg_auxiliar amb el tipus "gest"
     * string **info** Informació general de l'activitat
     *                               
     * @return boolean true si la inserció ha tingut èxit, false en cas contrari
     */
    public function importActs(array $acts){
        return DBUtil::insertObjectArray($acts, 'cataleg_activitats');
    }
}
