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

class Cataleg_Api_Admin extends Zikula_AbstractApi {

    /**
     * @name   : setActiveCataleg
     *          Estableix la variable de mòdul que conté l'id del catàleg actiu
     * @author : Josep Ferràndiz i Farré (jferran6@xtec.cat)
     * @param  : idCat Id del catàleg a marcar com "actiu"
     * @return : boolean True if successful, false otherwise. 
     */
    public function setActiveCataleg($idCat) {
        // Verificació de seguretat
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $id = (isset($idCat)) ? $idCat : null;
        if ($id) {
            // Variable de mòdul
              $obj = array ('estat' => Cataleg_Constant::OBERT);
              $where = "WHERE catId=$id";
              DBUtil::updateObject ($obj, 'cataleg', $where);
            return ModUtil::setVar("Cataleg", "actiu", $id);
        } else {
            return false;
        }
    }
    public function setTreballCataleg($idCat) {
        // Verificació de seguretat
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $id = (isset($idCat)) ? $idCat : null;
        if ($id) {
            // Variable de mòdul
              $where = "WHERE catId=$id";
              $estatIni = DBUtil::selectField('cataleg','estat',$where);
              if ($estatIni == Cataleg_Constant::TANCAT) {
                  $obj = array ('estat' => Cataleg_Constant::LES_MEVES);
                  DBUtil::updateObject ($obj, 'cataleg', $where);
              }
            return ModUtil::setVar("Cataleg", "treball", $id);
        } else {
            return false;
        }
    }
    public function saveCataleg($item) {
     if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
//    $cat = FormUtil::getPassedValue('item',isset($item) ? $item : null,POST);
     if ($item) {
        if ($item['catId']) {
            // Estem editant. El catàleg ja existeix
            $where = "WHERE catId = " . $item['catId'];
            DBUtil::updateObject($item, 'cataleg', $where);
            $insertCatId = 'edit';
        } else {
            // Estem creant un catàleg nou
            DBUtil::insertObject($item, 'cataleg', 'catId');
            $insertCatId = DBUtil::getInsertID('cataleg', 'catId');
        }
    return $insertCatId;
    }else{
        return false;
    }
    }
    public function saveEix($item) {
     if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
      if ($item) {
        if ($item['eixId']) {
            // Estem editant. L'eix ja existeix
            $where = "WHERE eixId = " . $item['eixId'];
            DBUtil::updateObject($item, 'cataleg_eixos', $where);
            $insertEixId = 'edit';
        } else {
            // Estem creant un eix nou
            DBUtil::insertObject($item, 'cataleg_eixos', 'eixId');
            $insertEixId = DBUtil::getInsertID('cataleg_eixos', 'eixId');
        }
    return $insertEixId;
    }else{
        return false;
    }
    }
    public function savePrioritat($item) {
     if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
      if ($item) {
        if ($item['priId']) {
            // Estem editant. La prioritat ja existeix
            $where = "WHERE priId = " . $item['priId'];
            DBUtil::updateObject($item, 'cataleg_prioritats', $where);
            $insertPriId = 'edit';
        } else {
            // Estem creant una prioritat nova
            DBUtil::insertObject($item, 'cataleg_prioritats', 'priId');
            $insertPriId = DBUtil::getInsertID('cataleg_prioritats', 'priId');
        }
    return $insertPriId;
    }else{
        return false;
    }
    }
    public function saveSubprioritat($item) {
     if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
      if ($item) {
        if ($item['sprId']) {
            // Estem editant. La subprioritat ja existeix
            $where = "WHERE sprId = " . $item['sprId'];
            DBUtil::updateObject($item, 'cataleg_subprioritats', $where);
            $insertSubpriId = 'edit';
        } else {
            // Estem creant una prioritat nova
            DBUtil::insertObject($item, 'cataleg_subprioritats', 'sprId');
            $insertSubpriId = DBUtil::getInsertID('cataleg_subprioritats', 'sprId');
        }
    return $insertSubpriId;
    }else{
        return false;
    }
    }
    
