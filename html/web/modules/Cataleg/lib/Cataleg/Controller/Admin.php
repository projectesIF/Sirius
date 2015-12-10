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
class Cataleg_Controller_Admin extends Zikula_AbstractController {

    /**
     * Funció inicial de l'administració del catàleg
     *
     * > Funció inicial de l'administració del mòdul Cataleg.\n
     * > Redirecciona a la funció **catalegsgest**.
     *
     * @return void
     */
    public function main() {
        // Security check will be done in catalegsgest()
        $this->redirect(ModUtil::url('Cataleg', 'admin', 'catalegsgest'));
    }

    /**
     * Accedeix a la gestió de tots els catàlegs existents
     *
     * > S'obté la llista de tots els catàlegs i permet administrar-los
     *
     * @return void Plantilla *Cataleg_admin_catalegsgest.tpl*
     */
    public function catalegsgest() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $cats = ModUtil::apiFunc('Cataleg', 'user', 'getCatalegList', true);
        $catIdAc = ModUtil::apiFunc('Cataleg', 'user', 'getActiveCataleg');
        $catIdTr = ModUtil::apiFunc('Cataleg', 'user', 'getTreballCataleg');
        $this->view->assign('cats', $cats);
        $this->view->assign('catIdAc', $catIdAc);
        $this->view->assign('catIdTr', $catIdTr);
        $this->view->assign('CatalegAdmin', SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN));
        return $this->view->fetch('admin/Cataleg_admin_catalegsgest.tpl');
    }

    /**
     * Accedeix a la gestió de tots els usuaris del catàleg
     *
     * > S'obté la llista de tots els usuaris i permet administra-los.\n
     * > Es poden crear nous usuaris, editar els existents, 'esborrar-los' o recuperar 'ex-usuaris'.
     *
     * @return void Plantilla *Cataleg_admin_usersgest.tpl*
     */
    public function usersgest() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $grupsZikula = ModUtil::getVar("Cataleg", "grupsZikula");
        $usercatlist = UserUtil::getUsersForGroup($grupsZikula['Sirius']);
        $exusercatlist = UserUtil::getUsersForGroup($grupsZikula['ExSirius']);
        $users = UserUtil::getUsers('', 'uname', -1, -1, 'uid');
        $allGroupsUnits = ModUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        $excatusers = array();
        foreach ($users as $key => $user) {
            if (in_array($key, $usercatlist)) {
                $gr = array();
                $uni = array();
                $grups = UserUtil::getGroupsForUser($key);
                foreach ($grups as $grup) {
                    ($grupsZikula['Generics'] == $grup) ? $gr['Generics'] = 1 : false;
                    ($grupsZikula['Personals'] == $grup) ? $gr['Personals'] = 1 : false;
                    ($grupsZikula['Gestform'] == $grup) ? $gr['Gestform'] = 1 : false;
                    ($grupsZikula['LectorsCat'] == $grup) ? $gr['LectorsCat'] = 1 : false;
                    ($grupsZikula['EditorsCat'] == $grup) ? $gr['EditorsCat'] = 1 : false;
                    ($grupsZikula['Gestors'] == $grup) ? $gr['Gestors'] = 1 : false;
                    ($grupsZikula['Odissea'] == $grup) ? $gr['Odissea'] = 1 : false;
                    ($grupsZikula['Cert'] == $grup) ? $gr['Cert'] = 1 : false;
                    ($grupsZikula['gA'] == $grup) ? $gr['gA'] = 1 : false;
                    ($grupsZikula['gB'] == $grup) ? $gr['gB'] = 1 : false;
                    if (isset($allGroupsUnits[$grup])) {
                        $uni[$grup]['gid'] = $grup;
                        $uni[$grup]['name'] = $allGroupsUnits[$grup]['name'];
                    }
                }
                $catusers[$key] = array('zk' => $user, 'iw' => DBUtil::selectObject('IWusers', 'where iw_uid =' . $key), 'gr' => $gr, 'uni' => $uni);
            }
            if (in_array($key, $exusercatlist)) {
                $excatusers[$key] = array('zk' => $user, 'iw' => DBUtil::selectObject('IWusers', 'where iw_uid =' . $key));
            }
        }
        $gtafInfo = ModUtil::apiFunc($this->name,'admin','getGtafInfo');
        $this->view->assign('gtafInfo',$gtafInfo);
        $entities = DBUtil::selectObjectArray('cataleg_gtafEntities','','',-1,-1,'gtafEntityId');
        $this->view->assign('entities', $entities);
        $this->view->assign('catusers', $catusers);
        $this->view->assign('excatusers', $excatusers);
        return $this->view->fetch('admin/Cataleg_admin_usersgest.tpl');
    }

    /**
     * Accedeix a la gestió de tots els grups del catàleg.
     *
     * > S'obté la llista de tots els grups i permet administra-los.\n
     * > Amb els grups d'unitats podem: crear nous, editar, esborrar i gestionar els membre.\n
     * > Es poden gestionar els membres del grup lectors i dels gestors (només els gestors administradors).\n
     * > Es poden consultar els membres de la resta de grups generals del catàleg (genèrics, personals, editors...)
     *
     * @return void Plantilla *Cataleg_admin_groupsgest.tpl*
     */
    public function groupsgest() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gestor = (SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) ? true : false;
        $this->view->assign('gestor', $gestor);
        $GroupsUnits = ModUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        $this->view->assign('GroupsUnits', $GroupsUnits);
        $grupsZikula = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
        $this->view->assign('grupsZikula', $grupsZikula);
        return $this->view->fetch('admin/Cataleg_admin_groupsgest.tpl');
    }

    /**
     * Accedeix a la gestió de paràmetres de configuració del mòdul
     *
     * > Hi ha un bloc de funcions d'accés restringit al Gestor Administador i un altre pels Administradors de l'Entorn.\n
     * > El Gestor Administrador pot importar catàlegs, gestionar les equivalències i administrar el bloc de novetats.\n
     * > L'administrador de l'entorn pot assignar els grups de Zikula generals del catàleg, gestionar la taula auxiliar i veure informació del php.
     *
     * @return void Plantilla *Cataleg_admin_modulesettings.tpl*
     */
    public function modulesettings() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $level = 0;
        if (SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            $level = 1;
        }
        if (SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            $level = 2;
        }
        // Obtenció variable de mòdul relativa a la visualització del bloc
        // de novetats en el catàleg i data de publicació
        $novetats = ModUtil::getVar($this->name, 'novetats');

        // Get the view object
        $view = Zikula_View::getInstance($this->name, false);
        // Per a la plantilla Cataleg_block_config.tpl
        $view->assign('dies', isset($novetats['diesNovetats']) ? $novetats['diesNovetats'] : 0);
        $view->assign('dp', isset($novetats['dataPublicacio']) ? $novetats['dataPublicacio'] : "");
        $view->assign('showNew', isset($novetats['showNew']) ? $novetats['showNew'] : FALSE);
        $view->assign('showMod', isset($novetats['showMod']) ? $novetats['showMod'] : FALSE);

        $view->assign('level', $level);
        return $view->fetch('admin/Cataleg_admin_modulesettings.tpl');
    }

    /**
     * Estableix el catàleg actiu
     *
     * > El catàleg indicat passa a ser l'actiu i a estar en estat Obert.
     *
     * @param integer $catIdNouAc catId del catàleg que passarà a ser l'actiu
     *
     * @return void Retorna a la plantilla *Cataleg_admin_catalegsgest.tpl*
     */
    public function setActiveCataleg($catIdNouAc) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catIdNouAc = FormUtil::getPassedValue('catIdNouAc', null, 'REQUEST');
        modUtil::apiFunc('Cataleg', 'admin', 'setActiveCataleg', $catIdNouAc);
        $this->redirect(ModUtil::url('Cataleg', 'admin', 'catalegsgest'));
    }

    /**
     * Estableix el catàleg de treball
     *
     * > El catàleg indicat passa a ser el de treball.\n
     * > Si estava en estat tancat, el catàleg passa a estat 'Les Meves'
     *
     * @param integer $catIdNouTr catId del catàleg que passarà a ser el de treball
     *
     * @return void Retorna a la plantilla *Cataleg_admin_catalegsgest.tpl*
     */
    public function setTreballCataleg($catIdNouTr) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catIdNouTr = FormUtil::getPassedValue('catIdNouTr', null, 'REQUEST');
        modUtil::apiFunc('Cataleg', 'admin', 'setTreballCataleg', $catIdNouTr);
        $this->redirect(ModUtil::url('Cataleg', 'admin', 'catalegsgest'));
    }

    /**
     * Creació de un nou catàleg
     *
     * @return void Plantilla *Cataleg_admin_addeditCat.tpl* per introduir les dades
     */
    public function addCat() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $this->view->assign('edit', false);
        return $this->view->fetch('admin/Cataleg_admin_addeditCat.tpl');
    }

    /**
     * Edició d'un catàleg
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** catId del catàleg a editar.
     *
     * @return void Plantilla *Cataleg_admin_addeditCat.tpl* amb les dades del catàleg per poder-les editar
     */
    public function editCat() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catId = FormUtil::getPassedValue('catId', null, 'GET');
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));
        $this->view->assign('edit', true);
        $this->view->assign('cat', $cat);
        return $this->view->fetch('admin/Cataleg_admin_addeditCat.tpl');
    }

    /**
     * Creació i/o Edició d'un catàleg
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *cataleg*-
     * * integer **catId** [opcional definit-edició/no_definit-creació]
     * * string **anyAcad** 
     * * string **nom**
     * * integer **editable** [opcional][per defecte 0]
     * * integer **estat**
     *
     * @return void Retorna a la plantilla *Cataleg_admin_catalegsgest.tpl* després de desar les dades
     */
    public function addeditCat() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['catId'] = FormUtil::getPassedValue('catId', null, 'POST');
        $item['anyAcad'] = FormUtil::getPassedValue('anyAcad', null, 'POST');
        $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
        $item['estat'] = FormUtil::getPassedValue('estat', null, 'POST');
        $item['editable'] = FormUtil::getPassedValue('editable', 0, 'POST');
        if (($item['anyAcad'] == '') || ($item['nom'] == '') || !isset($item['estat'])) {
            LogUtil::registerError($this->__('Falten dades per a poder crear el catàleg.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'catalegsgest'));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveCataleg', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('El catàleg s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('El catàleg s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar el catàleg.'));
        }

        return system::redirect(ModUtil::url('Cataleg', 'admin', 'catalegsgest'));
    }

    /**
     * Esborra un catàleg
     * 
     * > Esborra el registre de la taula *cataleg*.\n
     * > No esborra el catàleg si té eixos definits.\n
     * > No esborra el catàleg si té unitats definides.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** catId del catàleg a esborrar.
     *
     * @return void Retorna a la plantilla *Cataleg_admin_catalegsgest.tpl* amb els missatges d'execució
     */
    public function deleteCat() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catId = FormUtil::getPassedValue('catId', null, 'GET');
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getAllEixos', array('catId' => $catId, 'all' => true));
        $unitat = modUtil::apiFunc('Cataleg', 'user', 'getAllUnits', array('catId' => $catId, 'all' => true));
        if (!empty($eix)) {
            LogUtil::registerError($this->__('Aquest catàleg té línies prioritàries definides.'));
            $err = 1;
        }
        if (!empty($unitat)) {
            LogUtil::registerError($this->__('Aquest catàleg té unitats definides.'));
            $err = 1;
        }
        if ($err == 0) {
            $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'cataleg', 'id' => $catId));
        }
        if ($del) {
            LogUtil::registerStatus($this->__('El catàleg s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar el catàleg.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'catalegsgest'));
    }

    /**
     * Accedeix a la gestió de tots els eixos d'un catàleg
     *
     * > S'obté la llista de tots els eixos i permet administrar-los
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** catId del catàleg triat.
     *

     * @return void Plantilla *Cataleg_admin_eixosgest.tpl*
     */
    public function eixosgest() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catId = FormUtil::getPassedValue('catId', null, 'GET');
        $eixos = ModUtil::apiFunc('Cataleg', 'user', 'getAllEixos', array('catId' => $catId, 'all' => true));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));
        $this->view->assign('eixos', $eixos);
        $this->view->assign('cat', $cat);
        return $this->view->fetch('admin/Cataleg_admin_eixosgest.tpl');
    }

    /**
     * Creació d'un nou eix
     *
     * > Obre el formulari per a crear un eix del catàleg triat.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** catId del catàleg triat.
     *

     * @return void Plantilla *Cataleg_admin_addeditEix.tpl* per a introduir les dades
     */
    public function addEix() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catId = FormUtil::getPassedValue('catId', null, 'GET');
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));
        $this->view->assign('edit', false);
        $this->view->assign('cat', $cat);
        return $this->view->fetch('admin/Cataleg_admin_addeditEix.tpl');
    }

    /**
     * Edició d'un eix
     *
     * > Obre el formulari per a editar l'eix triat amb les dades que tenia.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **eixId** eixId de l'eix triat.
     *

     * @return void Plantilla *Cataleg_admin_addeditEix.tpl* per a editar les dades
     */
    public function editEix() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $eixId = FormUtil::getPassedValue('eixId', null, 'GET');
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $eixId));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $this->view->assign('edit', true);
        $this->view->assign('eix', $eix);
        $this->view->assign('cat', $cat);
        return $this->view->fetch('admin/Cataleg_admin_addeditEix.tpl');
    }

    /**
     * Creació i/o Edició d'un eix
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *cataleg_eixos*-
     * * integer **eixId** [opcional definit-edició/no_definit-creació]
     * * integer **catId**
     * * string **ordre** 
     * * string **nom**
     * * string **nomCurt**
     * * string **descripcio** [opcional][per defecte null]
     * * integer **visible** [opcional][per defecte 0]
     *
     * @return void Retorna a la funció *eixosgest* després de desar les dades
     */
    public function addeditEix() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['catId'] = FormUtil::getPassedValue('catId', null, 'POST');
        $item['eixId'] = FormUtil::getPassedValue('eixId', null, 'POST');
        $item['ordre'] = FormUtil::getPassedValue('ordre', null, 'POST');
        $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
        $item['nomCurt'] = FormUtil::getPassedValue('nomCurt', null, 'POST');
        $item['descripcio'] = FormUtil::getPassedValue('descripcio', null, 'POST');
        $item['visible'] = FormUtil::getPassedValue('visible', 0, 'POST');
        if (($item['ordre'] == '') || ($item['nomCurt'] == '') || ($item['nom'] == '')) {
            LogUtil::registerError($this->__('S\'ha d\'informar de l\'ordre, el nom i el nom curt per a poder desar l\'eix.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'eixosgest', array('catId' => $item['catId'])));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveEix', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('L\'eix s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('L\'eix s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar l\'eix.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'eixosgest', array('catId' => $item['catId'])));
    }

    /**
     * Esborra un eix
     * 
     * > Esborra el registre de la taula *cataleg_eixos*.\n
     * > No esborra l'eix si té prioritats definides.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **eixId** eixId de l'eix a esborrar.
     *
     * @return void Retorna a la funció *eixosgest* amb els missatges d'execució
     */
    public function deleteEix() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $eixId = FormUtil::getPassedValue('eixId', null, 'GET');
        $prioritats = modUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsEix', array('eixId' => $eixId, 'all' => true));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $eixId));
        if (!empty($prioritats)) {
            LogUtil::registerError($this->__('Aquest eix té prioritats definides.'));
        } else {
            $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'eix', 'id' => $eixId));
        }
        if ($del) {
            LogUtil::registerStatus($this->__('L\'eix s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar l\'eix.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'eixosgest', array('catId' => $eix['catId'])));
    }

    /**
     * Accedeix a la gestió de totes les prioritats d'un eix.
     *
     * > S'obté la llista de totes les prioritats i permet administrar-les
     *
     * ### Paràmetres rebuts per GET:
     * * integer **eixId** eixId de l'eix triat.
     *

     * @return void Plantilla *Cataleg_admin_prioritatsgest.tpl*
     */
    public function prioritatsgest() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $eixId = FormUtil::getPassedValue('eixId', null, 'GET');
        $prioritats = modUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsEix', array('eixId' => $eixId, 'all' => true));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $eixId));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $this->view->assign('prioritats', $prioritats);
        $this->view->assign('eix', $eix);
        $this->view->assign('cat', $cat);
        return $this->view->fetch('admin/Cataleg_admin_prioritatsgest.tpl');
    }

    /**
     * Creació d'una nova prioritat
     *
     * > Obre el formulari per a crear una prioritat de l'eix triat.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **eixId** eixId de l'eix triat.
     *

     * @return void Plantilla *Cataleg_admin_addeditPrior.tpl* per a introduir les dades
     */
    public function addPrior() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $eixId = FormUtil::getPassedValue('eixId', null, 'GET');
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $eixId));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $this->view->assign('edit', false);
        $this->view->assign('cat', $cat);
        $this->view->assign('eix', $eix);
        return $this->view->fetch('admin/Cataleg_admin_addeditPrior.tpl');
    }

    /**
     * Edició d'una prioritat
     *
     * > Obre el formulari per a editar la prioritat triada amb les dades que tenia.\n
     * > També carrega la informació de subprioritats i unitats implicades per a poder gestionar-la.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **priId** priId de la prioritat triada.
     *

     * @return void Plantilla *Cataleg_admin_addeditPrior.tpl* per a editar les dades
     */
    public function editPrior() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $priId = FormUtil::getPassedValue('priId', null, 'GET');
        $impunits = modUtil::apiFunc('Cataleg', 'user', 'getImpunits', $priId);
        $subpriors = modUtil::apiFunc('Cataleg', 'user', 'getAllSubprioritats', array('priId' => $priId, 'all' => true));
        $prior = modUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $priId));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $prior['eixId']));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $units = modUtil::apiFunc('Cataleg', 'user', 'getAllUnits', array('catId' => $eix['catId'], 'all' => true));
        $this->view->assign('edit', true);
        $this->view->assign('impunits', $impunits);
        $this->view->assign('subpriors', $subpriors);
        $this->view->assign('prior', $prior);
        $this->view->assign('eix', $eix);
        $this->view->assign('cat', $cat);
        $this->view->assign('units', $units);
        return $this->view->fetch('admin/Cataleg_admin_addeditPrior.tpl');
    }

    /**
     * Creació i/o Edició d'una prioritat
     * 
     * > Desa les dades de la prioritat (nova o editada).\n
     * > No es desen canvis en subprioritats o unitats implicades en aquesta acció.
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula cataleg_prioritats-
     * * integer **priId** [opcional definit-edició/no_definit-creació]
     * * integer **eixId**
     * * integer **ordre** 
     * * string **nom**
     * * string **nomCurt**
     * * string **orientacions** [opcional][per defecte null]
     * * string **recursos** [opcional][per defecte null]
     * * integer **visible** [opcional][per defecte 0]
     *
     * @return void Retorna a la funció *prioritatsgest* després de desar les dades
     */
    public function addeditPrior() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['priId'] = FormUtil::getPassedValue('priId', null, 'POST');
        $item['eixId'] = FormUtil::getPassedValue('eixId', null, 'POST');
        $item['ordre'] = FormUtil::getPassedValue('ordre', null, 'POST');
        $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
        $item['nomCurt'] = FormUtil::getPassedValue('nomCurt', null, 'POST');
        $item['orientacions'] = FormUtil::getPassedValue('orientacions', null, 'POST');
        $item['recursos'] = FormUtil::getPassedValue('recursos', null, 'POST');
        $item['visible'] = FormUtil::getPassedValue('visible', 0, 'POST');
        if (($item['ordre'] == '') || ($item['nomCurt'] == '') || ($item['nom'] == '')) {
            LogUtil::registerError($this->__('S\'ha d\'informar de l\'ordre, el nom i el nom curt per a poder desar la prioritat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'prioritatsgest', array('eixId' => $item['eixId'])));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'savePrioritat', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('La prioritat s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('La prioritat s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar la prioritat.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'prioritatsgest', array('eixId' => $item['eixId'])));
    }

    /**
     * Esborra una prioritat
     * 
     * > Esborra el registre de la taula *cataleg_prioritats*.\n
     * > No esborra la prioritat si té activitats associades.\n
     * > No esborra la prioritat si té relacions d'importació establertes.\n
     * > Esborra també les subprioritats i/o unitats implicades que tingui.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **priId** priId de la prioritat a esborrar.
     *
     * @return void Retorna a la funció *prioritatsgest* amb els missatges d'execució
     */
    public function deletePrior() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $priId = FormUtil::getPassedValue('priId', null, 'GET');
        $prior = modUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $priId));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $prior['eixId']));
        $activitats = modUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatActivitats', array('priId' => $priId, 'catId' => $eix['catId']));
        $args['tipus'] = 'pri';
        $args['id'] = $priId;
        $equi = ModUtil::apiFunc('Cataleg', 'admin', 'teEquivalencies', $args);
        if (!empty($activitats)) {
            LogUtil::registerError($this->__('Aquesta prioritat té activitats associades.'));
        } elseif (!empty($equi)) {
            LogUtil::registerError($this->__('Aquesta prioritat té establertes equivalències per a la importació.'));
        } else {
            $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'prioritat', 'id' => $priId));
            $del2 = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'allSubprioritatsPrioritat', 'id' => $priId));
            $del3 = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'allImpunitsPrioritat', 'id' => $priId));
        }
        if ($del) {
            LogUtil::registerStatus($this->__('La prioritat s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar la prioritat.'));
        }
        if ($del2) {
            LogUtil::registerStatus($this->__('S\'han esborrat les subprioritats corresponents.'));
        }
        if ($del3) {
            LogUtil::registerStatus($this->__('S\'han esborrat les persones de contacte corresponents.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'prioritatsgest', array('eixId' => $eix['eixId'])));
    }

    /**
     * Creació d'una nova subprioritat
     *
     * > Obre el formulari per a crear una subprioritat de la prioritat triada.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **priId** priId de la prioritat triada.
     *

     * @return void Plantilla *Cataleg_admin_addeditSubprior.tpl* per a introduir les dades
     */
    public function addSubprior() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $priId = FormUtil::getPassedValue('priId', null, 'GET');
        $prior = modUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $priId));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $prior['eixId']));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $this->view->assign('edit', false);
        $this->view->assign('cat', $cat);
        $this->view->assign('eix', $eix);
        $this->view->assign('prior', $prior);
        return $this->view->fetch('admin/Cataleg_admin_addeditSubprior.tpl');
    }

    /**
     * Edició d'una subprioritat.
     *
     * > Obre el formulari per a editar la subprioritat triada amb les dades que tenia.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **sprId** sprId de la subprioritat triada.
     *

     * @return void Plantilla *Cataleg_admin_addeditSubprior.tpl* per a editar les dades
     */
    public function editSubprior($sprId) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $sprId = FormUtil::getPassedValue('sprId', null, 'GET');
        $subprior = modUtil::apiFunc('Cataleg', 'user', 'getSubprioritat', array('sprId' => $sprId));
        $prior = modUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $subprior['priId']));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $prior['eixId']));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $this->view->assign('edit', true);
        $this->view->assign('subprior', $subprior);
        $this->view->assign('prior', $prior);
        $this->view->assign('eix', $eix);
        $this->view->assign('cat', $cat);
        return $this->view->fetch('admin/Cataleg_admin_addeditSubprior.tpl');
    }

    /**
     * Creació i/o Edició d'una subprioritat
     * 
     * > Desa les dades de la subprioritat (nova o editada).\n
     * > Torna a la gestió de subprioritats, que es fa des de l'edició de la prioritat corresponent.
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *cataleg_subprioritats*-
     * * integer **sprId** [opcional definit-edició/no_definit-creació]
     * * integer **priId**
     * * string **ordre** 
     * * string **nom**
     * * string **nomCurt**
     * * integer **visible** [opcional][per defecte 0]
     *
     * @return void Retorna a la funció *editPrior* després de desar les dades
     */
    public function addeditSubprior() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['sprId'] = FormUtil::getPassedValue('sprId', null, 'POST');
        $item['priId'] = FormUtil::getPassedValue('priId', null, 'POST');
        $item['ordre'] = FormUtil::getPassedValue('ordre', null, 'POST');
        $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
        $item['nomCurt'] = FormUtil::getPassedValue('nomCurt', null, 'POST');
        $item['visible'] = FormUtil::getPassedValue('visible', 0, 'POST');
        if (($item['ordre'] == '') || ($item['nomCurt'] == '') || ($item['nom'] == '')) {
            LogUtil::registerError($this->__('S\'ha d\'informar de l\'ordre, el nom i el nom curt per a poder desar la subprioritat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'editPrior', array('priId' => $item['priId'])));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveSubprioritat', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('La subprioritat s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('La subprioritat s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar la subprioritat.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'editPrior', array('priId' => $item['priId'])));
    }

    /**
     * Esborra una subprioritat
     * 
     * > Esborra el registre de la taula *cataleg_subprioritats*.\n
     * > No esborra la subprioritat si té activitats associades.\n
     * > No esborra la subprioritat si té relacions d'importació establertes.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **priId** priId de la prioritat a esborrar.
     *
     * @return void Retorna a la funció *editPrior* amb els missatges d'execució
     */
    public function deleteSubprior() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $sprId = FormUtil::getPassedValue('sprId', null, 'GET');
        $subprior = ModUtil::apiFunc('Cataleg', 'user', 'getSubprioritat', array('sprId' => $sprId));
        $activitats = ModUtil::apiFunc('Cataleg', 'user', 'getAllSubprioritatActivitats', array('sprId' => $sprId));
        $args['tipus'] = 'spr';
        $args['id'] = $sprId;
        $equi = ModUtil::apiFunc('Cataleg', 'admin', 'teEquivalencies', $args);
        if (!empty($activitats)) {
            LogUtil::registerError($this->__('Aquesta subprioritat té activitats associades.'));
        } elseif (!empty($equi)) {
            LogUtil::registerError($this->__('Aquesta subprioritat té establertes equivalències per a la importació.'));
        } else {
            $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'subprioritat', 'id' => $sprId));
        }
        if ($del) {
            LogUtil::registerStatus($this->__('La subprioritat s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar la subprioritat.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'editPrior', array('priId' => $subprior['priId'])));
    }

    /**
     * Creació d'una nova unitat implicada
     *
     * > Obre el formulari per a crear una unitat implicada (temàtica) de la prioritat triada.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **priId** priId de la prioritat triada.
     *

     * @return void Plantilla *Cataleg_admin_addeditImpunit.tpl* per a introduir les dades
     */
    public function addImpunit() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            $uniId = FormUtil::getPassedValue('uid', null, 'GET');
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $uniId)))){
                return LogUtil::registerPermissionError();
            }
        }
        $priId = FormUtil::getPassedValue('priId', null, 'GET');
        $prior = modUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $priId));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $prior['eixId']));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $units = modUtil::apiFunc('Cataleg', 'user', 'getAllUnits', array('catId' => $eix['catId'], 'all' => true));
        if (empty($units)) {
            LogUtil::registerError($this->__('No es pot crear cap unitat implicada, perquè el catàleg encara no té unitats definides.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'editPrior', array('priId' => $priId)));
        }
        $this->view->assign('edit', false);
        $this->view->assign('cat', $cat);
        $this->view->assign('eix', $eix);
        $this->view->assign('prior', $prior);
        $this->view->assign('units', $units);
        return $this->view->fetch('admin/Cataleg_admin_addeditImpunit.tpl');
    }

    /**
     * Edició d'una unitat implicada.
     *
     * > Obre el formulari per a editar la unitat implicada triada amb les dades que tenia.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **impunitId** impunitId de la unitat implicada triada.
     *

     * @return void Plantilla *Cataleg_admin_addeditImpunit.tpl* per a editar les dades
     */
    public function editImpunit($impunitId) {
        $uniId = FormUtil::getPassedValue('uid', null, 'GET');
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $uniId)))){
                return LogUtil::registerPermissionError();
            }
        }
        $impunitId = FormUtil::getPassedValue('impunitId', null, 'GET');
        $url = FormUtil::getPassedValue('url', null, 'GET');
        $impunit = modUtil::apiFunc('Cataleg', 'user', 'getImpunit', $impunitId);
        $prior = modUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $impunit['priId']));
        $eix = modUtil::apiFunc('Cataleg', 'user', 'getEix', array('eixId' => $prior['eixId']));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $eix['catId']));
        $units = modUtil::apiFunc('Cataleg', 'user', 'getAllUnits', array('catId' => $eix['catId'], 'all' => true));
        $this->view->assign('edit', true);
        $this->view->assign('impunit', $impunit);
        $this->view->assign('prior', $prior);
        $this->view->assign('eix', $eix);
        $this->view->assign('cat', $cat);
        $this->view->assign('units', $units);
        $this->view->assign('url', $url);
        $this->view->assign('uniId', $uniId);
        return $this->view->fetch('admin/Cataleg_admin_addeditImpunit.tpl');
    }

    /**
     * Creació i/o Edició d'una unitat implicada
     * 
     * > Desa les dades de la unitat implicada (nova o editada).\n
     * > Torna a la gestió de les unitats implicades, que es fa des de l'edició de la prioritat corresponent.
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *cataleg_unitatsImplicades*-
     * * integer **impunitId** [opcional definit-edició/no_definit-creació]
     * * integer **priId**
     * * integer **uniId**
     * * string **tematica** [opcional]
     * * string **pContacte**
     * * string **email** [opcional]
     * * string **telContacte** [opcional]
     * * integer **dispFormador** [opcional][per defecte 0]
     *
     * @return void Retorna a la funció *editPrior* després de desar les dades
     */
    public function addeditImpunit() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['impunitId'] = FormUtil::getPassedValue('impunitId', null, 'POST');
        $item['priId'] = FormUtil::getPassedValue('priId', null, 'POST');
        $item['uniId'] = FormUtil::getPassedValue('uniId', null, 'POST');
        $item['tematica'] = FormUtil::getPassedValue('tematica', null, 'POST');
        $item['pContacte'] = FormUtil::getPassedValue('pContacte', null, 'POST');
        $item['email'] = FormUtil::getPassedValue('email', null, 'POST');
        $item['telContacte'] = FormUtil::getPassedValue('telContacte', null, 'POST');
        $item['dispFormador'] = FormUtil::getPassedValue('dispFormador', 0, 'POST');
        if (($item['priId'] == '') || ($item['uniId'] == '') || ($item['pContacte'] == '')) {
            LogUtil::registerError($this->__('No s\'ha pogut desar per no arribar els paràmetres necessaris (prioritat i unitat).'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'editPrior', array('priId' => $item['priId'])));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveImpunit', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('La unitat implicada s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('La unitat implicada s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar la unitat implicada.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'editPrior', array('priId' => $item['priId'])));
    }

    /**
     * Esborra una unitat implicada
     * 
     * > Esborra el registre de la taula *cataleg_unitatsImplicades*.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **impunitId** impunitId de la unitat implicada a esborrar.
     *
     * @return void Retorna a la funció *editPrior* amb els missatges d'execució
     */
    public function deleteImpunit() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $impunitId = FormUtil::getPassedValue('impunitId', null, 'GET');
        $impunit = ModUtil::apiFunc('Cataleg', 'user', 'getImpunit', $impunitId);
        $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'impunit', 'id' => $impunitId));
        if ($del) {
            LogUtil::registerStatus($this->__('La unitat implicada s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar la unitat implicada.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'editPrior', array('priId' => $impunit['priId'])));
    }

    /**
     * Accedeix a la gestió de totes les unitats d'un catàleg
     *
     * > S'obté la llista de totes ls unitats i permet administrar-les
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** catId del catàleg triat.
     *
     * @return void Plantilla *Cataleg_admin_unitatsgest.tpl*
     */
    public function unitatsgest() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catId = FormUtil::getPassedValue('catId', null, 'GET');
        $unitats = ModUtil::apiFunc('Cataleg', 'user', 'getAllUnits', array('catId' => $catId, 'all' => true));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));
        $groups = ModUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        $this->view->assign('unitats', $unitats);
        $this->view->assign('cat', $cat);
        $this->view->assign('groups', $groups);
        return $this->view->fetch('admin/Cataleg_admin_unitatsgest.tpl');
    }

    /**
     * Creació d'una nova unitat
     *
     * > Obre el formulari per a crear una unitat del catàleg triat.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** catId del catàleg triat.
     *

     * @return void Plantilla *Cataleg_admin_addeditUnitat.tpl* per a introduir les dades
     */
    public function addUnitat() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $catId = FormUtil::getPassedValue('catId', null, 'GET');
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));
        $grups = modUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        $this->view->assign('edit', false);
        $this->view->assign('cat', $cat);
        $this->view->assign('grups', $grups);
        return $this->view->fetch('admin/Cataleg_admin_addeditUnitat.tpl');
    }

    /**
     * Edició d'una unitat
     *
     * > Obre el formulari per a editar la unitat triada amb les dades que tenia.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **uniId** uniId de la unitat triada.
     *

     * @return void Plantilla *Cataleg_admin_addeditUnitat.tpl* per a editar les dades
     */
    public function editUnitat() {
        $uniId = FormUtil::getPassedValue('uniId', null, 'GET');
        $unitat = modUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $uniId, 'simple' => true));
        $isEditor = false;
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            // Check if user have edit permissions
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $uniId)))){
                return LogUtil::registerPermissionError();
            } else {
                $isEditor = true;
            }
        }
        
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $unitat['catId']));
        $responsables = modUtil::apiFunc('Cataleg', 'user', 'getAllResponsablesUnitat', array('uniId' => $uniId));
        $grups = modUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        $this->view->assign('edit', true);
        $this->view->assign('unitat', $unitat);
        $this->view->assign('cat', $cat);
        $this->view->assign('grups', $grups);
        $this->view->assign('responsables', $responsables);
        $this->view->assign('isEditor', $isEditor);
        return $this->view->fetch('admin/Cataleg_admin_addeditUnitat.tpl');
    }

    /**
     * Creació i/o Edició d'una unitat
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *cataleg_unitats*-
     * * integer **uniId** [opcional definit-edició/no_definit-creació]
     * * integer **catId**
     * * string **nom**
     * * string **descripcio** [opcional][per defecte null]
     * * integer **activa** [opcional][per defecte 0]
     *
     * @return void Retorna a la funció *unitatsgest* després de desar les dades
     */
    public function addeditUnitat() {
        $item['uniId'] = FormUtil::getPassedValue('uniId', null, 'POST');
        $isEditor = false;
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            // Check if user have edit permissions
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $item['uniId'])))){
                LogUtil::registerStatus('No access');
                return LogUtil::registerPermissionError();
            } else {
                $isEditor = true;
            }
        }
        if ($isEditor) {
            $item['catId'] = FormUtil::getPassedValue('catId', null, 'POST');
            $item['descripcio'] = FormUtil::getPassedValue('descripcio', null, 'POST');
        } else {
            $item['catId'] = FormUtil::getPassedValue('catId', null, 'POST');
            $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
            $item['descripcio'] = FormUtil::getPassedValue('descripcio', null, 'POST');
            $item['gzId'] = FormUtil::getPassedValue('gzId', null, 'POST');
            $item['activa'] = FormUtil::getPassedValue('activa', 0, 'POST');
        }
        if (!$isEditor && ($item['nom'] == '')) {
            LogUtil::registerError($this->__('S\'ha d\'informar del nom per a poder desar la prioritat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'unitatsgest', array('catId' => $item['catId'])));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveUnitat', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('La unitat s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('La unitat s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar la unitat.'));
        }
        if ($isEditor)
            return system::redirect(ModUtil::url('Cataleg', 'user', 'view'));
        else    
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'unitatsgest', array('catId' => $item['catId'])));
        
    }

    /**
     * Esborra una unitat
     * 
     * > Esborra el registre de la taula *cataleg_unitats*.\n
     * > Esborra també els registres de la taula *cataleg_responsables* corresponents.\n
     * > No esborra la unitat si té activitats creades.\n
     * > No esborra la unitat si està definida com a unitat implicada d'alguna prioritat.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **uniId** uniId de la unitat a esborrar.
     *
     * @return void Retorna a la funció *unitatsgest* amb els missatges d'execució
     */
    public function deleteUnitat() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $uniId = FormUtil::getPassedValue('uniId', null, 'GET');
        $unitat = modUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $uniId, 'simple' => true));
        $activitats = modUtil::apiFunc('Cataleg', 'user', 'getAllUnitatActivitats', array('uniId' => $uniId, 'catId' => $unitat['catId']));
        $impunits = modUtil::apiFunc('Cataleg', 'user', 'getImpunitsUnitat', $uniId);
        if (!empty($activitats)) {
            LogUtil::registerError($this->__('Aquesta unitat té activitats creades.'));
            $err = 1;
        }
        if (!empty($impunit)) {
            LogUtil::registerError($this->__('Aquesta unitat és unitat implicada en alguna prioritat.'));
            $err = 1;
        }
        if ($err == 0) {
            $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'unitat', 'id' => $uniId));
            $del2 = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'allResponsablesUnitat', 'id' => $uniId));
        }
        if ($del) {
            LogUtil::registerStatus($this->__('La unitat s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar la unitat.'));
        }
        if ($del2) {
            LogUtil::registerStatus($this->__('S\'han esborrat les persones responsables corresponents.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'unitatsgest', array('catId' => $unitat['catId'])));
    }

    /**
     * Creació d'una nova persona responsable
     *
     * > Obre el formulari per a crear una persona responsable de la unitat triada.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **uniId** uniId de la unitat triada.
     *

     * @return void Plantilla *Cataleg_admin_addeditResponsable.tpl* per a introduir les dades
     */
    public function addResponsable() {
        $uniId = FormUtil::getPassedValue('uniId', null, 'GET');
        $isEditor = false;
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            // Check if user have edit permissions
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' =>  $uniId)))){
                return LogUtil::registerPermissionError();
            } else {
                $isEditor = true;
            }
        }
       
        $unitat = modUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $uniId, 'simple' => true));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $unitat['catId']));
        $this->view->assign('edit', false);
        $this->view->assign('cat', $cat);
        $this->view->assign('unitat', $unitat);
        return $this->view->fetch('admin/Cataleg_admin_addeditResponsable.tpl');
    }

    /**
     * Edició d'una persona responsable
     *
     * > Obre el formulari per a editar la persona responsable triada amb les dades que tenia.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **respunitId** respunitId de la persona responsable triada.
     *

     * @return void Plantilla *Cataleg_admin_addediResponsable.tpl* per a editar les dades
     */
    public function editResponsable() {
        $respunitId = FormUtil::getPassedValue('respunitId', null, 'GET');
        $responsable = modUtil::apiFunc('Cataleg', 'user', 'getResponsable', array('respunitId' => $respunitId));
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            // Check if user have edit permissions
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' =>  $responsable['uniId'])))){
                return LogUtil::registerPermissionError();
            } 
        }
        $respunitId = FormUtil::getPassedValue('respunitId', null, 'GET');
        $responsable = modUtil::apiFunc('Cataleg', 'user', 'getResponsable', array('respunitId' => $respunitId));
        $unitat = modUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $responsable['uniId'], 'simple' => true));
        $cat = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $unitat['catId']));
        $this->view->assign('edit', true);
        $this->view->assign('responsable', $responsable);
        $this->view->assign('unitat', $unitat);
        $this->view->assign('cat', $cat);
        $this->view->assign('isEditor', $isEditor);
        return $this->view->fetch('admin/Cataleg_admin_addeditResponsable.tpl');
    }

    /**
     * Creació i/o Edició d'una persona responsable
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *cataleg_responsables*-
     * * integer **respunitd** [opcional definit-edició/no_definit-creació]
     * * integer **uniId**
     * * integer **responsable**
     * * integer **email** [opcional]
     * * integer **telefon** [opcional]
     *
     * @return void Retorna a la funció *unitatsgest* després de desar les dades
     */
    public function addeditResponsable() {
        $item['respunitId'] = FormUtil::getPassedValue('respunitId', null, 'POST');
        $item['responsable'] = FormUtil::getPassedValue('responsable', null, 'POST');
        $item['email'] = FormUtil::getPassedValue('email', null, 'POST');
        $item['telefon'] = FormUtil::getPassedValue('telefon', null, 'POST');
        $item['uniId'] = FormUtil::getPassedValue('uniId', null, 'POST');
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            // Check if user have edit permissions
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $item['uniId'])))){
                return LogUtil::registerPermissionError();
            } 
        }
        
        if (($item['responsable'] == '')) {
            LogUtil::registerError($this->__('S\'ha d\'informar del nom de la persona responsable per a poder-la desar.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'editUnitat', array('uniId' => $item['uniId'])));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveResponsable', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('La persona responsable s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('La persona responsable s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar la persona responsable.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'editUnitat', array('uniId' => $item['uniId'])));
    }

    /**
     * Esborra una persona responsable
     * 
     * > Esborra el registre de la taula *cataleg_responsables*.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **respunitId** respunitId de la persona responsable a esborrar.
     *
     * @return void Retorna a la funció *editUnitat* amb els missatges d'execució
     */
    public function deleteResponsable() {
        $respunitId = FormUtil::getPassedValue('respunitId', null, 'GET');
        $responsable = modUtil::apiFunc('Cataleg', 'user', 'getResponsable', array('respunitId' => $respunitId));
        
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            if (!(ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $responsable['uniId'])))){
                return LogUtil::registerPermissionError();
            } 
        }
        
        $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'responsable', 'id' => $respunitId));
        if ($del) {
            LogUtil::registerStatus($this->__('La persona responsable s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar la persona responable.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'editUnitat', array('uniId' => $responsable['uniId'])));
    }

    /**
     * Creació d'un nou grup de zikula corresponent a una unitat
     *
     * > Obre el formulari per a crear un grup.
     *
     * @return void Plantilla *Cataleg_admin_addeditGroup.tpl* per a introduir les dades
     */
    public function addGroup() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $this->view->assign('edit', false);
        return $this->view->fetch('admin/Cataleg_admin_addeditGroup.tpl');
    }

    /**
     * Edició d'un grup de zikula corresponent a una unitat
     *
     * > Obre el formulari per a editar el grup triat amb les dades que tenia.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **gid** gid del grup de zikula a editar.
     *

     * @return void Plantilla *Cataleg_admin_addeditGroup.tpl* per a editar les dades
     */
    public function editGroup() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gid = FormUtil::getPassedValue('gid', null, 'GET');
        $GroupsUnits = ModUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        if (!isset($GroupsUnits[$gid])) {
            LogUtil::registerError($this->__('No existeix cap grup d\'usuaris d\'unitats del catàleg amb l\'identificador demanat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
        }
        $grupUnitat = $GroupsUnits[$gid];
        $this->view->assign('edit', true);
        $this->view->assign('grupUnitat', $grupUnitat);
        return $this->view->fetch('admin/Cataleg_admin_addeditGroup.tpl');
    }

    /**
     * Creació i/o Edició d'un grup de zikula corresponent a una unitat
     * 
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *groups*-
     * * integer **gid** [opcional definit-edició/no_definit-creació]
     * * string **name**
     * * string **description**
     *
     * @return void Retorna a la funció *groupsgest* després de desar les dades
     */
    public function addeditGroup() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gid = FormUtil::getPassedValue('gid', null, 'POST');
        $GroupsUnits = ModUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        if (($gid > 0) && !isset($GroupsUnits[$gid])) {
            LogUtil::registerError($this->__('No existeix cap grup d\'usuaris d\'unitats del catàleg amb l\'identificador demanat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
        }
        $item['gid'] = $gid;
        $item['name'] = FormUtil::getPassedValue('name', null, 'POST');
        $item['description'] = FormUtil::getPassedValue('description', null, 'POST');
        if (($item['name'] == '') || ($item['description'] == '')) {
            LogUtil::registerError($this->__('Falten dades per a poder desar el grup.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveGroup', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('El grup s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('El grup s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar el grup.'));
        }

        return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
    }

    /**
     * Creació d'un nou usuari
     *
     * > Obre el formulari per a crear un usuari del catàleg.
     *
     * @return void Plantilla *Cataleg_admin_addeditUser.tpl* per a introduir les dades
     */
    public function addUser() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN) ? $gestor = true : $gestor = false;
        $grupCat = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
        $allGroupsUnits = ModUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        $minpass = ModUtil::getVar('Users', 'minpass');
        $gtafInfo = ModUtil::apiFunc($this->name,'admin','getGtafInfo');
        $this->view->assign('gtafInfo',$gtafInfo);
        $this->view->assign('edit', false);
        $this->view->assign('minpass', $minpass);
        $this->view->assign('gestor', $gestor);
        $this->view->assign('grupCat', $grupCat);
        $this->view->assign('allGroupsUnits', $allGroupsUnits);
        return $this->view->fetch('admin/Cataleg_admin_addeditUser.tpl');
    }

    /**
     * Edició d'un usuari del catàleg
     *
     * > Obre el formulari per a editar l'usuari triat amb les dades que tenia.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **uid** uid de l'usuari triat.
     *

     * @return void Plantilla *Cataleg_admin_addeditUser.tpl* per a editar les dades
     */
    public function editUser() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gestor = (SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) ? true : false;
        $uid = FormUtil::getPassedValue('uid', null, 'GET');
        $grupCat = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
        $allGroupsUnits = ModUtil::apiFunc('Cataleg', 'admin', 'getAllGroupsUnits');
        $catUsersList = UserUtil::getUsersForGroup($grupCat['Sirius']);
        if (!in_array($uid, $catUsersList)) {
            LogUtil::registerError($this->__('No existeix cap usuari del catàleg amb l\'identificador indicat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
        }
        //Només els gestors-administradors poden editar altres gestors
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            $gestorUsersList = UserUtil::getUsersForGroup($grupCat['Gestors']);
            if (in_array($uid, $gestorUsersList)) {
                LogUtil::registerError($this->__('Només els gestors-administradors poden editar altres gestors.'));
                return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
            }
        }
        $user = UserUtil::getVars($uid);
        $user['iw'] = DBUtil::selectObject('IWusers', 'iw_uid = ' . $uid);
        $user['pw'] = (DBUtil::selectField('users','pass','uid = '.$uid) != '' ? true: false);
        $grups = UserUtil::getGroupsForUser($uid);
        $uni = array();
        foreach ($grups as $grup) {
            ($grupCat['Generics'] == $grup) ? $gr['Generics'] = 1 : false;
            ($grupCat['Personals'] == $grup) ? $gr['Personals'] = 1 : false;
            ($grupCat['Gestform'] == $grup) ? $gr['Gestform'] = 1 : false;
            ($grupCat['LectorsCat'] == $grup) ? $gr['LectorsCat'] = 1 : false;
            ($grupCat['EditorsCat'] == $grup) ? $gr['EditorsCat'] = 1 : false;
            ($grupCat['Gestors'] == $grup) ? $gr['Gestors'] = 1 : false;
            ($grupCat['Odissea'] == $grup) ? $gr['Odissea'] = 1 : false;
            ($grupCat['Cert'] == $grup) ? $gr['Cert'] = 1 : false;
            ($grupCat['gA'] == $grup) ? $gr['gA'] = 1 : false;
            ($grupCat['gB'] == $grup) ? $gr['gB'] = 1 : false;
            if (isset($allGroupsUnits[$grup])) {
                $uni[$grup]['gid'] = $grup;
                $uni[$grup]['name'] = $allGroupsUnits[$grup]['name'];
            }
        }
        $user['gr'] = $gr;
        $user['uni'] = $uni;
        $minpass = ModUtil::getVar('Users', 'minpass');
        $gtafInfo = ModUtil::apiFunc($this->name,'admin','getGtafInfo');
        $this->view->assign('gtafInfo',$gtafInfo);
        $this->view->assign('edit', true);
        $this->view->assign('minpass', $minpass);
        $this->view->assign('gestor', $gestor);
        $this->view->assign('user', $user);
        $this->view->assign('grupCat', $grupCat);
        $this->view->assign('allGroupsUnits', $allGroupsUnits);
        return $this->view->fetch('admin/Cataleg_admin_addeditUser.tpl');
    }

    /**
     * Creació i/o Edició d'un usuari del catàleg
     *
     * ### Paràmetres rebuts per POST:
     * -Corresponents als diferents camps de la taula *users*-
     * * integer **uid** [opcional definit-edició/no_definit-creació]
     * * string **uname**
     * 
     * -Corresponents als diferents camps de la taula *iw_users*-
     * * string **iw_nom**
     * * string **iw_cognom1**
     * * string **iw_cognom2**
     * 
     * -Per gestionar les grups relacionats amb el catàleg-
     * * array **groups**
     * 
     * -Per gestionar la contrasenya de l'usuari-
     * * string **password**
     * * string **rpassword**
     * * string **changeme**
     *
     * @return void Retorna a la funció *usergest* després de desar les dades
     */
    public function addeditUser() {
        //Comprovacions de seguretat. Només els gestors poden crear i editar usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        // Primer desem les modificacions de les dades d'usuari a users i a IWusers i reassignem els grups de l'usuari
        $user['zk']['uid'] = FormUtil::getPassedValue('uid', null, 'POST');
        //Comprovem si es passa una uid (per editar) o no (i s'ha de crear un nou usuari)
        if (!empty($user['zk']['uid'])) {
            //Comprovem que aquest usuari eixisteixi i es pugui editar (és a dir, que sigui del grup d'usuaris del catàleg)
            $grupCat = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
            $catUsersList = UserUtil::getUsersForGroup($grupCat['Sirius']);
            if (!in_array($user['zk']['uid'], $catUsersList)) {
                LogUtil::registerError($this->__('No existeix cap usuari del catàleg amb l\'identificador indicat.'));
                return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
            }
            $user['iw']['uid'] = $user['zk']['uid'];
            $user['iw']['suid'] = $user['zk']['uid'];
            $r = 'edit';
        }
        $user['zk']['uname'] = FormUtil::getPassedValue('uname', null, 'POST');
        //Comprovem que no existeix cap usuari amb aquest uname
        if (!empty($user['zk']['uid'])) {
            $where = "uname = '" . $user['zk']['uname'] . "' AND uid != " . $user['zk']['uid'];
        } else {
            $where = "uname = '" . $user['zk']['uname'] . "'";
        }
        $uname = UserUtil::getUsers($where);
        if ($uname) {
            LogUtil::registerError($this->__('El nom d\'usuari triat ja existeix.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
        }
        $user['zk']['email'] = FormUtil::getPassedValue('email', null, 'POST');
        $user['iw']['nom'] = FormUtil::getPassedValue('iw_nom', null, 'POST');
        $user['iw']['cognom1'] = FormUtil::getPassedValue('iw_cognom1', null, 'POST');
        $user['iw']['cognom2'] = FormUtil::getPassedValue('iw_cognom2', null, 'POST');
        $user['gr'] = FormUtil::getPassedValue('groups', null, 'POST');
        
        $prev_pass = FormUtil::getPassedValue('prev_pass', 0, 'POST');
        $setpass = FormUtil::getPassedValue('setpass', 0, 'POST');
        if ($setpass == 1) {
            $password = FormUtil::getPassedValue('password', null, 'POST');
            $changeme = FormUtil::getPassedValue('changeme', 0, 'POST');
        } else {
            $password = null;
        }
        $setcode = FormUtil::getPassedValue('setcode', 0, 'POST');
        if ($setcode == 1) $iwcode = FormUtil::getPassedValue('iwcode_s', null, 'POST');
        if ($setcode == 2) $iwcode = FormUtil::getPassedValue('iwcode_m', null, 'POST');
        if ($iwcode) {
            $user['iw']['code'] = $iwcode;
        } elseif ($r == 'edit'){
            $iwcode = DBUtil::selectField('IWusers', 'code', 'iw_uid='.$user['zk']['uid']);
        }
        if ($iwcode) {
            $gtafInfo = ModUtil::apiFunc('Cataleg','admin','getGtafEntity',$iwcode);
            $grupCat = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
            if (isset($grupCat[$gtafInfo['entity']['tipus']])) $user['gr'][] = $grupCat[$gtafInfo['entity']['tipus']];
        }
        $insertUserId = ModUtil::apifunc('Cataleg', 'admin', 'saveUser', $user);
        if ($insertUserId) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('L\'usuari s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('L\'usuari s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar l\'usuari.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
        }
        //Si es tria 'buidar' la contrasenya, aquesta opció mana sobre el canvi i forçar el canvi
        if ($setpass == 2) {
            $reg = array('pass' => '');
            if (DBUtil::updateObject($reg,'users','uid ='. $insertUserId)) {
                UserUtil::setVar('', $passreminder, $insertUserId);
                LogUtil::registerStatus($this->__('L\'usuari haurà de validar-se per LDAP'));
            }
        }
        // Segon pas: desem el possible canvi de contrasenya
        if ($password) {
            $rpassword = FormUtil::getPassedValue('rpassword', null, 'POST');
            $passreminder = $this->__('Constasenya establerta des de l\'administració.');
            $passwordErrors = ModUtil::apiFunc('Users', 'registration', 'getPasswordErrors', array(
                        'uname' => $user['zk']['uname'],
                        'pass' => $password,
                        'passagain' => $rpassword,
                        'passreminder' => $passreminder
            ));
            if (empty($passwordErrors)) {
                if (UserUtil::setPassword($password, $insertUserId)) {
                    UserUtil::setVar('passreminder', $passreminder, $insertUserId);
                    LogUtil::registerStatus($this->__('S\'ha desat la contrasenya.'));
                }
            } else {
                LogUtil::registerError($this->__('No s\'ha desat la contrasenya.'));
                LogUtil::registerError($passwordErrors['pass']);
            }
        }
        // Tercer pas: establim la variable que controla el forçar el canvi de contrasenya
        if ($setpass == 1 && ($prev_pass || $password)) {
            UserUtil::setVar('_Users_mustChangePassword', $changeme, $insertUserId);
            if ($changeme == 1)
                LogUtil::registerStatus($this->__('L\'usuari haurà de canviar la contrasenya en la propera validació.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
    }

    /**
     * 'Esborra' un usuari del catàleg
     * 
     * > No esborra realment l'usuari del sistema.\n
     * > Treu a l'usuari de tots els grups relacionats amb el catàleg.\n
     * > El fa membre del grup 'ExSirius'.\n
     * > Si l'usuari no té altres rols en el sistema (Sirius) quedarà sense accés.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **uid** uid de l'usuari a esborrar.
     *
     * @return void Retorna a la funció *usersgest* amb els missatges d'execució
     */
    public function deleteUser() {
        //Comprovacions de seguretat. Només els gestors poden crear i editar usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $uid = FormUtil::getPassedValue('uid', null, 'GET');
        //Només es poden esborrar els usuaris del catàleg
        $grupCat = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
        $catUsersList = UserUtil::getUsersForGroup($grupCat['Sirius']);
        if (!in_array($uid, $catUsersList)) {
            LogUtil::registerError($this->__('No existeix cap usuari del catàleg amb l\'identificador indicat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
        }
        //Només els gestors-administradors poden esborrar altres gestors
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            $gestorUsersList = UserUtil::getUsersForGroup($grupCat['Gestors']);
            if (in_array($uid, $gestorUsersList)) {
                LogUtil::registerError($this->__('Només els gestors-administradors poden esborrar altres gestors.'));
                return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
            }
        }
        //Reassignem els grups assignats del catàleg. El mourem a ser membre del grup ExSirius
        //Netegem l'assignació de grups relacinats amb el catàleg
        $grupsUnitats = ModUtil::getVar('Cataleg', 'grupsUnitats');
        $grupsZikula = ModUtil::getVar('Cataleg', 'grupsZikula');
        foreach ($grupsUnitats as $grup) {
            $where = 'gid = ' . $grup . ' AND uid = ' . $uid;
            DBUtil::deleteWhere('group_membership', $where);
        }
        foreach ($grupsZikula as $grup) {
            $where = 'gid = ' . $grup . ' AND uid = ' . $uid;
            DBUtil::deleteWhere('group_membership', $where);
        }
        //Assignem el grup ExSirius
        $item = array('gid' => $grupsZikula['ExSirius'], 'uid' => $uid);
        DBUtil::insertObject($item, 'group_membership');
        //Tornem a la gestió d'usuaris
        LogUtil::registerStatus($this->__('L\'usuari ja no té accés al catàleg. Es pot gestionar des de l\'apartat d\'ex-usuaris.'));
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
    }

    /**
     * Incorpora un ex-usuari al catàleg
     * 
     * > Treu a l'usuari triat del grup 'ExSirius' i el fa membre de 'Catàleg'.\n
     * > Obre l'edició de l'usuari per a concretar els nous grups d'accés.\n
     * > Fins que no es desin els canvis, l'usuari no tindrà la resta de grups que li donin accés al catàleg.\n
     *
     * ### Paràmetres rebuts per GET:
     * * integer **uid** uid de l'usuari a incorporar.
     *
     * @return void Retorna a la funció *editUser* per a editar l'usuari incorporat
     */
    public function incorporaUser() {
        //Comprovacions de seguretat. Només els gestors poden crear i editar usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $uid = FormUtil::getPassedValue('uid', null, 'GET');
        //Només es poden incorporar els ex-usuaris del catàleg
        $grupCat = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
        $exCatUsersList = UserUtil::getUsersForGroup($grupCat['ExSirius']);
        if (!in_array($uid, $exCatUsersList)) {
            LogUtil::registerError($this->__('No existeix cap ex-usuari del catàleg amb l\'identificador indicat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'usersgest'));
        }
        //El traiem de ExSirius i l'assignem el grup Sirius
        $where = 'gid = ' . $grupCat['ExSirius'] . ' AND uid = ' . $uid;
        DBUtil::deleteWhere('group_membership', $where);
        $item = array('gid' => $grupCat['Sirius'], 'uid' => $uid);
        DBUtil::insertObject($item, 'group_membership');
        return ModUtil::func('Cataleg', 'admin', 'editUser', array('uid' => $uid));
    }

    /**
     * Gestiona-Informa dels usuaris d'un grup de zikula del catàleg
     * 
     * > Retorna la informació de tots els membres del grup triat.\n
     * > Depenent del grup, permetrà la seva edició o només mostrarà la informació.\n
     * > Per als grups generals (Catàleg, Excatàleg, Personals, Genèrics) i pel grup Editors obtindrem la informació.\n
     * > El grup Gestors només podrà ser editat per el Gestor-Administrador.
     *
     * ### Paràmetres rebuts per GET:
     * * numeric **gid** gid del grup de zikula a gestionar.
     *
     * @return void Plantilla *Cataleg_admin_membersGroupview.tpl* o *Cataleg_admin_membersGroupgest.tpl*
     */
    public function membersGroupgest() {
        //Comprovacions de seguretat. Només els gestors poden crear i editar usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gid = FormUtil::getPassedValue('gid', null, 'GET');
        $grup = UserUtil::getGroup($gid);
        //Només es poden gestionar els membres dels grups del catàleg
        $grupsUnitats = ModUtil::getVar('Cataleg', 'grupsUnitats');
        $grupsZikula = ModUtil::getVar('Cataleg', 'grupsZikula');
        if (!in_array($gid, $grupsUnitats) && !in_array($gid, $grupsZikula)) {
            LogUtil::registerError($this->__('No es poden gestionar els membres del grup indicat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
        }
        $users = UserUtil::getUsers('', 'uname', -1, -1, 'uid');
        foreach ($users as $key => $user) {
            $users[$key]['iw'] = DBUtil::selectObject('IWusers', 'where iw_uid =' . $key);
        }
        $catUsersList = UserUtil::getUsersForGroup($grupsZikula['Sirius']);
        $groupUsersList = UserUtil::getUsersForGroup($gid);
        foreach ($users as $user) {
            if (in_array($user['uid'], $catUsersList)) {
                if (in_array($user['uid'], $groupUsersList)) {
                    $usuaris[1][] = $user;
                } else {
                    $usuaris[0][] = $user;
                }
            } else {
                if (in_array($user['uid'], $groupUsersList)) {
                    $usuaris[2][] = $user;
                }
            }
        }
        $this->view->assign('usuaris', $usuaris);
        $this->view->assign('grup', $grup);
        if ((!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN) && $gid == $grupsZikula['Gestors']) || $gid == $grupsZikula['UNI'] || $gid == $grupsZikula['ST'] || $gid == $grupsZikula['SE'] || $gid == $grupsZikula['Gestform'] || $gid == $grupsZikula['LectorsCat'] || $gid == $grupsZikula['EditorsCat'] || $gid == $grupsZikula['Personals'] || $gid == $grupsZikula['Generics'] || $gid == $grupsZikula['Sirius'] || $gid == $grupsZikula['ExSirius']) {
            return $this->view->fetch('admin/Cataleg_admin_membersGroupview.tpl');
        } else {
            return $this->view->fetch('admin/Cataleg_admin_membersGroupgest.tpl');
        }
    }

    /**
     * Afegeix usuaris a un grup del catàleg
     * 
     * > Assigna els usuaris seleccionats al grup que s'està gestionant.
     *
     * ### Paràmetres rebuts per POST:
     * * integer **gid** gid del grup que s'està gestionant
     * * array **addselect** amb els uid dels usuaris que es volen afegir
     *
     * @return void Retorna a la funció *membersGroupGest* després de desar les dades amb els missatges d'execució
     */
    public function addMembers() {
        //Comprovacions de seguretat. Només els gestors poden crear i editar usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $addusers = FormUtil::getPassedValue('addselect', null, 'POST');
        $gid = FormUtil::getPassedValue('gid', null, 'POST');
        $grupsUnitats = ModUtil::getVar('Cataleg', 'grupsUnitats');
        $grupsZikula = ModUtil::getVar('Cataleg', 'grupsZikula');
        if (!in_array($gid, $grupsUnitats) && $gid != $grupsZikula['LectorsCat'] && !($gid == $grupsZikula['Gestors'] && SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN))) {
            LogUtil::registerError($this->__('No es poden gestionar els membres del grup indicat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
        }
        $uni = (in_array($gid, $grupsUnitats)) ? $grupsZikula['EditorsCat'] : false;
        $res = ModUtil::apiFunc('Cataleg', 'admin', 'gestMembers', array('gid' => $gid, 'action' => 'add', 'users' => $addusers, 'uni' => $uni));
        if ($res) {
            LogUtil::registerStatus($this->__('S\'han afegit els usuaris seleccionats.'));
            if (isset($res['nousEditors'])) {
                $nousEditors = '';
                foreach ($res['nousEditors'] as $nouEditor) {
                    $nousEditors .= ' - ' . UserUtil::getPNUserField($nouEditor, 'uname');
                }
                LogUtil::registerStatus($this->__('Els següents usuaris han estat assignats també al rol d\'editors, que encara no tenien:') . $nousEditors);
            }
        } else {
            LogUtil::registerError($this->__('No s\'han pogut afegir els usuaris selecionats.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'membersGroupgest', array('gid' => $gid)));
    }

    /**
     * Treu usuaris d'un grup del catàleg
     * 
     * > Treu els usuaris seleccionats del grup que s'està gestionant.
     *
     * ### Paràmetres rebuts per POST:
     * * integer **gid** gid del grup que s'està gestionant
     * * array **removeelect** amb els uid dels usuaris que es volen treure
     *
     * @return void Retorna a la funció *membersGroupGest* després de desar les dades amb els missatges d'execució
     */
    public function removeMembers() {
        //Comprovacions de seguretat. Només els gestors poden crear i editar usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $removeusers = FormUtil::getPassedValue('removeselect', null, 'POST');
        $gid = FormUtil::getPassedValue('gid', null, 'POST');
        $grupsUnitats = ModUtil::getVar('Cataleg', 'grupsUnitats');
        $grupsZikula = ModUtil::getVar('Cataleg', 'grupsZikula');
        if (!in_array($gid, $grupsUnitats) && $gid != $grupsZikula['LectorsCat'] && !($gid == $grupsZikula['Gestors'] && SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN))) {
            LogUtil::registerError($this->__('No es poden gestionar els membres del grup indicat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
        }
        $uni = (in_array($gid, $grupsUnitats)) ? $grupsZikula['EditorsCat'] : false;
        $res = ModUtil::apiFunc('Cataleg', 'admin', 'gestMembers', array('gid' => $gid, 'action' => 'remove', 'users' => $removeusers, 'uni' => $uni));
        if ($res) {
            LogUtil::registerStatus($this->__('S\'han desassignat els usuaris seleccionats.'));
            if ($res['exEditors']) {
                $exEditors = '';
                foreach ($res['exEditors'] as $exEditor) {
                    $exEditors .= ' - ' . UserUtil::getPNUserField($exEditor, 'uname');
                }
                LogUtil::registerStatus($this->__('Els següents usuaris han perdut també el rol d\'editors, ja que no pertanyen a cap unitat:') . $exEditors);
            }
        } else {
            LogUtil::registerError($this->__('No s\'han pogut desassignar els usuaris selecionats.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'membersGroupgest', array('gid' => $gid)));
    }

    /**
     * Esborra un grup de zikula corresponent a una unitat del catàleg
     * 
     * > Esborra el registre de la taula *groups*.\n
     * > No deixa esborrar el grup si té usuaris o gestiona alguna unitat.
     *
     * ### Paràmetres rebuts per GET:
     * * integer **gid** gid del grup a esborrar.
     *
     * @return void Retorna a la funció *groupsgest* amb els missatges d'execució
     */
    public function deleteGroupUnit() {
        //Comprovacions de seguretat. Només els gestors poden crear i editar usuaris
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gid = FormUtil::getPassedValue('gid', null, 'GET');
        $grupsUnitats = ModUtil::getVar('Cataleg', 'grupsUnitats');
        if (!in_array($gid, $grupsUnitats)) {
            LogUtil::registerError($this->__('No es pot esborrar. El grup indicat no correspon a cap unitat'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
        }
        $del = ModUtil::apiFunc('Cataleg', 'admin', 'deleteGroupUnit', $gid);
        if ($del) {
            LogUtil::registerStatus($this->__('El grup s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar el grup.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'groupsgest'));
    }

    /**
     * Exporta un csv amb tota la informació dels centres que té Sirius informats
     * 
     *
     * @return void Retorna a la funció *modulesetings* amb els missatges d'execució
     */
    public function exportaCentres() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $titlerow = DBUtil::getColumnsArray('cataleg_centres');
        $datarows = DBUtil::selectObjectArray('cataleg_centres');
        FileUtil::exportCSV($datarows, $titlerow, ';', '"', 'exportaCentres.csv');
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'modulesettings'));
    }

    /**
     * Importa centres a partir d'un csv a la base de dades de Sirius
     * 
     * Els centres ja existents (codi) els actualitza (informació addicional) i afegeix els nous
     * 
     * @return void Retorna a la funció *modulesetings* amb els missatges d'execució
     */
    public function importaCentres() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        // get input values. Check for direct function call first because calling function might be either get or post
        if (isset($args) && is_array($args) && !empty($args)) {
            $confirmed = isset($args['confirmed']) ? $args['confirmed'] : false;
        } elseif (isset($args) && !is_array($args)) {
            throw new Zikula_Exception_Fatal(LogUtil::getErrorMsgArgs());
        } elseif ($this->request->isGet()) {
            $confirmed = false;
        } elseif ($this->request->isPost()) {
            $this->checkCsrfToken();
            $confirmed = $this->request->request->get('confirmed', false);
        }
        if ($confirmed) {
            // get other import values
            $importFile = $this->request->files->get('importFile', isset($args['importFile']) ? $args['importFile'] : null);

            $fileName = $importFile['name'];
            $importResults = '';
            if ($fileName == '') {
                $importResults = $this->__("No heu triat cap fitxer.");
            } elseif (FileUtil::getExtension($fileName) != 'csv') {
                $importResults = $this->__("L'extensió del fitxer ha de ser csv.");
            } elseif (!$file_handle = fopen($importFile['tmp_name'], 'r')) {
                $importResults = $this->__("No s'ha pogut llegir el fitxer csv.");
            } else {
                $caps = array(
                    'CODI_ENTITAT'      => 'CODI_ENTITAT',
                    'CODI_TIPUS_ENTITAT'=> 'CODI_TIPUS_ENTITAT',
                    'NOM_ENTITAT'       => 'NOM_ENTITAT',
                    'NOM_LOCALITAT'     => 'NOM_LOCALITAT',
                    'NOM_DT'            => 'NOM_DT',
                    'CODI_DT'           => 'CODI_DT',
                    'NOM_TIPUS_ENTITAT' => 'NOM_TIPUS_ENTITAT'
                );
                while (!feof($file_handle)) {
                    $line = fgetcsv($file_handle, 1024, ';', '"');
                    if ($line != '') {
                        $lines[] = $line;
                    }
                }
                fclose($file_handle);
                //
                $centres = DBUtil::selectFieldArray('cataleg_centres', 'CODI_ENTITAT');
                $updateCentres = array();
                $insertCentres = array();
                foreach ($lines as $line_num => $line) {
                    if ($line_num != 0) {
                        if (count($lines[0]) != count($line)) {
                            $importResults .= $this->__("<div>Hi ha registres amb un número de camps incorrecte.</div>");
                        } else {
                            if (in_array($line[0], $centres)) {
                                $updateCentres[] = array_combine($lines[0], $line);
                            } else {
                                $insertCentres[] = array_combine($lines[0], $line);
                            }
                        }
                    } else {
                        $difs = array_diff($line, $caps);
                        if (count($line) != count(array_unique($line))) {
                            $importResults = $this->__("La capçalera del csv té columnes repetides.");
                        } elseif (!in_array('CODI_ENTITAT', $line)) {
                            $importResults = $this->__("El csv ha de tenir obligatòriament el camp CODI_ENTITAT.");
                        } elseif ($line[0] != 'CODI_ENTITAT') {
                            $importResults = $this->__("El camp obligatori CODI_ENTITAT ha d'ocupar el primer lloc.");
                        } elseif (!empty($difs)) {
                            $importResults = $this->__("<div>El csv té camps incorrectes.</div>");
                        }
                    }
                }
            }
            
            if ($importResults == '') {
                $inserts = count($insertCentres);
                $updates = count($updateCentres);
                DBUtil::insertObjectArray($insertCentres, 'cataleg_centres');
                DBUtil::updateObjectArray($updateCentres, 'cataleg_centres', 'CODI_ENTITAT');
                // the users have been imported successfully
                $this->registerStatus($this->__('Els centres s\'han importat correctament'));
                $this->registerStatus($this->__('Centres actualitzats: ' . $updates . ' - Centres nous: ' . $inserts));
                //$this->redirect(ModUtil::url($this->name, 'admin', 'modulesettings'));
                return system::redirect(ModUtil::url('Cataleg', 'admin', 'modulesettings'));
            }
        }
        // shows the form
        $post_max_size = ini_get('post_max_size');
        return $this->view->assign('importResults', isset($importResults) ? $importResults : '')
                        ->assign('post_max_size', $post_max_size)
                        ->fetch('admin/Cataleg_admin_importaCentres.tpl');
    }
    /**
     * Funció per a la gestió de les entitats i grups d'entitats gtaf per a la catalogació dels usuaris
     *
     *  > S'obté la llista de les entitats i grups d'entitats i permet administrar-les
     *
     * @return void Plantilla *Cataleg_admin_gtafEntitiesGest.tpl*
     */
    public function gtafEntitiesGest() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gtafInfo = ModUtil::apiFunc('Cataleg','admin','getGtafInfo');
        $this->view->assign('gtafInfo', $gtafInfo);
        return $this->view->fetch('admin/Cataleg_admin_gtafEnititiesGest.tpl');
    }
     /**
     * Creació d'una nova entitat-gtaf
     *
     * @return void Plantilla *Cataleg_admin_addeditGtafEntity.tpl* per introduir les dades
     */
    public function addGtafEntity() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gtafInfo = ModUtil::apiFunc('Cataleg','admin','getGtafInfo');
        $this->view->assign('gtafGroups', $gtafInfo['groups']);
        $this->view->assign('gtafEntities', $gtafInfo['entities']);
        $this->view->assign('edit', false);
        return $this->view->fetch('admin/Cataleg_admin_addeditGtafEntity.tpl');
    }

    /**
     * Edició d'una entitat
     *
     * ### Paràmetres rebuts per GET:
     * * integer **gtafeid** de l'entitat a editar.
     *
     * @return void Plantilla *Cataleg_admin_addeditGtafEntity.tpl* amb les dades de l'entitat per poder-les editar
     */
    public function editGtafEntity() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gtafeid = FormUtil::getPassedValue('gtafeid', null, 'GET');
        $gtafInfo = modUtil::apiFunc('Cataleg', 'admin', 'getGtafEntity', $gtafeid);
        $this->view->assign('edit', true);
        $this->view->assign('gtafEntity', $gtafInfo['entity']);
        $this->view->assign('gtafGroups', $gtafInfo['groups']);
        $this->view->assign('gtafEntities', $gtafInfo['entities']);
        return $this->view->fetch('admin/Cataleg_admin_addeditGtafEntity.tpl');
    }
    /**
     * Creació i/o Edició d'una entitat-gtaf
     *
     * ### Paràmetres rebuts per POST:
     * * string **prev_gtafEntityId**
     * * string **gtafEntityId**
     * * string **nom**
     * * string **tipus**
     * * string **gtafGroupId**
     *
     * @return void Retorna a la plantilla *Cataleg_admin_gtafEnititiesGest.tpl* després de desar les dades
     */
    public function addeditGtafEntity() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['prev_gtafEntityId'] = FormUtil::getPassedValue('prev_gtafEntityId', null, 'POST');
        $item['gtafEntityId'] = FormUtil::getPassedValue('gtafEntityId', null, 'POST');
        $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
        $item['tipus'] = FormUtil::getPassedValue('tipus', null, 'POST');
        $item['gtafGroupId'] = FormUtil::getPassedValue('gtafGroupId', null, 'POST');
        if (!isset($item['gtafEntityId']) || !isset($item['nom']) || !isset($item['tipus']) || !isset($item['gtafGroupId'])) {
            LogUtil::registerError($this->__('Falten dades per a poder crear l\'entitat.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveEntitat', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('L\'entitat s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('L\'entitat s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'han pogut desar les modificacions.'));
        }

        return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
    }
    /**
     * Esborra una entitat-gtaf
     *
     * ### Paràmetres rebuts per GET:
     * * integer **gtafeid** de l'entitat a editar.
     *
     * @return void Retorna a la plantilla *Cataleg_admin_gtafEnititiesGest.tpl* després d'esborrar el registre
     */
    public function deleteGtafEntity() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gtafeid = FormUtil::getPassedValue('gtafeid', null, 'GET');
        $obj = FormUtil::getPassedValue('obj', null, 'GET');
        if ($obj == 'entitat') {
            $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'gtafEntity', 'id' => $gtafeid));
        } elseif ($obj == 'grup') {
            $del = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'gtafGroup', 'id' => $gtafeid));
        }
        if ($del) {
            LogUtil::registerStatus($this->__('L\'entitat s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar l\'entitat.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
    }
    /**
     * Creació d'un nou grup d'entitats-gtaf
     *
     * @return void Plantilla *Cataleg_admin_addeditGtafGroup.tpl* per introduir les dades
     */
    public function addGtafGroup() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gtafInfo = ModUtil::apiFunc('Cataleg','admin','getGtafGroups');
        $this->view->assign('gtafGroups', $gtafInfo['groups']);
        $this->view->assign('catusers', $gtafInfo['catusers']);
        $this->view->assign('edit', false);
        return $this->view->fetch('admin/Cataleg_admin_addeditGtafGroup.tpl');
    }
    /**
     * Edició d'un grup d'entitats-gtaf
     *
     * @return void Plantilla *Cataleg_admin_addeditGtafGroup.tpl* per editar les dades
     */
    public function editGtafGroup() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $gtafgid = FormUtil::getPassedValue('gtafgid', null, 'GET');
        $gtafInfo = ModUtil::apiFunc('Cataleg','admin','getGtafGroups',$gtafgid);
        $this->view->assign('gtafGroup', $gtafInfo['group']);
        $this->view->assign('gtafGroups', $gtafInfo['groups']);
        $this->view->assign('catusers', $gtafInfo['catusers']);
        $this->view->assign('edit', true);
        return $this->view->fetch('admin/Cataleg_admin_addeditGtafGroup.tpl');
    }
     /**
     * Creació i/o Edició d'un grup d'entitats-gtaf
     *
     * ### Paràmetres rebuts per POST:
     * * string **prev_gtafGroupId**
     * * string **gtafGroupId**
     * * string **nom**
     * * string **resp_uid**
     *
     * @return void Retorna a la plantilla *Cataleg_admin_gtafEnititiesGest.tpl* després de desar les dades
     */
    public function addeditGtafGroup() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['prev_gtafGroupId'] = FormUtil::getPassedValue('prev_gtafGroupId', null, 'POST');
        $item['gtafGroupId'] = FormUtil::getPassedValue('gtafGroupId', null, 'POST');
        $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
        $item['resp_uid'] = FormUtil::getPassedValue('resp_uid', null, 'POST');
        if (!isset($item['gtafGroupId']) || !isset($item['nom']) || !isset($item['resp_uid'])) {
            LogUtil::registerError($this->__('Falten dades per a poder crear el grup d\'entitats.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
        }
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveGtafGroup', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('El grup s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('El grup s\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'han pogut desar les modificacions.'));
        }

        return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
    }
    /**
     * Exporta un csv amb tota la informació de les entitats-gtaf/grups d'entitats-gtaf que té Sirius
     * 
     *
     * @return void Retorna a la funció *gtafEntitiesGest* amb els missatges d'execució
     */
    public function exportGtafEntities() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $case = FormUtil::getPassedValue('case',null,'GET');
        if ($case == 'entities') {
            $taula = 'cataleg_gtafEntities';
            $fitxer = 'exportaGtafEntitats.csv';
        } elseif ($case == 'groups') {
            $taula = 'cataleg_gtafGroups';
            $fitxer = 'exportaGtafGrups.csv';
        } else {
            LogUtil::registerError($this->__('La petició no és vàlida'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
        }
        $titlerow = DBUtil::getColumnsArray($taula);
        $datarows = DBUtil::selectObjectArray($taula);
        FileUtil::exportCSV($datarows, $titlerow, ';', '"', $fitxer);
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
    }
        /**
     * Importa les taules de entitats-gtaf i grups d'entitats a partir d'un csv a la base de dades de Sirius
     * 
     * Esborra el contingut previ de les taules i importa el contingut del fitxer
     * 
     * @return void Retorna a la funció *gtafEntitiesGest* amb els missatges d'execució
     */
    public function importGtafEntities() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        // get input values. Check for direct function call first because calling function might be either get or post
        if (isset($args) && is_array($args) && !empty($args)) {
            $confirmed = isset($args['confirmed']) ? $args['confirmed'] : false;
            $case = isset($args['case']) ? $args['case'] : false;
        } elseif (isset($args) && !is_array($args)) {
            throw new Zikula_Exception_Fatal(LogUtil::getErrorMsgArgs());
        } elseif ($this->request->isGet()) {
            $confirmed = 1;
        } elseif ($this->request->isPost()) {
            $this->checkCsrfToken();
            $confirmed = $this->request->request->get('confirmed', false);
            $case = $this->request->request->get('case',false);
        }
        if ($confirmed == 2) {
            if ($case == 'entities') {
                $caps = array(
                    'gtafEntityId'   => 'gtafEntityId',
                    'nom'            => 'nom',
                    'tipus'          => 'tipus',
                    'gtafGroupId'    => 'gtafGroupId'
                );
                $caps_man = $caps;
                $taula = 'cataleg_gtafEntities';
                $mes = "Importació d'entitats-gtaf";
                $field_id = 'gtafEntityId';
            } else {
                $caps = array(
                    'gtafGroupId'   => 'gtafGroupId',
                    'nom'           => 'nom',
                    'resp_uid'      => 'resp_uid'
                );
                $caps_man = array(
                    'gtafGroupId'   => 'gtafGroupId',
                    'nom'           => 'nom'
                );
                $taula = 'cataleg_gtafGroups';
                $mes = "Importació de grups d'entitats-gtaf";
                $field_id = 'gtafGroupId';
            }
            // get other import values
            $importFile = $this->request->files->get('importFile', isset($args['importFile']) ? $args['importFile'] : null);

            $fileName = $importFile['name'];
            $importResults = '';
            if ($fileName == '') {
                $importResults = $this->__("No heu triat cap fitxer.");
            } elseif (FileUtil::getExtension($fileName) != 'csv') {
                $importResults = $this->__("L'extensió del fitxer ha de ser csv.");
            } elseif (!$file_handle = fopen($importFile['tmp_name'], 'r')) {
                $importResults = $this->__("No s'ha pogut llegir el fitxer csv.");
            } else {
                while (!feof($file_handle)) {
                    $line = fgetcsv($file_handle, 1024, ';', '"');
                    if ($line != '') {
                        $lines[] = $line;
                    }
                }
                fclose($file_handle);
                //
                foreach ($lines as $line_num => $line) {
                    if ($line_num != 0) {
                        if (count($lines[0]) != count($line)) {
                            $importResults .= $this->__("<div>Hi ha registres amb un número de camps incorrecte.</div>");
                        } else {
                                $import[] = array_combine($lines[0], $line);
                                $import_id[] = $line[0];
                        }
                    } else {
                        $difs = array_diff($line, $caps);
                        $difs2 = array_diff($caps_man,$line);
                        if (count($line) != count(array_unique($line))) {
                            $importResults .= $this->__("<div>La capçalera del csv té columnes repetides.</div>");
                        } elseif (!in_array($field_id, $line)) {
                            $importResults .= $this->__("<div>Falta el camp obligatori de la clau primària (id).</div>");
                        } elseif ($line[0] != $field_id) {
                            $importResults .= $this->__("<div>El camp obligatori de la clau primària (id) ha d'ocupar el primer lloc.</div>");
                        } elseif (!empty($difs2)) {
                            $importResults .= $this->__("<div>Falten camps obligatoris.</div>");
                        } elseif (!empty($difs)) {
                            $importResults .= $this->__("div>El csv té camps incorrectes.</div>");
                        }
                    }
                }
                if (count($import_id) != count(array_unique($import_id))) $importResults .= $this->__("<div>El fitxer té alguna id repetida.</div>"); 
            }
            
            if ($importResults == '') {
                $old_reg = DBUtil::selectObjectCount($taula);
                DBUtil::deleteWhere($taula);
                $inserts = count($import);
                DBUtil::insertObjectArray($import, $taula);
                $this->registerStatus($mes);
                $this->registerStatus($this->__('La importació s\'ha realitzat correctament'));
                $this->registerStatus($this->__('Registres antics: ' . $old_reg . ' - Registres actuals: ' . $inserts));
                return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
            } else {
                $this->view->assign('case',$case);
                $post_max_size = ini_get('post_max_size');
                return $this->view->assign('importResults', isset($importResults) ? $importResults : '')
                            ->assign('post_max_size', $post_max_size)
                            ->fetch('admin/Cataleg_admin_importGtafEntities.tpl');
            }
        } elseif ($confirmed == 1){
            // shows the form
            $case = $this->request->query->get('case',false);
            $this->view->assign('case',$case);
            $post_max_size = ini_get('post_max_size');
            return $this->view->assign('importResults', isset($importResults) ? $importResults : '')
                        ->assign('post_max_size', $post_max_size)
                        ->fetch('admin/Cataleg_admin_importGtafEntities.tpl');
        } else {
            LogUtil::registerError($this->__('La petició no és vàlida'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'gtafEntitiesGest'));
        }
    }

    /*
     * /////////////////////////////////////////////////////////////////////////////////
     * Funcions del mòdul catàleg per al gestor administrador del catàleg
     * Les funcions requereixen el permís 'CatalegAdmin'
     * /////////////////////////////////////////////////////////////////////////////////
     */

    /**
     * Inicia la importació de catàlegs
     *
     * > S'obté un formulari per triar els paràmetres de la importació
     *
     * @return void Plantilla *Cataleg_admin_importFormulari.tpl*
     */
    public function importFormulari() {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $cats = ModUtil::apiFunc('Cataleg', 'admin', 'countElementsCataleg');
        //$cats = ModUtil::apiFunc('Cataleg', 'user', 'getCatalegList', true);
        $this->view->assign('cats', $cats);
        return $this->view->fetch('admin/Cataleg_admin_importFormulari.tpl');
    }

    /**
     * Importa dades d'un catàleg a un altre
     *
     * ### Paràmetres rebuts per POST:
     * * integer **catIdOri** catId del catàleg origen
     * * integer **catIdDest** catId del catàleg destinació
     * * boolean **iEixos** [opcional][per defecte false] indica si s'han d'importar els eixos
     * * boolean **iPrioritats** [opcional][per defecte false] indica si s'han d'importar les prioritats
     * * boolean **iSubprioritats** [opcional][per defecte false] indica si s'han d'importar les subprioritats
     * * boolean **iUnitats** [opcional][per defecte false] indica si s'han d'importar les unitats
     * * boolean **iResponsables** [opcional][per defecte false] indica si s'han d'importar les persones responsables
     * * boolean **iImpunits** [opcional][per defecte false] indica si s'han d'importar les unitats implicades
     * * boolean **iTot** [opcional][per defecte false] indica si s'han d'importar totes els dades anteriors (aquest criteri s'imposa)
     *
     * @return void Retorna a la funció *modulesettings* després de fer la importació
     */
    public function importCataleg() {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item = array('catIdOri' => FormUtil::getPassedValue('catIdOri', false, 'POST'),
            'catIdDest' => FormUtil::getPassedValue('catIdDest', false, 'POST'),
            'iEixos' => FormUtil::getPassedValue('iEixos', false, 'POST'),
            'iPrioritats' => FormUtil::getPassedValue('iPrioritats', false, 'POST'),
            'iSubprioritats' => FormUtil::getPassedValue('iSubprioritats', false, 'POST'),
            'iUnitats' => FormUtil::getPassedValue('iUnitats', false, 'POST'),
            'iResponsables' => FormUtil::getPassedValue('iResponsables', false, 'POST'),
            'iImpunits' => FormUtil::getPassedValue('iImpunits', false, 'POST'));
        $iTot = FormUtil::getPassedValue('iTot', false, 'POST');
        if ($iTot) {
            $item['iEixos'] = true;
            $item['iPrioritats'] = true;
            $item['iSubprioritats'] = true;
            $item['iUnitats'] = true;
            $item['iResponsables'] = true;
            $item['iImpunits'] = true;
        }
        $import = ModUtil::apiFunc('Cataleg', 'admin', 'importCataleg', $item);
        if ($import) {
            LogUtil::registerStatus($this->__('El catàleg s\'ha importat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut importar el catàleg.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'modulesettings'));
    }

    public function importgest() {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $cats = ModUtil::apiFunc('Cataleg', 'user', 'getCatalegList', true);
        $iTaules = ModUtil::apiFunc('Cataleg', 'admin', 'getImportTaules', 'all');
        $this->view->assign('iTaules', $iTaules);
        $this->view->assign('cats', $cats);
        return $this->view->fetch('admin/Cataleg_admin_importgest.tpl');
    }

    public function importaddTaula() {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $cats = ModUtil::apiFunc('Cataleg', 'user', 'getCatalegList', true);
        $this->view->assign('cats', $cats);
        return $this->view->fetch('admin/Cataleg_admin_importaddTaula.tpl');
    }

    public function importaddsaveTaula() {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item = array('catIdOri' => FormUtil::getPassedValue('catIdOri', false, 'POST'),
            'catIdDest' => FormUtil::getPassedValue('catIdDest', false, 'POST'));
        $import = ModUtil::apiFunc('Cataleg', 'admin', 'importaddTaula', $item);
        if ($import) {
            LogUtil::registerStatus($this->__('La taula d\'importació s\'ha creat correctament.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'importeditTaula', array('importId' => $import)));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut crear la taula d\'importació.'));
            return system::redirect(ModUtil::url('Cataleg', 'admin', 'importgest'));
        }
    }

    public function importeditTaula($importId) {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $importId = FormUtil::getPassedValue('importId', null, 'GET');
        $assigna = ModUtil::apiFunc('Cataleg', 'user', 'getImportAssigns', $importId);
        $iTaula = ModUtil::apiFunc('Cataleg', 'admin', 'getImportTaules', $importId);
        $eixosOri = ModUtil::apiFunc('Cataleg', 'user', 'getAllEixos', array('catId' => $iTaula['catIdOri'], 'all' => true, 'resum' => true));
        $catOri = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $iTaula['catIdOri']));
        foreach ($eixosOri as $eixOri) {
            $eixosOri[$eixOri['eixId']]['prioritats'] = modUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsEix', array('eixId' => $eixOri['eixId'], 'all' => true, 'resum' => true));
            foreach ($eixosOri[$eixOri['eixId']]['prioritats'] as $eixPrioritatOri) {
                $eixosOri[$eixOri['eixId']]['prioritats'][$eixPrioritatOri['priId']]['subprioritats'] = modUtil::apiFunc('Cataleg', 'user', 'getAllSubprioritats', array('priId' => $eixPrioritatOri['priId'], 'all' => true, 'resum' => true));
            }
        }
        $eixosDest = ModUtil::apiFunc('Cataleg', 'user', 'getAllEixos', array('catId' => $iTaula['catIdDest'], 'all' => true, 'resum' => true));
        $catDest = modUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $iTaula['catIdDest']));
        foreach ($eixosDest as $eixDest) {
            $eixosDest[$eixDest['eixId']]['prioritats'] = modUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsEix', array('eixId' => $eixDest['eixId'], 'all' => true, 'resum' => true));
            foreach ($eixosDest[$eixDest['eixId']]['prioritats'] as $eixPrioritatDest) {
                $eixosDest[$eixDest['eixId']]['prioritats'][$eixPrioritatDest['priId']]['subprioritats'] = modUtil::apiFunc('Cataleg', 'user', 'getAllSubprioritats', array('priId' => $eixPrioritatDest['priId'], 'all' => true, 'resum' => true));
            }
        }
        $this->view->assign('assigna', $assigna);
        $this->view->assign('iTaula', $iTaula);
        $this->view->assign('catOri', $catOri);
        $this->view->assign('eixosOri', $eixosOri);
        $this->view->assign('catDest', $catDest);
        $this->view->assign('eixosDest', $eixosDest);
        return $this->view->fetch('admin/Cataleg_admin_importeditTaula.tpl');
    }

    public function importeditsaveTaula() {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item = FormUtil::getPassedValue('item', null, 'POST');
        $desa = ModUtil::apiFunc('Cataleg', 'admin', 'importeditTaula', $item);
        if ($desa) {
            LogUtil::registerStatus($this->__('La taula d\'importació s\'ha desat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar la taula d\'importació.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'importgest'));
    }

    public function importdeleteTaula($importId) {
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $importId = FormUtil::getPassedValue('importId', null, 'GET');
        $del = ModUtil::apiFunc('Cataleg', 'admin', 'importdeleteTaula', $importId);
        if ($del) {
            LogUtil::registerStatus($this->__('La taula d\'importació s\'ha esborrat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut esborrar la taula d\'importació.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'importgest'));
    }

    /*
     * /////////////////////////////////////////////////////////////////////////////////
     * Funcions del mòdul catàleg per a l'administrador de Sirius
     * Les funcions requereixen el permís 'SiriusAdmin'
     * /////////////////////////////////////////////////////////////////////////////////
     */

    public function grupsZikulagest() {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $grupsZikula = ModUtil::apiFunc('Cataleg', 'admin', 'getgrupsZikula');
        $allgroupsZikula = ModUtil::apiFunc('Groups', 'user', 'getall');
        $this->view->assign('grupsZikula', $grupsZikula);
        $this->view->assign('allgroupsZikula', $allgroupsZikula);
        return $this->view->fetch('admin/Cataleg_admin_grupsZikulagest.tpl');
    }

    public function setgrupsZikula() {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item = FormUtil::getPassedValue('grz', null, 'POST');
        $desa = ModUtil::apiFunc('Cataleg', 'admin', 'setgrupsZikula', $item);
        if ($desa) {
            LogUtil::registerStatus($this->__('Les variables de mòdul s\'han desat correctament.'));
        } else {
            LogUtil::registerError($this->__('No s\'han pogut desar les variables de mòdul.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'modulesettings'));
    }

    public function auxgest() {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $auxElements = ModUtil::apiFunc('Cataleg', 'admin', 'getAuxElements');
        $this->view->assign('auxElements', $auxElements);
        return $this->view->fetch('admin/Cataleg_admin_auxgest.tpl');
    }

    public function addAuxElement() {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $auxElement['action'] = 'add';
        $this->view->assign('auxElement', $auxElement);
        return $this->view->fetch('admin/Cataleg_admin_addeditAuxElement.tpl');
    }

    public function editAuxElement($auxId) {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $auxId = FormUtil::getPassedValue('auxId', null, 'GET');
        $auxElement = modUtil::apiFunc('Cataleg', 'admin', 'getAuxElement', $auxId);
        $auxElement['action'] = 'edit';
        $this->view->assign('auxElement', $auxElement);
        return $this->view->fetch('admin/Cataleg_admin_addeditAuxElement.tpl');
    }

    public function addeditAuxElement() { //copiat i pendent d'arranjar
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        $item['auxId'] = FormUtil::getPassedValue('auxId', null, 'POST');
        $item['tipus'] = FormUtil::getPassedValue('tipus', null, 'POST');
        $item['ordre'] = FormUtil::getPassedValue('ordre', null, 'POST');
        $item['nom'] = FormUtil::getPassedValue('nom', null, 'POST');
        $item['nomCurt'] = FormUtil::getPassedValue('nomCurt', null, 'POST');
        $item['visible'] = FormUtil::getPassedValue('visible', 0, 'POST');
        $r = ModUtil::apifunc('Cataleg', 'admin', 'saveAuxElement', $item);

        if ($r) {
            if ($r == 'edit') {
                LogUtil::registerStatus($this->__('L\'element s\'ha editat correctament.'));
            } else {
                LogUtil::registerStatus($this->__('L\'elements\'ha creat correctament.'));
            }
        } else {
            LogUtil::registerError($this->__('No s\'ha pogut desar l\'element.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'admin', 'auxgest'));
    }

    public function phpinfo() {
        if (!SecurityUtil::checkPermission('SiriusAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        return phpinfo();
    }

    /**
     * Generar un document, en format pdf, amb tot el contingut (orientacions, activitats i unitats)
     * del catàleg especificat per $catId
     * 
     * @param integer **$catId**
     * @param boolean $do si false mostar formulari per crear portada document, true genera el pdf
     * @return void (pdf document)
     */
    public function document() {

        //Comprovacions de seguretat. Només els gestors poden crear el pdf del catàleg complet
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $catId = FormUtil::getPassedValue('catId', ModUtil::apiFunc($this->name, 'user', 'getActiveCataleg'), 'GET');
        $doPDF = FormUtil::getPassedValue('do', false, 'GET');        

        $cataleg = ModUtil::apiFunc($this->name, 'user', 'getCataleg', array('catId' => $catId));

        if ($doPDF) {
            $prioritats = ModUtil::apiFunc($this->name, 'user', 'getAllPrioritatsCataleg', array('catId' => $catId));

            ini_set("memory_limit", "512M");
            set_time_limit(150); //2.5 minuts
            // Generar PDF amb els paràmetres del post
            $portada         = FormUtil::getPassedValue('portada', '', 'POST');
            $secOri          = FormUtil::getPassedValue('nomSeccioOrientacions', '', 'POST');
            $secAct          = FormUtil::getPassedValue('nomSeccioActivitats', '', 'POST');
            $secUni          = FormUtil::getPassedValue('nomSeccioUnitats', '', 'POST');
            $useStyle        = FormUtil::getPassedValue('pdfStyle', false, 'POST');
            $excludeUnits = FormUtil::getPassedValue('exUnits', false, 'POST');

            // Footer
            $footer = array(
                'C' => array(
                    'content' => '{PAGENO}', //'{PAGENO}/{nb}',
                    'font-size' => 8,
                    'font-style' => '',
                    'font-family' => 'arial',
                    'color' => '#555555'
                ),
                'line' => 1,
            );
            // Inicialització objecte pdf
            $pdf = ModUtil::func($this->name, 'user', 'startPdf');

            if ($useStyle) {
                $stylesheet = file_get_contents(ModUtil::getModuleBaseDir($this->name) . '/' . ModUtil::getName() . '/style/pdf.css');
                $pdf->WriteHTML($stylesheet, 1);
            }
            $pdf->mirrorMargins = 1;
            //$pdf->SetDisplayMode('fullpage','two');
            // Portada del document
            ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => '', 'content' => $portada, 'footer' => ''));
            $pdf->AddPage();
            // Paràmetres taula de continguts
            $pdf->TOCpagebreakByArray(array(
                'tocfont' => '',
                'tocfontsize' => '10',
                'tocindent' => '5',
                'TOCusePaging' => true,
                'TOCuseLinking' => true,
                'toc_orientation' => '',
                'toc_mgl' => '25',
                'toc_mgr' => '15',
                'toc_mgt' => '',
                'toc_mgb' => '',
                'toc_mgh' => '',
                'toc_mgf' => '',
                'toc_ohname' => '',
                'toc_ehname' => '',
                'toc_ofname' => '',
                'toc_efname' => '',
                'toc_ohvalue' => 0,
                'toc_ehvalue' => 0,
                'toc_ofvalue' => -1,
                'toc_efvalue' => -1,
                'toc_preHTML' => $this->__('<h1>Índex del catàleg</h1>'),
                'toc_postHTML' => '',
                'toc_bookmarkText' => $this->__('Índex del catàleg'),
                'resetpagenum' => '',
                'pagenumstyle' => '',
                'suppress' => 'off',
                'orientation' => '',
                'mgl' => '',
                'mgr' => '',
                'mgt' => '',
                'mgb' => '',
                'mgh' => '',
                'mgf' => '',
                'ohname' => '',
                'ehname' => '',
                'ofname' => '',
                'efname' => '',
                'ohvalue' => 0,
                'ehvalue' => 0,
                'ofvalue' => 0,
                'efvalue' => 0,
                'toc_id' => 0,
                'pagesel' => '',
                'toc_pagesel' => '',
                'sheetsize' => '',
                'toc_sheetsize' => ''
            ));

            // Inicialització de capçalera de pàgina. $l1 part comuna de la secció
            $headerView = Zikula_View::getInstance('Cataleg', false);
            $headerView->assign('l1', $secOri . " - " . $cataleg['nom']);
            $htmlHeader = $headerView->fetch('user/Cataleg_user_pdfHeader.tpl');
            // Orientacions
            if (strlen($secOri) > 0) {
                // Títol línies priroitàries
                $pdf->WriteHTML('<bookmark content="' . $secOri . '" level="0" /><tocentry content="' . $secOri . '" level="0" />', 2);
                ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $htmlHeader, 'content' => '<h2>' . $secOri . '</h2>', 'footer' => $footer));

                // Orientacions 
                $first = true;
                foreach ($prioritats as $prioritat) {
                    $headerView->assign('l2', $prioritat['ordre'] . '- ' . $prioritat['nomCurt']);
                    $htmlHeader = $headerView->fetch('user/Cataleg_user_pdfHeader.tpl');
                    if ($first)
                        $first = false;
                    else
                        $pdf->addPage();
                    // Crear entrada a líndex
                    $pdf->WriteHTML('<bookmark content="' . $prioritat['ordre'] . '.- ' . $prioritat['nom'] . '" level="1" /><tocentry content="' . $prioritat['ordre'] . '.- ' . $prioritat['nom'] . '" level="1" />', 2);
                    $content = ModUtil::func($this->name, 'user', 'display', array('priId' => $prioritat['priId'], 'pdf' => false, 'doc' => true));
                    ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $htmlHeader, 'content' => $content, 'footer' => $footer));
                }
                $pdf->addPage();
            }
            // Regles de conversió dels noms de les unitats a efectes d'ordenació
            $patterns = array();
                        $patterns[0] = '/À/';
                        $patterns[1] = '/d\'/';
                        $patterns[2] = '/de/';
                        $patterns[3] = '/Serveis/';
            $replacements = array();
                        $replacements[3] = 'A';
                        $replacements[2] = '';
                        $replacements[1] = '';
                        $replacements[0] = 'Servei';
                        
            if (strlen($secAct) > 0) {
                // Activitats 
                // Inserció a l'índex la secció activitats            
                $pdf->WriteHTML('<bookmark content="' . $secAct . '" level="0" /><tocentry content="' . $secAct . '" level="0" />', 2);
                ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $htmlHeader, 'content' => '<h2>' . $secAct . '</h2>', 'footer' => $footer));
            }
            $first = true;
            $eix = "";
            $unitats = array();                                    
            foreach ($prioritats as $prioritat) {
                $pri = "";
                $spr = "**";
                // Obtenim les activitats d'una prioritat
                $activitats = ModUtil::apiFunc($this->name, 'user', 'getAllPrioritatActivitats', array('priId' => $prioritat['priId'], 'catId' => $catId, 'itemsperpage' => -1));
                foreach ($activitats as $activitat) {
                    if ($excludeUnits) {
                        if (!in_array($activitat['uniId'], $unitats, true)) {
                            $unit = ModUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $activitat['uniId']));
                            $unitats [$activitat['uniId']] = $unit;
                            //Modifiquem el nom de la unitat per a l'ordenació
                            $uniOrder[$activitat['uniId']] = preg_replace($patterns, $replacements, $unit['nom']);
                        }
                    }
                    if (strlen($secAct) > 0) {
                        if ($eix != $activitat['ordreEix']) {
                            // Afegim l'eix a l'índex
                            $pdf->WriteHTML('<bookmark content="Eix ' . $activitat['ordreEix'] . '- ' . $activitat['nomCurtEix'] . '" level="1" /><tocentry content="Eix ' . $activitat['ordreEix'] . '- ' . $activitat['nomCurtEix'] . '" level="1" />', 2);
                            $eix = $activitat['ordreEix'];
                        }
                        if ($pri != $activitat['ordrePri']) {
                            // Afegim la prioritat a l'índex
                            $pdf->WriteHTML('<bookmark content="' . $activitat['ordrePri'] . '- ' . $activitat['nomCurtPri'] . '" level="2" /><tocentry content="' . $activitat['ordrePri'] . '- ' . $activitat['nomCurtPri'] . '" level="2" />', 2);
                            $pri = $activitat['ordrePri'];
                        }
                        if ($spr != $activitat['ordreSpr']) {
                            //Afegim la subprioritat a l'índex
                            $tocEntry = (is_null($activitat['ordreSpr'])) ? $this->__('Sense subprioritat') : $activitat['ordrePri'] . $activitat['ordreSpr'] . '- ' . $activitat['nomCurtSpr'];
                            $pdf->WriteHTML('<bookmark content="' . $tocEntry . '" level="3" /><tocentry content="' . $tocEntry . '" level="3" />', 2);
                            $spr = $activitat['ordreSpr'];
                        }

                        if ($first)
                            $first = false;
                        else
                            $pdf->addPage();
                        $tocEntry = (strlen($activitat['tGTAF']) == 0) ? $activitat['titol'] : $activitat['tGTAF'] . '- ' . $activitat['titol'];
                        $pdf->WriteHTML('<bookmark content="' . $tocEntry . '" level="4" /><tocentry content="' . $tocEntry . '" level="4" />', 2);
                        $content = ModUtil::func($this->name, 'user', 'show', array('actId' => $activitat['actId'], 'doc' => true, 'sec' => $secAct));
                        ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $content['header'], 'content' => $content['html'], 'footer' => $footer));
                    }
                }
            }
            $pdf->addPage();
            
            // Add to PDF units info
            if (strlen($secUni) > 0) {
                // Unitats
                if (!$excludeUnits) {
                    // ********************************************************************
                    $allUnits = ModUtil::apiFunc($this->name, 'user', 'getAllUnits', array('catId' => $catId));
                    //$unit = ModUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $activitat['uniId']));
                    foreach ($allUnits as $unit){
                        $unitats [$unit['uniId']] = $unit;
                        //Modifiquem el nom de la unitat per a l'ordenació                        
                        $uniOrder[$unit['uniId']] = preg_replace($patterns, $replacements, $unit['nom']);
                    }       
                }
                // ********************************************************************
                $headerView->assign('l1', $secUni . " - " . $cataleg['nom']);
                $htmlHeader = $headerView->fetch('user/Cataleg_user_pdfHeader.tpl');

                $first = true;
                $pdf->WriteHTML('<bookmark content="' . $secUni . '" level="0" /><tocentry content="' . $secUni . '" level="0" />', 2);
                ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $htmlHeader, 'content' => '<h2>' . $secUni . '</h2>', 'footer' => $footer));
                natsort($uniOrder);
                foreach ($uniOrder as $key => $uo) {
                    $headerView->assign('l2', $unitats[$key]['nom']);
                    $htmlHeader = $headerView->fetch('user/Cataleg_user_pdfHeader.tpl');
                    $uview = Zikula_View::getInstance('Cataleg', false);
                    $uview->assign('unitat', $unitats[$key]);
                    $uview->assign('doc', true);
                    $content = $uview->fetch('user/Cataleg_user_display_unitat.tpl');
                    if ($first)
                        $first = false;
                    else
                        $pdf->addPage();
                    $tocEntry = $unitats[$key]['nom'];
                    $pdf->WriteHTML('<bookmark content="' . $tocEntry . '" level="1" /><tocentry content="' . $tocEntry . '" level="1" />', 2);
                    ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $htmlHeader, 'content' => $content, 'footer' => $footer));
                }
            }
            //Generar el document PDF
            ModUtil::func($this->name, 'user', 'closePdf', array('pdf' => $pdf, 'filename' => 'cataleg.pdf', 'dest' => 'D'));
            return true;
        } else {
            // Mostrar formulari previ a la generació del document PDF per a la
            // introducció del text de portada i títols de secció
            $view = Zikula_View::getInstance('Cataleg', false);

            if (isset($catId) && is_numeric($catId)) {
                $cataleg = ModUtil::apiFunc($this->name, 'user', 'getCataleg', array('catId' => $catId));
                $view->assign('cataleg', $cataleg);
                return $view->fetch('admin/Cataleg_admin_document.tpl');
            } else {
                // No s'ha especificat catàleg i no existeix cap catàleg actiu
                $view->assign('icon', 'error.png');
                $view->assign('msg', $this->__('No és possible generar el document pdf. No s\'ha especificat cap catàleg.'));
                return $view->fetch('user/Cataleg_user_msg.tpl');
            }
        }
    }

    /**
     * Vista prèvia de la portada del pdf general del catàleg
     * 
     * @return void
     */
    public function previewPortada() {
        //Comprovacions de seguretat. Només els gestors poden crear el pdf del catàleg complet
        if (!SecurityUtil::checkPermission('CatalegAdmin::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $portada = FormUtil::getPassedValue('portada', '', 'POST');
        $useStyle = FormUtil::getPassedValue('pdfStyle', false, 'POST');
        $pdf = ModUtil::func($this->name, 'user', 'startPdf');
        if ($useStyle) {
            $stylesheet = file_get_contents(ModUtil::getModuleBaseDir($this->name) . '/' . ModUtil::getName() . '/style/pdf.css');
            $pdf->WriteHTML($stylesheet, 1);
        }
        ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => '', 'content' => $portada, 'footer' => ''));
        ModUtil::func($this->name, 'user', 'closePdf', array('pdf' => $pdf, 'filename' => 'cataleg.pdf', 'dest' => 'I'));
        return true;
    }

}