    public function saveImpunit($item) {
        /* if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
          return LogUtil::registerPermissionError();
          }
         * 
         */
        if ($item) {
            if ($item['impunitId']) {
                // Estem editant. La unitat implicada ja existeix
                $where = "WHERE impunitId = " . $item['impunitId'];
                DBUtil::updateObject($item, 'cataleg_unitatsImplicades', $where);
                $insertImpunitId = 'edit';
            } else {
                // Estem creant una unitat implicada nova
                DBUtil::insertObject($item, 'cataleg_unitatsImplicades', 'impunitId');
                $insertImpunitId = DBUtil::getInsertID('cataleg_unitatsImplicades', 'impunitId');
            }
            return $insertImpunitId;
        } else {
            return false;
        }
    }

    public function saveUnitat($item) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            // Check if user have edit permissions
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $item['uniId'])))){
                LogUtil::registerStatus('No access');
                return LogUtil::registerPermissionError();
            } 
        }
        
        if ($item) {
            if ($item['uniId']) {
                // Estem editant. La unitat ja existeix
                $where = "WHERE uniId = " . $item['uniId'];
                DBUtil::updateObject($item, 'cataleg_unitats', $where);
                $insertUnitId = 'edit';
            } else {
                // Estem creant una unitat nova
                DBUtil::insertObject($item, 'cataleg_unitats', 'uniId');
                $insertUnitId = DBUtil::getInsertID('cataleg_unitats', 'uniId');
            }
            return $insertUnitId;
        }else{
            return false;
        }
    }
    
    
    public function saveResponsable($item) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $item['uniId'])))) {
                return LogUtil::registerPermissionError();
            }
        }
        if ($item) {
            if ($item['respunitId']) {
                // Estem editant. La persona responsable ja existeix
                $where = "WHERE respunitId = " . $item['respunitId'];
                DBUtil::updateObject($item, 'cataleg_responsables', $where);
                $insertRespunitId = 'edit';
            } else {
                // Estem creant una nova persona responsable
                DBUtil::insertObject($item, 'cataleg_responsables', 'respunitId');
                $insertRespunitId = DBUtil::getInsertID('cataleg_responsables', 'respunitId');
            }
            return $insertRespunitId;
        } else {
            return false;
        }
    }

    // Inicialització de taules amb valors per defecte
    public function initAuxiliarTable() {
        //TODO: Això s'hauria de fer en la instal·lació del mòdul
        return true;
    }
/**
     * Funció: getAllGroups
     *         Retorna tots els grups de zikula
     * @author: Joan Guillén i Pelegay(jguille2@xtec.cat)
     * @param: 
     * @return: Array amb tota la taula groups
     */
    public function getAllGroupsUnits() {
        //Verificar permisos
       $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
      /* $grupsUnitatsLlista = ModUtil::getVar("Cataleg", "grupsUnitats");
       //$allGroups = DBUtil::selectObjectArray('groups', '','','-1','-1','gid');
       $allGroups = UserUtil::getGroups();
       foreach ($grupsUnitatsLlista as $grup) {
           $allGroupsUnits[$grup] = $allGroups[$grup];
       }*/
       $allGroups = UserUtil::getGroups('','name');
       $grupsUnitatsLlista = ModUtil::getVar("Cataleg", "grupsUnitats");
       foreach ($allGroups as $gid=>$group){
           if (in_array($gid,$grupsUnitatsLlista)) {
               $allGroupsUnits[$gid] = $group;
           }
       }
       return $allGroupsUnits;
    }
    public function saveGroup($item) {
     if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
//    $cat = FormUtil::getPassedValue('item',isset($item) ? $item : null,POST);
     if ($item) {
        if ($item['gid']) {
            // Estem editant. El grup ja existeix
            $where = "WHERE gid = " . $item['gid'];
            DBUtil::updateObject($item, 'groups', $where);
            $insertGroupId = 'edit';
        } else {
            // Estem creant un grup nou
            DBUtil::insertObject($item, 'cataleg', 'catId');
            $res = DBUtil::insertObject($item, 'groups', 'gid');
            $insertGroupId = $res['gid'];
            $grupsUnitatsLlista = ModUtil::getVar("Cataleg", "grupsUnitats");
            $grupsUnitatsLlista [] = $insertGroupId;
            ModUtil::setVar("Cataleg", "grupsUnitats", $grupsUnitatsLlista);
            
        }
    return $insertGroupId;
    }else{
        return false;
    }
    }
    public function importCataleg($item) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_READ));
        if ($item['iUnitats']) {
            $unitats = ModUtil::apiFunc('Cataleg', 'user', 'getAllUnits', array('catId' => $item['catIdOri'], 'all' => true));
            foreach ($unitats as $unitat){
                $unitat['catId'] = $item['catIdDest'];
                $vellaUnitat = $unitat['uniId'];
                DBUtil::insertObject($unitat,'cataleg_unitats','uniId');
                $importunits[$vellaUnitat] = $unitat['uniId'];
                if ($item['iResponsables']) {
                    $responsables = modUtil::apiFunc('Cataleg','user','getAllResponsablesUnitat',array('uniId' => $vellaUnitat));
                    foreach ($responsables as $responsable){
                        $responsable['uniId'] = $unitat['uniId'];
                        DBUtil::insertObject($responsable,'cataleg_responsables','respunitId');
                    }
                }
            }
        }
        if ($item['iEixos']) {
            $eixos = ModUtil::apiFunc('Cataleg', 'user', 'getAllEixos', array('catId' => $item['catIdOri'], 'all' => true));
            foreach ($eixos as $eix) {
                $eix['catId'] = $item['catIdDest'];
                $vellEix = $eix['eixId'];
                DBUtil::insertObject($eix,'cataleg_eixos','eixId');
                if ($item['iPrioritats']) {
                    $prioritats = modUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsEix', array('eixId' => $vellEix, 'all' => true));
                    foreach ($prioritats as $prioritat) {
                        $prioritat['eixId'] = $eix['eixId'];
                        $vellaPrioritat = $prioritat['priId'];
                        DBUtil::insertObject($prioritat,'cataleg_prioritats','priId');
                        if ($item['iSubprioritats']) {
                            $subprioritats = modUtil::apiFunc('Cataleg','user','getAllSubprioritats',array('priId' => $vellaPrioritat, 'all' => true));
                            foreach ($subprioritats as $subprioritat) {
                                $subprioritat['priId'] = $prioritat['priId'];
                                DBUtil::insertObject($subprioritat,'cataleg_subprioritats','sprId');
                            }
                        }
                        if ($item['iImpunits']) {
                            $impunits = modUtil::apiFunc('Cataleg','user','getImpunits',$vellaPrioritat);
                            foreach ($impunits as $impunit) {
                                $impunit['priId'] = $prioritat['priId'];
                                $impunit['uniId'] = $importunits[$impunit['uniId']];
                                DBUtil::insertObject($impunit,'cataleg_unitatsImplicades','impunitId');
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
    public function getImportTaules($importId) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_READ));
        if ($importId == 'all') {
            $item = DBUtil::selectObjectArray('cataleg_importTaules','','',-1,-1,'importId');
        } else {
            $item = DBUtil::selectObject('cataleg_importTaules','importId = '.$importId);
        }
        return $item;
        
    }
    public function importaddTaula($item) {
     if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
//    $cat = FormUtil::getPassedValue('item',isset($item) ? $item : null,POST);
     if ($item) {
         $where = 'catIdOri = '.$item['catIdOri'].' AND catIdDest = '.$item['catIdDest'];
         $comp = DBUtil::selectObject('cataleg_importTaules',$where);
         if ($comp) {
             LogUtil::registerError($this->__('Ja existeix una taula d\'importació entre aquests catàlegs.'));
             return false;
         } else {
         DBUtil::insertObject($item, 'cataleg_importTaules', 'importId');
         return $item['importId'];
         }
    }else{
        return false;
    }
    }
    public function importeditTaula($item) {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $importId = $item['importId'];
        unset($item['importId']);
        $where = "importId = ".$importId;
        DBUtil::deleteWhere('cataleg_importAssign', $where);
        foreach ($item as $key => $it ) {
            if (!empty($it)) {
            $objecte['idsOri'] = $key;
            $objecte['idsDest'] = $it;
            $objecte['importId'] = $importId;
            DBUtil::insertObject($objecte, 'cataleg_importAssign');
            }
        }
        return true;
    }
    public function importdeleteTaula($importId) {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $where = "importId = ".$importId;
        if ((DBUtil::deleteWhere('cataleg_importAssign', $where)) && (DBUtil::deleteWhere('cataleg_importTaules', $where))) {
            return true;
        } else {
            return false;
        }
    }
    //public function backup_tables($host, $user, $pass, $name, $tables = '*') {
    public function backup_tables($tables = '*') {

   }
   public function getlinks($args)
    {
       if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
       $links[] = array('url' => ModUtil::url('Cataleg', 'admin', 'catalegsgest', array()), 'text' => $this->__('Catàlegs'), 'id' => 'catalegsgest', 'class' => 'z-icon-es-view');
       $links[] = array('url' => ModUtil::url('Cataleg', 'admin', 'usersgest', array()), 'text' => $this->__('Usuaris'), 'id' => 'usersgest', 'class' => 'z-icon-es-user');
       $links[] = array('url' => ModUtil::url('Cataleg', 'admin', 'groupsgest', array()), 'text' => $this->__('Grups'), 'id' => 'catalegsgest', 'class' => 'z-icon-es-group');
       $links[] = array('url' => ModUtil::url('Cataleg', 'admin', 'modulesettings', array()), 'text' => $this->__('Gestió del mòdul'), 'id' => 'modulesettings', 'class' => 'z-icon-es-config');
       }
       

        return $links;
    }
    public function getgrupsZikula() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $grupsZikula = ModUtil::getVar("Cataleg", "grupsZikula"); 
        return $grupsZikula;
    }
    public function saveUser($user) {
        //Comprovacions de seguretat. Només els gestors poden editar i crear usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        
        if ($user) {
            if ($user['zk']['uid']) {
                // Estem editant. L'usuari ja existeix
                $where1 = "WHERE uid = " . $user['zk']['uid'];
                $where2 = "WHERE iw_uid = " . $user['iw']['uid'];
                DBUtil::updateObject($user['zk'], 'users', $where1);
                $a = DBUtil::selectObject('IWusers', $where2);
                if ($a) {
                    DBUtil::updateObject($user['iw'], 'IWusers', $where2);
                } else {
                    DBUtil::insertObject($user['iw'], 'IWusers', 'suid');
                }
                $insertUserId = $user['zk']['uid'];
            } else {
                // Estem creant un usuari nou
                $user['zk']['activated'] = 1;
                $user['zk']['approved_date'] = DateUtil::getDatetime();
                $user['zk']['user_regdate'] = DateUtil::getDatetime();
                $user['zk']['approved_by'] = UserUtil::getVar('uid');
                DBUtil::insertObject($user['zk'], 'users', 'uid');
                $insertUserId = $user['zk']['uid'];
                $user['iw']['uid'] = $insertUserId;
                $user['iw']['suid'] = $insertUserId;
                DBUtil::insertObject($user['iw'], 'IWusers', 'suid');
            }
            //Netegem l'assignació de grups relacinats amb el catàleg
            $grupsUnitats = ModUtil::getVar('Cataleg', 'grupsUnitats');
            $grupsZikula = ModUtil::getVar('Cataleg', 'grupsZikula');
            foreach ($grupsUnitats as $grup) {
                $where = 'gid = ' . $grup . ' AND uid = ' . $user['zk']['uid'];
                DBUtil::deleteWhere('group_membership', $where);
            }
            foreach ($grupsZikula as $grup) {
                $where = 'gid = ' . $grup . ' AND uid = ' . $user['zk']['uid'];
                DBUtil::deleteWhere('group_membership', $where);
            }
            //Assignem els grups indicats en el formulari
            foreach ($user['gr'] as $grup) {
                $item = array('gid' => $grup, 'uid' => $user['zk']['uid']);
                DBUtil::insertObject($item, 'group_membership');
            }
            return $insertUserId;
        } else {
            return false;
        }
    }
   public function setgrupsZikula($args) {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        if ($args) {
            return ModUtil::setVar("Cataleg", "grupsZikula", $args);
        } else {
            return false;
        }
    }
    public function getAuxElements () {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        return DBUtil::selectObjectArray ('cataleg_auxiliar','','tipus,ordre,nom',-1,-1,'auxId');
    }
    public function getAuxElement ($auxId) {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        return DBUtil::selectObject('cataleg_auxiliar','auxId = '.$auxId);
    }
    public function saveAuxElement($item) {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        if ($item) {
        if ($item['auxId']) {
            // Estem editant. L'element ja existeix
            $where = "auxId = " . $item['auxId'];
            DBUtil::updateObject($item, 'cataleg_auxiliar', $where);
            $insertAuxElementId = 'edit';
        } else {
            // Estem creant un element nou
            DBUtil::insertObject($item, 'cataleg_auxiliar', 'auxId');
            $insertAuxElementId = DBUtil::getInsertID('cataleg_auxiliar','auxId');
        }
        return $insertAuxElementId;
        } else {
        return false;
        }
    }
    public function gestMembers($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $groupUsers = UserUtil::getUsersForGroup($args['gid']);
        $editorsUsers = ($args['uni']) ? UserUtil::getUsersForGroup($args['uni']) : false;
        $grupsUnitats = ModUtil::getVar('Cataleg', 'grupsUnitats');
        if ($args['action'] == 'add') {
            $res['resposta'] = true;
            foreach ($args['users'] as $userid) {
                $item1 = array('gid'=>$args['gid'],'uid'=>$userid);
                $r1 = (!in_array($userid,$groupUsers)) ? DBUtil::insertObject($item1,'group_membership'): false;
                $item2 = array('gid'=>$args['uni'],'uid'=>$userid);
                if ($args['uni'] && !in_array($userid,$editorsUsers)) {
                    DBUtil::insertObject($item2,'group_membership');
                    $res['nousEditors'][] = $userid;
                }
            }
            return $res;
        } else if ($args['action'] == 'remove') {
            $res['resposta'] = true;
            foreach ($args['users'] as $userid) {
                $where1= 'gid = '.$args['gid'].' AND uid = '.$userid;
                $r1 = (in_array($userid,$groupUsers)) ? DBUtil::deleteWhere('group_membership',$where1):false;
                if ($args['uni']) {
                    $a = 0;
                    foreach ($grupsUnitats as $unitat) {
                        $unitatUsers = UserUtil::getUsersForGroup($unitat);
                        $a = (in_array($userid,$unitatUsers)) ? $a+1:$a;
                    }
                    $where2 ='gid = '.$args['uni'].' AND uid = '.$userid;
                    if ($a < 2) {
                        DBUtil::deleteWhere('group_membership',$where2);
                        $res['exEditors'][] = $userid;   
                    }
                }
             }
            return $res;
        }
        return false;
    }
     public function deleteGroupUnit($gid) {
         if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        //Comprovem que aquest grup no tingui usuaris assignats
        $where1 = 'gid = '.$gid;
        $r1 = DBUtil::selectObject('group_membership',$where1);
        if ($r1) {
            LogUtil::registerError($this->__('No es pot esborrar. El grup té usuaris assignats.'));
            return false;
        }
        //Comprovem que cap unitat del catàleg és gestionada per aquest grup
        $where2 = 'gzId = '.$gid;
        $r2 = DBUtil::selectObject('cataleg_unitats',$where2);
        if ($r2) {
            LogUtil::registerError($this->__('No es pot esborrar. El grup gestiona unitats del catàleg.'));
            return false;
        }
        DBUtil::deleteWhere('groups',$where1);
        DBUtil::deleteWhere('group_perms',$where1);
        return true;
     }
     public function countElementsCataleg() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $cats=array();
        $cats = ModUtil::apiFunc('Cataleg', 'user', 'getCatalegList', true);
        foreach ($cats as $key=>$cat) {
            $cats[$key]['uni'] = 0;
            $cats[$key]['eix'] = 0;
            $cats[$key]['pri'] = 0;
            $cats[$key]['spr'] = 0;
            $cats[$key]['uni'] = DBUtil::selectObjectCount('cataleg_unitats','catId = '.$cat['catId']);
            $eixos[$key] = DBUtil::selectObjectArray('cataleg_eixos','catId = '.$cat['catId']);
            foreach ($eixos[$key] as $pkey=>$eix) {
                ++$cats[$key]['eix'];
                $prioritats[$key][$pkey] = DBUtil::selectObjectArray('cataleg_prioritats','eixId = '.$eix['eixId']);
                foreach ($prioritats[$key][$pkey] as $prioritat) {
                    ++$cats[$key]['pri'];
                    $cats[$key]['spr'] += DBUtil::selectObjectCount('cataleg_subprioritats','priId = '.$prioritat['priId']);
                }
            }
            
        }
        return $cats;
     }
     public function teEquivalencies($args) {
         if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        //Mirem si tractem una prioritat o una subprioritat
        if ($args['tipus'] == 'pri') {
            $where = 'idsOri LIKE "'.$args['id'].'o%" OR idsDest LIKE "'.$args['id'].'d%"';
            
        } elseif ($args['tipus'] == 'spr') {
            $where = 'idsOri LIKE "%o'.$args['id'].'" OR idsDest LIKE "%d'.$args['id'].'"';
            
        } else {
            return false;
        }
        $res = DBUtil::selectObjectArray('cataleg_importAssign',$where);
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
     }
     /**
     * Funció per l'obtenció de la informació de les entitats i grups d'entitas de gtaf
     *
     *  > Obté un array amb les entitats i grups d'entitats i la informació corresponent
     *  > També carrega la informació d'usuari (IWusers) del responsable del grup d'entitat
     *
     * @return array *gtafInfo* amb tota la informació
     */
     public function getGtafInfo() {
         $gtafInfo = array();
         $gtafInfo['entities'] = DBUtil::selectObjectArray('cataleg_gtafEntities','','gtafGroupId,gtafEntityId',-1,-1,'gtafEntityId');
         $gtafInfo['groups'] = DBUtil::selectObjectArray('cataleg_gtafGroups','','gtafGroupId',-1,-1,'gtafGroupId');
         $gtafInfo['groupsAc'] = DBUtil::selectFieldArray('cataleg_gtafEntities', 'gtafGroupId');
         foreach ($gtafInfo['groups'] as $key=>$group) {
             if (isset($group['resp_uid']) && $group['resp_uid'] !='') $gtafInfo['groups'][$key]['responsable'] = DBUtil::selectObject('IWusers','iw_uid='.$group['resp_uid']);
         }
         foreach ($gtafInfo['entities'] as $key=>$entity) {
             if (isset($gtafInfo['groups'][$entity['gtafGroupId']])) {
             $gtafInfo['groups'][$entity['gtafGroupId']]['entities'][] = $entity;
             } else {
                 $gtafInfo['ent_orfe'][] = $entity['gtafEntityId'];
             } 
             $gtafInfo['entities_code'][] = $entity['gtafEntityId'];
         }
         return $gtafInfo;
     }
     /**
     * Funció per l'obtenció de la informació d'una entitat-gtaf
     *
     *  > Obté un array amb la informació de l'entitat-gtaf
     *
     * @return array *gtafEntity* amb tota la informació de la entitat
     */
     public function getGtafEntity($gtafeid) {
         $gtafInfo = array();
         $gtafInfo['entity'] = DBUtil::selectObject('cataleg_gtafEntities','gtafEntityId="'.$gtafeid.'"');
         $gtafInfo['entities'] = DBUtil::selectFieldArray('cataleg_gtafEntities','gtafEntityId');
         $gtafInfo['groups'] = DBUtil::selectObjectArray('cataleg_gtafGroups','','gtafGroupId',-1,-1,'gtafGroupId');
         return $gtafInfo;
     }
      /**
     * Funció per l'obtenció de la informació d'una entitat-gtaf
     *
     *  > Obté un array amb la informació de l'entitat-gtaf
     *
     * @return array *gtafEntity* amb tota la informació de la entitat
     */
     public function getGtafGroups($gtafgid) {
         $gtafInfo = array();
         if (isset($gtafgid)){
             $gtafInfo['group'] = DBUtil::selectObject('cataleg_gtafGroups','gtafGroupId="'.$gtafgid.'"');
         }
         $gtafInfo['groups'] = DBUtil::selectFieldArray('cataleg_gtafGroups','gtafGroupId');
         $grupsZikula = ModUtil::getVar("Cataleg", "grupsZikula");
         $usercatlist = UserUtil::getUsersForGroup($grupsZikula['Sirius']);
         $users = UserUtil::getUsers('', 'uname', -1, -1, 'uid');
         foreach ($users as $key => $user) {
            if (in_array($key, $usercatlist)) {
                $gtafInfo['catusers'][$key] = array('zk' => $user, 'iw' => DBUtil::selectObject('IWusers', 'where iw_uid =' . $key));
            }
         }
         return $gtafInfo;
     }
     public function saveEntitat($item) {
     if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
//    $cat = FormUtil::getPassedValue('item',isset($item) ? $item : null,POST);
     if ($item) {
        if (isset($item['prev_gtafEntityId'])) {
            // Estem editant
            $where = 'WHERE gtafEntityId="' . $item['prev_gtafEntityId'] . '"';
            DBUtil::updateObject($item, 'cataleg_gtafEntities', $where);
            $insertGtafEnt = 'edit';
        } else {
            // Estem creant una entitat nova
            DBUtil::insertObject($item, 'cataleg_gtafEntities');
            $insertGtafEnt = $item['gtafEntityId'];
        }
    return $insertGtafEnt;
    }else{
        return false;
    }
    }
    public function saveGtafGroup($item) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        if ($item) {
            if (isset($item['prev_gtafGroupId'])) {
                // Estem editant
                $item_ori['gtafGroupId'] = $item['gtafGroupId'];
                $where = 'WHERE gtafGroupId="' . $item['prev_gtafGroupId'] . '"';
                DBUtil::updateObject($item, 'cataleg_gtafGroups', $where);
                DBUtil::updateObject($item_ori,'cataleg_gtafEntities',$where);
                $insertGtafEnt = 'edit';
            } else {
                // Estem creant un grup nou
                DBUtil::insertObject($item, 'cataleg_gtafGroups');
                $insertGtafEnt = $item['gtafGroupId'];
            }
            return $insertGtafEnt;
        } else {
            return false;
        }
    }
}