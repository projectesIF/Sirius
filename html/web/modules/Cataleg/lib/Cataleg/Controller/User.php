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
class Cataleg_Controller_User extends Zikula_AbstractController {

    /** 
     * Visualització del contingut del catàleg actiu i consultable
     * 
     * @return void 
     */
    public function main() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $this->redirect(ModUtil::url('Cataleg', 'user', 'cataleg'));
    }

    /**
     * Visualització del contingut d'un catàleg
     *   
     * > Mostra les línies prioritàries, agrupades per eixos, del catàleg especificat a catId.\n
     * > Si no s'especifica cap catàleg es mostra per defecte el catàleg actiu.
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **catId** identificador del catàleg a mostrar. Per defecte l'actiu
     * 
     * @return void
     */
    public function cataleg() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $catId = FormUtil::getPassedValue('catId', ModUtil::getVar($this->name, 'actiu'), 'GET');

        // Verificar paràmetre
        if (!is_null($catId) && is_numeric($catId)) {
            if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'cataleg', 'id' => $catId))) {
                $cat = array();
                $cat = ModUtil::apiFunc('Cataleg', 'user', 'get', array('catId' => $catId));

                $view = Zikula_View::getInstance('Cataleg', false);
                if ($cat) {
                    $view->assign('catId', $catId);
                    $view->assign('titol', $cat['nom']);
                    $view->assign('eixos', $cat['eixos']);
                    $view->assign('prioritats', $cat['prioritats']);
                    return $view->fetch('user/Cataleg_user_main.tpl');
                } else {
                    $view->assign('icon', 'error.png');
                    $view->assign('msg', $this->__('En aquests moments no hi ha cap catàleg per mostrar.'));
                    return $view->fetch('user/Cataleg_user_msg.tpl');
                }
            } else {
                $view = Zikula_View::getInstance('Cataleg', false);
                $view->assign('icon', 'error.png');
                $view->assign('msg', $this->__('No és possible accedir a aquest catàleg.'));
                return $view->fetch('user/Cataleg_user_msg.tpl');
            }
        } else {
            return LogUtil::registerError($this->__('Falta especificar un catàleg o definir un catàleg de treball.'), null, ModUtil::url('Cataleg', 'user', 'cataleg'));
        }
    }

    /**
     * Actualitzar la llista d'activitats i, si existeix, les equivalències de prioritats i subprioritats
     * 
     * > En el procés d'importació d'activitats actualitza la llista d'activitats
     * > que es poden importar en funció de la selecció del **catàleg** i la **unitat**
     * > origen de la importació així com les equivalències entre les **prioritats** dels catàlegs
     * > origen i destí.
     * 
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres de l'array $args:
     * * integer **uniId** identificador de la unitat des de la que es vol importar activitats
     * * integer **catOrigId** identificador del catàleg que conté les activitats a importar
     * * integer **catDestId** identificador del catàleg on es crearan les activitats importades  
     * 
     * @return void (Plantilla amb el llistat d'activitats que es poden seleccionar per importar)
     */
    public function updateActsList($args) {
        $uniId = $args['uniId'];
        $catOrigId = $args['catOrigId'];
        $catDestId = $args['catDestId'];

        $equiv = ModUtil::apiFunc($this->name, 'user', 'getCompatTable', array('catOrigId' => $catOrigId, 'catDestId' => $catDestId));
        $acts = ModUtil::apiFunc($this->name, 'user', 'getAllUnitatActivitats', array('uniId' => $uniId));

        $priInfo = array();
        $sprInfo = array();
        foreach ($acts as $key => $act) {
            // Busquem informació de la prioritat si no la tenim           
            if (!array_key_exists($act['priId'], $priInfo)) {
                $priInfo[$act['priId']] = ModUtil::apiFunc($this->name, 'user', 'getPrioritat', array('priId' => $act['priId']));
            }
            $acts[$key]['priOrdre'] = $priInfo[$act['priId']]['ordre'];
            $acts[$key]['priDesc'] = $priInfo[$act['priId']]['nomCurt'];
            // Busquem informació de la subprioritat si no la tenim  
            if (!array_key_exists($act['sprId'], $sprInfo)) {
                $sprInfo[$act['sprId']] = ModUtil::apiFunc($this->name, 'user', 'getSubprioritat', array('sprId' => $act['sprId']));
            }
            $acts[$key]['sprOrdre'] = $sprInfo[$act['sprId']]['ordre'];
            $acts[$key]['sprDesc'] = $sprInfo[$act['sprId']]['nom'];
            // Recollim les equivalències entre prioritats i subprioritats entre els catàlegs
            if (isset($equiv[$acts[$key]['priId'] . "o" . $acts[$key]['sprId']]))
                $acts[$key]['equiv'] = $equiv[$acts[$key]['priId'] . "o" . $acts[$key]['sprId']]['idsDest'];
            else // No hi ha cap equivalència
                $acts[$key]['equiv'] = null;
        }
        // Obtenirm les dades per a la llista desplegable de prioritats i subprioritats del catàleg destí
        $eixosDest = ModUtil::apiFunc('Cataleg', 'user', 'getAllEixos', array('catId' => $catDestId, 'all' => true, 'resum' => true));
        foreach ($eixosDest as $eixDest) {
            $eixosDest[$eixDest['eixId']]['prioritats'] = modUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsEix', array('eixId' => $eixDest['eixId'], 'all' => true, 'resum' => true));
            foreach ($eixosDest[$eixDest['eixId']]['prioritats'] as $eixPrioritatDest) {
                $eixosDest[$eixDest['eixId']]['prioritats'][$eixPrioritatDest['priId']]['subprioritats'] = modUtil::apiFunc('Cataleg', 'user', 'getAllSubprioritats', array('priId' => $eixPrioritatDest['priId'], 'all' => true, 'resum' => true));
            }
        }

        $view = Zikula_View::getInstance('Cataleg', false);
        $view->assign('acts', $acts);
        $view->assign('eixosDest', $eixosDest);
        // Carreguem la plantilla amb les dades
        return $view->fetch('user/Cataleg_user_importActsList.tpl');
    }

    /**
     * Desa els canvis de l'edició d'una fitxa d'orientació d'una línia prioritària
     * 
     * > Els gestors del catàleg poden editar el contingut dels camps orientacions i recursos 
     * > d'una línia prioritària des de la visualització de la fitxa d'orientacions.
     * 
     * ### Paràmetres rebuts per POST:
     * * integer **priId** identificador de la prioritat
     * * string **orientacions** contingut textual del camp "orientacions"
     * * string **recursos** contingut textual del camp "recursos"
     * 
     * @return void 
     */
    public function updateOri() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN));

        $priId = FormUtil::getPassedValue('priId', null, 'POST');
        $ori = FormUtil::getPassedValue('orientacions', null, 'POST');
        $rec = FormUtil::getPassedValue('recursos', null, 'POST');

        modUtil::apiFunc('Cataleg', 'user', 'updateOri', array('priId' => $priId,
            'orientacions' => $ori,
            'recursos' => $rec));
        return system::redirect(ModUtil::url('Cataleg', 'user', 'display', array('id' => $priId)));
    }

    /**
     * Crea una nova activitat o actualitza les dadea d'una existent
     * 
     * > Si existeix actId s'actualitza el contingut de la fitxa d'activitat corresponent.\n
     * > Si no existeix actId, la fitxa correspon a una nova activitat, es crea.\n
     * > En el moment de crear-la/actualitzar-la es pot decidir si es desa com a esborrany o 
     * > s'envia per a la seva validació (publicació).
     * 
     * ### Paràmetres rebuts per POST:
     * * integer **catId** identificador de la prioritat
     * * integer **actId** identificador de l'activitat
     * * integer **uniResp** identificador de la unitat responsable de l'activitat
     * * integer **prioritat** identificador de la prioritat que contempla l'activitat
     * * Els camps de la taula cataleg_activitats
     * * Els camps de la taula cataleg_contactes,
     * * Els camps de la taula cataleg_activitatsZona 
     * * Els camps de la taula centresActivitat
     * 
     * ### Paràmetres rebuts per GET:
     * * boolean **show** indica si després de desar les dades es retorna a la vista d'activitat
     * 
     * @return void
     */
    public function save() {
        // Verificació de seguretat
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD));

        $item['catId'] = FormUtil::getPassedValue('catId', null, 'POST');     // Id del catàleg
        $item['actId'] = FormUtil::getPassedValue('actId', null, 'POST');     // Id de l'activitat (si existeix)
        $item['uniId'] = FormUtil::getPassedValue('uniResp', null, 'POST');   // id de la unitat responsable de l'activitat
        $item['priId'] = FormUtil::getPassedValue('prioritat', null, 'POST'); //id de la prioritat en la que s'emmarca l'activitat
        $back = FormUtil::getPassedValue('back', null, 'POST');

        if ($item['actId'])
            $haveAccess = ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'save', 'id' => $item['actId']));
        else
            $haveAccess = ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $item['uniId']));
        // $haveAccess és true si l'usuari té accés a modificar o crear activitats del catàleg i la unitat
        if ($haveAccess && !is_null($item['priId'])) {
            // DESTINATARIS DE LA FORMACIÓ
            $d = FormUtil::getPassedValue('dest', null, 'POST');
            // Si $show = true retornarem a la vista d'activitat
            $show = FormUtil::getPassedValue('show', FALSE, 'GET');
            $item['destinataris'] = serialize($d);
            // OBJECTIUS
            $o = FormUtil::getPassedValue('obj', null, 'POST');
            $item['objectius'] = serialize($o);
            // CONTINGUTS
            $c = FormUtil::getPassedValue('con', null, 'POST');
            $item['continguts'] = serialize($c);
            // INFO SOBRE DIFERENTS ASPECTES DE GESTIÓ
            $g = FormUtil::getPassedValue('gestio', null, 'POST');
            $item['gestio'] = serialize($g);
            // CENTRES QUE PROBABLEMENT REALITZARAN AQUESTA ACTIVITAT        
            $hihacentres = FormUtil::getPassedValue('hihacentres', null, 'POST');
            if ($hihacentres) {
                $centres = FormUtil::getPassedValue('lcentres', null, 'POST');
                if ($centres > " ") {
                    // Eliminar els codis de centre erronis
                    $filtered = ModUtil::apiFunc($this->name, 'user', 'checkCentres', array('centres' => $centres));
                    $ncentres = $filtered['codis'];
                    $item['llocs'] = explode(",", $ncentres);
                }
                $item['centres'] = FormUtil::getPassedValue('obslloc', null, 'POST');
            }
            $item['sprId'] = FormUtil::getPassedValue('subprioritat', null, 'POST');
            $item['prioritaria'] = FormUtil::getPassedValue('prioritzada', null, 'POST');
            $item['tGTAF'] = FormUtil::getPassedValue('tGTAF', null, 'POST');
            $item['titol'] = FormUtil::getPassedValue('titol', null, 'POST');
            $item['observacions'] = FormUtil::getPassedValue('obs', null, 'POST');
            $item['curs'] = FormUtil::getPassedValue('tcurs', null, 'POST');
            $item['presencialitat'] = FormUtil::getPassedValue('tpres', null, 'POST');
            $item['abast'] = FormUtil::getPassedValue('tabast', null, 'POST');
            $item['hores'] = FormUtil::getPassedValue('nhores', null, 'POST');
            $item['obs_editor'] = FormUtil::getPassedValue('obs_editor', null, 'POST');
            $item['obs_validador'] = FormUtil::getPassedValue('obs_validador', null, 'POST');
            $item['actsPerZona'] = FormUtil::getPassedValue('az', null, 'POST');
            $item['contactes'] = FormUtil::getPassedValue('contacte', null, 'POST');
            $item['info'] = FormUtil::getPassedValue('info', null, 'POST');
            $estat = FormUtil::getPassedValue('estat1', null, 'POST');
            if (!is_null($estat))
                $item['estat'] = $estat; // Si no s'indica estat és manté el que tenia
            $item['activa'] = FormUtil::getPassedValue('activa', 1, 'POST');
            $item['ordre'] = 127;

            /* Estats de la fitxa: 
             * Esborrany 0 
             * Enviada 1
             * Per revisar 2 
             * validada 3 
             * modificada 4 
             * anul·lada 5
             */
            if ($item['estat'] == Cataleg_Constant::VALIDADA) { // Validar
                // si no ha estat mai validada es guarda la informació
                if (!(ModUtil::apiFunc($this->name, 'user', 'isValidated', $item['actId']))) {
                    // Establir data de validació i uid del validador
                    $item['validador'] = UserUtil::getVar('uid');
                    $item['dataVal'] = date('Y-m-d H:i:s');
                }
            }
            // S'ha marcat l'activitata modificadaa per a ser mostrada al bloc de novetats
            $novetat = FormUtil::getPassedValue('novetat', null, 'POST');

            if ($novetat) {
                // Escriure dataModif;  
                $item['dataModif'] = date('Y-m-d H:i:s');
            }

            // S'ha marcat per deixar de mostrar l'activitat modificada al bloc de novetats
            $eraseDataMod = FormUtil::getPassedValue('eraseMod', false, 'POST');
            if ($eraseDataMod)
                $item['dataModif'] = null; //Esborrem la data de modificació a efectes de visualització en el bloc novetats

            if ($item['actId']) {
                // Estem editant. L'activitat ja existeix
                $r = ModUtil::apifunc('Cataleg', 'user', 'updateActivitat', $item);
            } else {
                // S'ha de crear nova
                $r = ModUtil::apifunc('Cataleg', 'user', 'addActivitat', $item);
            }
            if ($r)
                LogUtil::registerStatus($this->__('Les dades s\'han desat correctament.'));
            else
                LogUtil::registerError($this->__('No s\'han pogut desar totes les dades de l\'activitat.'));
            if ($show) {
                return system::redirect(ModUtil::url('Cataleg', 'user', 'show', array('back' => $back, 'actId' => $item['actId'])));
            } else {
                return system::redirect(ModUtil::url('Cataleg', 'user', 'view', array('catId' => $item['catId'])));
            }
        } else {
            // No hi ha accés a crear o modificar
            $view = Zikula_View::getInstance('Cataleg', false);
            $view->assign('icon', 'error.png');
            $view->assign('msg', $this->$this->__('No teniu permís per actualitzar o crear activitats en aquest catàleg.'));
            return $view->fetch('user/Cataleg_user_msg.tpl');
        }
    }

    /**
     * Mostra el formulari per introduir una nova activitat
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **catId** identificador del catàleg on es crearà l'activitat
     * 
     * @return void (mostra la plantilla Cataleg_user_fitxaActivitat.tpl)
     */
    public function addnew() {

        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD));

        $catId = FormUtil::getPassedValue('catId', null, 'GET');
        $view = Zikula_View::getInstance('Cataleg', false);
        if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'addnew', 'id' => $catId))) {
            $cataleg = ModUtil::apiFunc('Cataleg', 'user', 'get', array('catId' => $catId));
            $prioritats = ModUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsCataleg', array('catId' => $catId));
            $destinataris = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'dest'));
            $curs = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'curs'));
            $pres = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'pres'));
            $abast = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'abast'));
            $sstt = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'sstt'));
            $opsGest = ModUtil::apiFUnc('Cataleg', 'user', 'getOpcionsGestio');
            $unitats = ModUtil::apiFunc('Cataleg', 'user', 'getAllUserUnits', array('catId' => $catId));
            if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN))
                $level = 'admin';
            else
                $level = 'edit';

            $view->assign('info', null);
            $view->assign('actId', null);
            $view->assign('level', $level);
            $view->assign('cataleg', $cataleg);
            $view->assign('eixinfo', null);           // info de l'eix associat a l'activitat
            $view->assign('subprinfo', null);         // info de la subprioritat associada a l'activitat
            $view->assign('subpr', null);             // inicialment no hi ha llista de subprioritats perquè depenen de la prioritat triada
            $view->assign('priinfo', null);           // info de la prioritat associada a l'activitat
            $view->assign('prioritats', $prioritats); // llista de prioritats disponibles
            $view->assign('unitats', $unitats);       // llista de les unitats de l'usuari
            $view->assign('destinataris', $destinataris);
            $view->assign('curs', $curs);
            $view->assign('pres', $pres);
            $view->assign('ambit', $abast);
            $view->assign('sstt', $sstt);
            $view->assign('opsGest', $opsGest);
            $view->assign('def', '');
            $view->assign('back', '');
            if (!$unitats) {
                LogUtil::addErrorPopup($this->__('No esteu associat a cap unitat o servei d\'aquest catàleg '));
                return system::redirect(ModUtil::url('Cataleg', 'user', 'view'));
            }
            return $view->fetch('user/Cataleg_user_fitxaActivitat.tpl');
        } else {
            $view->assign('icon', 'important.png');
            $view->assign('msg', $this->__('No teniu permís per crear una activitat en aquest catàleg.'));
            return $view->fetch('user/Cataleg_user_msg.tpl');
        }
    }

    /**
     * Edició/modificació de les dades d'una activitat
     * 
     * > Obtenció de les dades existents de l'activitat id i càrrega del formulari per a l'edició.
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **id** Identificador de l'activitat
     * * boolean **show** Cert indica que s'accedix a l'edició de l'activitat des de la visualització. En cas contrari s'accedeix a l'edició des de la llista d'activitats a "Les meves activitats"
     * * string **back** Indica on enllaça el link de retorn "Tornar" des de la visualització de la fitxa 
     * 
     * @return void (mostra la plantilla Cataleg_user_fitxaActivitat.tpl' amb les dades de l'activitat id)
     */
    public function edit() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_EDIT));

        $id = FormUtil::getPassedValue('id', null, 'GET');
        $show = FormUtil::getPassedValue('show', false, 'GET');
        $back = FormUtil::getPassedValue('back', null, 'GET');

        $view = Zikula_View::getInstance('Cataleg', false);
        //if (is_numeric($id) && $editable && $isMine) {
        if (!is_null($id) && (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'edit', 'id' => $id)))) {
            // Obtenim catàleg, eix, prioritat i subprioritat i resta de dades de l'activitat
            $info = ModUtil::apiFunc('Cataleg', 'user', 'getActivitat', $id);
            if (!is_null($info)) {
                if (SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN))
                    $level = 'admin';
                else
                    $level = 'edit';

                $prioritats = ModUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsCataleg', array('catId' => $info['cataleg']['catId']));
                $destinataris = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'dest'));
                $curs = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'curs'));
                $pres = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'pres'));
                $abast = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'abast'));
                $sstt = ModUtil::apiFunc('Cataleg', 'user', 'getTipus', array('tipus' => 'sstt'));
                $opsGest = ModUtil::apiFUnc('Cataleg', 'user', 'getOpcionsGestio');
                $unitats = ModUtil::apiFunc('Cataleg', 'user', 'getAllUserUnits', array('catId' => $info['cataleg']['catId']));

                $view->assign('info', $info);                     // Tota la informació de l'activitat
                $view->assign('level', $level);
                $view->assign('actId', $id);
                $view->assign('cataleg', $info['cataleg']);
                $view->assign('eixinfo', $info['eix']);           // info de l'eix associat a l'activitat
                $view->assign('subprinfo', $info['spr']);         // info de la subprioritat associada a l'activitat                
                $view->assign('subpr', ModUtil::apiFunc('Cataleg', 'user', 'getAllSubprioritats', array('priId' => $info['priId'])));  // Llista de subprioritats de la prioritat triada
                $view->assign('priinfo', $info['pri']);           // info de la prioritat associada a l'activitat
                $view->assign('prioritats', $prioritats);         // llista de prioritats disponibles
                $view->assign('unitats', $unitats);               // llista de les unitats de l'usuari
                $view->assign('destinataris', $destinataris);
                $view->assign('curs', $curs);
                $view->assign('pres', $pres);
                $view->assign('ambit', $abast);
                $view->assign('sstt', $sstt);
                $view->assign('opsGest', $opsGest);
                $view->assign('def', '');
                $view->assign('show', $show);
                $view->assign('back', $back);

                return $view->fetch('user/Cataleg_user_fitxaActivitat.tpl');
            }
        } else {
            $view->assign('icon', 'important.png');
            $view->assign('msg', $this->__('No teniu permís per editar aquesta activitat.'));
            return $view->fetch('user/Cataleg_user_msg.tpl');
        }
    }

    /**
     * Mostra la interfície d'usari per a importar activitats d'altres catàlegs
     * 
     * > Restriccions a la importació:
     * > * Només es poden importar activitats de catàlegs que estiguin relacionats.
     * > * La relació entre catàlegs l'estableix l'administrador del catàleg
     * > * Només es podran importar activitats pròpies de la unitat que els gestiona 
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **catId** identificador del catàleg
     * 
     * @return void 
     */
    public function import_ui() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD));
        // Llegir paràmetres d'entrada
        $catDest = FormUtil::getPassedValue('catId', null, 'GET'); // Catàleg destí de la importació
        $view = Zikula_View::getInstance('Cataleg', false);
        if ($catDest && is_numeric($catDest)) {
            if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'addnew', 'id' => $catDest))) {
                $cd = ModUtil::apiFunc($this->name, 'user', 'getCataleg', array('catId' => $catDest));
                // Obtenir llista de catàlegs compatibles per importar
                $ccats = ModUtil::apiFunc($this->name, 'user', 'getCompatCats', $catDest);
                if ($ccats) {
                    //Preparar dades plantilla
                    $view->assign('catDestNom', $cd['nom']);
                    $view->assign('catDest', $catDest);
                    $view->assign('ccats', $ccats);
                    return $view->fetch('user/Cataleg_user_import.tpl');
                } else {
                    // No hi ha catàlegs per importar activitats
                    return LogUtil::registerError($this->__('No es poden importar activitats. No s\'ha definit cap relació d\'importació amb altres catàlegs.'), null, ModUtil::url($this->name, 'user', 'view', array('catId' => $catDest)));
                }
            } else {
                $view->assign('icon', 'important.png');
                $view->assign('msg', $this->__('No teniu permís per crear activitats en aquest catàleg.'));
                return $view->fetch('user/Cataleg_user_msg.tpl');
            }
        } else {
            // Falta paràmetre
            return LogUtil::registerError($this->__('No es pot realitzar l\'acció: paràmetrers insuficients o erronis.'), null, ModUtil::url($this->name, 'user', 'view', array('catId' => $catDest))); //($this->__('No es pot mostrar cap activitat. Manca un paràmetre (actId).'));
        }
    }

    /**
     * Crea les activitats seleccionades al formulari d'importació
     * 
     * ### Paràmetres rebuts per POST:
     * * integer **uniId** Identificador de la unitat
     * * array **acts** Conté com a índex associatiu **actId** a importar i com a contingut\n
     *   l'equivalència prioritat/subprioritat en format xxdyy on xx: priId i yy: sprId\n
     *   del catàleg destí de la importació\n
     *   Exemple:  [182] => 11d42\n
     * 
     * @return void 
     */
    public function import() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD));

        // $acts conté com a índex associatiu actId a importar i com a contingut
        // l'equivalència prioritat/subprioritat en format xxdyy on xx: priId i yy: sprId
        // del catàleg destí de la importació
        // Exemple:  [182] => 11d42

        $uniId = FormUtil::getPassedValue('ud', null, 'POST');
        $haveAccess = ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $uniId));
        $import = FormUtil::getPassedValue('acts', null, 'POST');
        $nacts = count($import);
        if ($uniId && ($nacts > 0)) {
            if ($haveAccess) {
                // 
                $activitats = array(); // Contindrà informació de les activitats a importar
                foreach ($import as $key => $act) {
                    // Separem prioritat i sbprioritat
                    $ps = explode('d', $act);
                    // Obtenim les dades de l'activitat $key
                    $activitats[$key] = ModUtil::apiFunc($this->name, 'user', 'getActivitatDigest', array(
                                'actId' => $key,
                                'fields' => array(
                                    'titol',
                                    'tGTAF',
                                    'destinataris',
                                    'observacions',
                                    'curs',
                                    'presencialitat',
                                    'abast',
                                    'hores',
                                    'objectius',
                                    'continguts',
                                    'gestio',
                                    'info'
                                    )));
                    $activitats[$key]['priId'] = $ps[0];
                    $activitats[$key]['sprId'] = $ps[1];
                    $activitats[$key]['uniId'] = $uniId;
                }

                if (ModUtil::apiFunc($this->name, 'user', 'importActs', $activitats)){
                    LogUtil::registerStatus($this->__('La importació d\'activitats s\'ha realitzat correctament.'));
                } else {
                    LogUtil::registerError($this->__('Hi ha hagut una errada en la importació d\'activitats.'));
                }
            } else {
                // No hi ha accés a crear o modificar
                $view = Zikula_View::getInstance('Cataleg', false);
                $view->assign('icon', 'error.png');
                $view->assign('msg', $this->__('No teniu permís per actualitzar o crear activitats en aquest catàleg.'));
                return $view->fetch('user/Cataleg_user_msg.tpl');
            }
        } else {
            // Falta algun paràmetre
            LogUtil::registerError($this->__('No s\'ha importat cap activitat. Possible causa de l\'error: paràmetres insuficients.'));
        }
        return system::redirect(ModUtil::url('Cataleg', 'user', 'view'));
    }

    /**
     * Mostra tota la informació d'una activitat i totes les dades associades
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **actId** Identificador de l'activitat
     * * boolean **pdf** True si es vol generar un document en format PDF de l'activitat. False en qualsevol altre cas
     * * boolean **doc** True si s'està generant el document PDF complet del catàleg. False en qualsevol altre cas
     * * string **sec** El nom que s'ha donat a la secció de les activitats al formulari de configuració de la generació del document PDF del catàleg general
     * 
     * ### Paràmetres rebuts per GETPOST:
     * * string **back** Informació per generar l'URL de l'enllaç "Tornar" a la plantilla de vista de l'activitat (Cataleg_user_activitat.tpl)  
     *      
     * @return void
     */
    public function show($args) {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        // Obtenir paràmetres
        $actId = isset($args['actId']) ? $args['actId'] : FormUtil::getPassedValue('actId', null, 'GET');
        $doPDF = FormUtil::getPassedValue('pdf', false, 'GET');
        $back = FormUtil::getPassedValue('back', null, 'GETPOST');
        $doc = isset($args['doc']) ? $args['doc'] : (int) FormUtil::getPassedValue('doc', false, 'GET');
        $sec = isset($args['sec']) ? $args['sec'] : (int) FormUtil::getPassedValue('sec', false, 'GET');

        if ($actId && is_numeric($actId)) {
            if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'show', 'id' => $actId))) {
                $activitat = ModUtil::apiFunc('Cataleg', 'user', 'getActivitat', $actId);
                $centres = ModUtil::apiFunc('Cataleg', 'user', 'getCentres', $actId);
                $llocs = ModUtil::apiFunc('Cataleg', 'user', 'getActsZona', $actId);
                $persDest = ModUtil::apiFunc('Cataleg', 'user', 'gettxtInfo', array('mode' => 'array', 'valor' => $activitat['destinataris']));
                $gestio = ModUtil::apiFunc('Cataleg', 'user', 'gettxtInfo', array('mode' => 'gestio', 'valor' => $activitat['gestio']));
                $unitat = ModUtil::apiFunc('Cataleg', 'user', 'gettxtInfo', array('mode' => 'unitat', 'valor' => $activitat['uniId']));
                $modalitat = ModUtil::apiFunc('Cataleg', 'user', 'gettxtInfo', array('mode' => 'array', 'valor' => array($activitat['curs'], $activitat['presencialitat'], $activitat['abast'])));

                $view = Zikula_View::getInstance('Cataleg', false);
                $view->assign('cr_date', date('d/m/Y  H:i', strtotime($activitat['cr_date'])));
                $view->assign('lu_date', date("d/m/Y  H:i", strtotime($activitat['lu_date'])));
                $view->assign('activitat', $activitat);
                $view->assign('centres', $centres);
                $view->assign('actsZona', $llocs);
                $view->assign('destinataris', $persDest);
                $view->assign('modalitat', $modalitat);
                $view->assign('gestio', $gestio);
                $view->assign('unitat', $unitat);

                if ($doPDF || $doc) {
                    // Descripció de l'activitat
                    $html = $view->fetch('user/Cataleg_user_activitatPDF.tpl');
                    // Creació de l'objecte per a la capçalera del pdf
                    $headerView = Zikula_View::getInstance('Cataleg', false);
                    if (strlen($sec) > 0)
                        $l1 = $sec . " - " . $activitat['cataleg']['nom'];
                    else
                        $l1 = $activitat['cataleg']['nom'];
                    $headerView->assign('l1', $l1);
                    $l2 = $activitat['tGTAF'] ? $activitat['tGTAF'] . " - " . $activitat['titol'] : $activitat['titol'];
                    $headerView->assign('l2', $l2);
                    $headerView->assign('mode', 'act');
                    $htmlHeader = $headerView->fetch('user/Cataleg_user_pdfHeader.tpl');
                }
                if ($doPDF && !$doc) {
                    // Generació del PDF *
                    // Pàgina simple de l'activitats
                    $footer = array(
                        'L' => array(
                            'content' => $this->__('Data de publicació: ') . $activitat['cr_date'],
                            'font-size' => 8,
                            'font-style' => 'I',
                            'font-family' => 'arial',
                            'color' => '#555555'
                        ),
                        'C' => array(
                            'content' => '{PAGENO}/{nb}',
                            'font-size' => 8,
                            'font-style' => '',
                            'font-family' => 'arial',
                            'color' => '#555555'
                        ),
                        'R' => array(
                            'content' => $this->__('Darrera modificació: ') . $activitat['lu_date'],
                            'font-size' => 8,
                            'font-style' => 'I',
                            'font-family' => 'arial',
                            'color' => '#555555'
                        ),
                        'line' => 1,
                    );

                    $pdf = ModUtil::func($this->name, 'user', 'startPdf');
                    ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $htmlHeader, 'content' => $html, 'footer' => $footer));
                    ModUtil::func($this->name, 'user', 'closePdf', array('pdf' => $pdf, 'filename' => 'act.pdf', 'dest' => 'D'));
                } elseif ($doc) {
                    // Generació del PDF 
                    // Document general del catàleg
                    return array('html' => $html, 'header' => $htmlHeader);
                } else { // No generar pdf i mostrar informació activitat en html                
                    $unitats[] = ModUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $activitat['uniId']));
                    $view->assign('unitats', $unitats);
                    $view->assign('canEdit', ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'edit', 'id' => $actId)));
                    $view->assign('back', $back);
                    return $view->fetch('user/Cataleg_user_activitat.tpl');
                }
            } else {
                $view = Zikula_View::getInstance('Cataleg', false);
                $view->assign('icon', 'important.png');
                $view->assign('msg', $this->__('Les activitats d\'aquest catàleg encara no estàn disponibles.<br /><br />Disculpeu les molèsties.'));
                return $view->fetch('user/Cataleg_user_msg.tpl');
            }
        } else { // No hi ha actId
            return LogUtil::registerError($this->__('No es pot mostrar cap activitat. Manca un paràmetre (actId).'));
        }
    }

    /**
     * Crea una instància d'objecte mPDF
     * 
     * @return object mPDF 
     */
    public function startPdf() {
        ob_end_clean();
        $pdfClass = DataUtil::formatForOS('modules/Cataleg/includes/mpdf/mpdf.php');
        include $pdfClass;

        //mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
        $pdf = new mPDF('c', 'A4', 10, 'Helvetica', 25, 15, 30, 16, 8, 10);
        $pdf->defaultheaderfontsize = 8; // in pts 
        $pdf->defaultheaderfontstyle = ''; // blank, B, I, or BI 
        $pdf->defaultheaderline = 1;  // 1 to include line below header/above footer

        $pdf->defaultfooterfontsize = 8; // in pts 
        $pdf->defaultfooterfontstyle = B; // blank, B, I, or BI 
        $pdf->defaultfooterline = 1;  // 1 to include line below header/above footer

        return $pdf;
    }

    /**
     * Genera la sortida i tanca el document pdf
     * 
     * @param array $args Array amb els paràmetres de la funció
     * 
     * ### Paràmetres de l'array $args:
     * * object **pdf** Objecte pdf
     * * string **filename** Nom de l'arxiu pdf a crear
     * * char **dest** Destí de l'arxiu produït **D**' força la descàrrega / **I** mostra al navegador
     * 
     * @return void
     */
    public function closePdf($args) {
        $pdf = isset($args['pdf']) ? $args['pdf'] : null;
        $filename = isset($args['filename']) ? $args['filename'] : 'cataleg.pdf';
        $dest = isset($args['dest']) ? $args['dest'] : 'D';
        if ($pdf) {
            $pdf->Output($filename, $dest);
        }
    }

    /**
     * Afegeix contingut al document pdf
     * 
     * @param array $args Array amb els paràmetres de la funció
     * 
     * ### Paràmetres de l'array $args:
     * * object **pdf** Objecte pdf on s'afegeix contingut
     * * text **header** Capçalera de la pàgina
     * * text **content** Contingut de la pàgina
     * * string **footer** Peu de la pàgina
     * 
     * @return boolean  
     */
    public function addContentPdf($args) {
        $pdf = isset($args['pdf']) ? $args['pdf'] : null;

        $htmlHeader = isset($args['header']) ? $args['header'] : '';
        $html = isset($args['content']) ? $args['content'] : '';
        $footer = isset($args['footer']) ? $args['footer'] : '';

        if (!is_null($pdf)) {
            $pdf->SetHTMLHeader($htmlHeader, 'O', true);
            $pdf->SetHTMLHeader($htmlHeader, 'E', true);
            $pdf->SetFooter($footer, 'O');
            $pdf->SetFooter($footer, 'E');
            $pdf->writeHTML($html);
            return true;
        } else
            return false;
    }

    /**
     * Esborra una activitat i totes les dades associades:
     * > Taules: 
     * > * activitats 
     * > * activitatsZona 
     * > * centresActivitat 
     * > * contactes
     * 
     * ### Paràmetres rebuts per GETPOST:
     * * integer **actId** Identificador de l'activitat
     * * boolean **c**  Confirmació de l'eliminació. True si s'ha confirmat, false en cas contrari
     *       
     * @return boolean
     */
    public function delete() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_DELETE));

        // id de l'activitat a esborrar
        $id = FormUtil::getPassedValue('actId', null, 'GETPOST');
        $confirm = FormUtil::getPassedValue('c', 0, 'GETPOST');
        if (!isset($haveAccess)) {
            $haveAccess = ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'delete', 'id' => $id));
        }
        if (is_numeric($id) && $haveAccess) {
            if ($confirm == 1) { //S'ha confirmat l'esborrament de l'activitat
                $result = ModUtil::apiFunc('Cataleg', 'user', 'delete', array('que' => 'activitat', 'id' => $id));
                if (!$result)
                    LogUtil::registerError($this->__('No s\'ha pogut esborrar l\'activitat.'));
                else
                    LogUtil::registerStatus($this->__("L'activitat s'ha esborrat correctament."));
            }else {
                $view = Zikula_View::getInstance('Cataleg', false);
                $info = ModUtil::apiFunc('Cataleg', 'user', 'getActivitat', $id);
                $view->assign('info', $info);
                return $view->fetch('user/Cataleg_user_delete.tpl');
            }
        } else {
            $view = Zikula_View::getInstance('Cataleg', false);
            $view->assign('icon', 'important.png');
            $view->assign('msg', $this->__('No teniu permís per esborrar l\'activitat.'));
            return $view->fetch('user/Cataleg_user_msg.tpl');
        }
        return system::redirect(ModUtil::url('Cataleg', 'user', 'view'));
    }

    /**
     *  Mostrar llistat de totes les activitats d'un catàleg, agrupades per unitats, a
     *  les que un usuari té accés com a editor o gestor.  
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **catId** Identificador del catàleg
     * * integer **filter** Només es mostraran les activitats en estat = filter. Si filter = -1 es mostren totes les activitats amb independència del seu estat
     *       
     * @return void
     */
    public function view() {
        // Check permission
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD))
            return LogUtil::registerError($this->__('Es necessiten permisos d\'edició per accedir a "Les meves activitats".'), null, ModUtil::url('Cataleg', 'user', 'cataleg'));

        // Si no s'especifica $catId s'agafa per defecte la id del catàleg de treball
        $catId = FormUtil::getPassedValue('catId', ModUtil::getVar($this->name, 'treball'), 'GET');
        // Valor per filtrar les activitats segons estat triat a la plantilla 
        $filter = FormUtil::getPassedValue('filter', isset($_SESSION['cat' . $catId]['filter']) ? $_SESSION['cat' . $catId]['filter'] : -1, 'GET');
        if (!is_numeric($filter) || $filter < -1 || $filter > Cataleg_Constant::ANULLADA)
            $filter = -1;
        $_SESSION['cat' . $catId]['filter'] = $filter;

        if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'view', 'id' => $catId))) {
            // Verificar que existeix id del catàleg
            if (!is_null($catId) && is_numeric($catId)) {
                //Obtenir informació del catàleg 
                $cataleg = ModUtil::apiFunc($this->name, 'user', 'getCataleg', array('catId' => $catId));

                // Comprovar que el catàleg catId existeix (conté informació)
                if (!is_null($cataleg)) {
                    $estats = ModUtil::apiFunc('Cataleg', 'user', 'getEstatsActivitat');
                    $unitats = array();
                    $unis = array();
                    // Recuperem les unitats a les quals pertany l'usuari, relacionades amb un catàleg
                    $unis = ModUtil::apiFunc('Cataleg', 'user', 'getAllUserUnits', array('uid' => UserUtil::getVar('uid'),
                                'catId' => $catId)
                    );
                    $nunis = 0;

                    foreach ($unis as $j => $unitat) {
                        $nacts = 0;
                        $acts = array();
                        // Obtenim les activitats de cadascuna de les unitats de l'usuari i catàleg
                        $acts = ModUtil::apiFunc('Cataleg', 'user', 'getAllUnitatActivitats', array('uniId' => $unitat['uniId'],
                                    'catId' => $catId, 'filter' => $filter));

                        foreach ($acts as $l => $act) {
                            $nacts++;
                            $options = array();
                            $options[] = array('url' => ModUtil::url('Cataleg', 'user', 'show', array('actId' => $act['actId'],
                                    'back' => 'view')),
                                'image' => 'kview.png',
                                'title' => $this->__('Mostra'));

                            // Comprovar si l'activitat és editable en funció de l'estat del catàleg i dels permisos de l'usuari
                            if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'edit', 'id' => $act['actId']))) {
                                $options[] = array('url' => ModUtil::url('Cataleg', 'user', 'edit', array('id' => $act['actId'],
                                        'show' => false,
                                        'back' => 'view')),
                                    'image' => 'xedit.png',
                                    'title' => $this->__('Edita'));
                            }
                            // Comprovar si l'activitat es pot esborrar en funció de l'estat del catàleg i dels permisos de l'usuari
                            if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'delete', 'id' => $act['actId']))) {
                                $options[] = array('url' => ModUtil::url('Cataleg', 'user', 'delete', array('actId' => $act['actId'])),
                                    'image' => '14_layer_deletelayer.png',
                                    'title' => $this->__('Esborra'));
                            }

                            $acts[$l]['options'] = $options;
                            $acts[$l]['estat'] = $estats[$act['estat']];
                            $acts[$l]['n_estat'] = $act['estat'];
                        }
                        if ($nacts) {
                            $unis[$j]['activitats'] = $acts;
                            $nunis++;
                        }
                        $unitats[] = ModUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $unitat['uniId']));
                    }
                    if ($nunis)
                        $cataleg['unitats'] = $unis;

                    $view = Zikula_View::getInstance('Cataleg', false);
                    $view->assign('titol', $this->__('Les meves activitats'));
                    $view->assign('cataleg', $cataleg);
                    $view->assign('unitats', $unitats);
                    $view->assign('filter', $filter);
                    $view->assign('txtfilter', $filter > -1 ? $estats[$filter] : null);
                    $view->assign('isGestor', SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN));

                    return $view->fetch('user/Cataleg_user_view.tpl');
                }else {
                    return LogUtil::registerError($this->__('No es pot accedir. El catàleg (catId: ' . $catId . ') no existeix.'), null, ModUtil::url('Cataleg', 'user', 'cataleg')
                    );
                }
            } else {
                return LogUtil::registerError($this->__('Falta especificar un catàleg o definir un catàleg de treball.'), null, ModUtil::url('Cataleg', 'user', 'cataleg'));
            }
        } else {
            $view = Zikula_View::getInstance('Cataleg', false);
            $view->assign('icon', 'important.png');
            $view->assign('msg', $this->__('No teniu accés a "Les meves activitats".<br /><br />Possibles causes: <br /><ol><li Value = 1>Identificador de catàleg incorrecte</li><li Value = 2>No s\'ha definit cap catàleg de treball</li></ol>'));
            return $view->fetch('user/Cataleg_user_msg.tpl');
        }
    }

    /**
     *  Mostra la fitxa d'orientacions relatives a una prioritat.
     *
     * > La sortida pot ser com a plantilla en html o en format pdf.
     * > També és possible mostrar per editar els camps orientacions i recursos.
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **priId** Identificador de la prioritat
     * * boolean **do_pdf** True si es vol generar un document PDF amb el contingut de la orientació
     * * boolean **doc**  True si es vol incloure el contingut de la orientació en el document PDF del catàleg general
     * * boolean **edito** Si true permet editar els camps "orientacions" i "recursos"
     *      
     * @return void
     */
    public function display($args) {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $priId = isset($args['priId']) ? $args['priId'] : (int) FormUtil::getPassedValue('priId', 1, 'GET');
        $do_pdf = isset($args['pdf']) ? $args['pdf'] : (int) FormUtil::getPassedValue('pdf', false, 'GET');
        $doc = isset($args['doc']) ? $args['doc'] : (int) FormUtil::getPassedValue('doc', false, 'GET');
        $edito = isset($args['edito']) ? $args['edito'] : (int) FormUtil::getPassedValue('edito', 0, 'GET');

        if (isset($priId) && is_numeric($priId)) {
            $priInfo = Modutil::apifunc('Cataleg', 'user', 'getprioritat', array('priId' => $priId));
            $cataleg = $priInfo['cataleg'];
            if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'display', 'id' => $cataleg['catId']))) {
                $eix = $priInfo['eix'];
                $item = ModUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $priId));
                $subpri = ModUtil::apiFunc('Cataleg', 'user', 'getAllSubprioritats', array('priId' => $priId));
                // Busquem les unitats implicades en alguna activitat relacionada amb aquesta prioritat
                $uniImplicades = ModUtil::apiFunc('Cataleg', 'user', 'getUnitatsImplicades', array('priId' => $priId));
                $unitats = ModUtil::apiFunc('Cataleg', 'user', 'getUnitatsInfoByPriId', array('priId' => $priId));

                $view = Zikula_View::getInstance('Cataleg', false);

                $pdfDoc = (($do_pdf) || ($doc));  // Es vol generar un pdf ja sigui com a part del catàleg general o no
                $view->assign('pdf', $pdfDoc);
                $view->assign('eix', $eix);
                $view->assign('cataleg', $cataleg);
                $view->assign('item', $item);
                $view->assign('subpri', $subpri);
                $view->assign('uniImplicades', $uniImplicades);
                $view->assign('unitats', $unitats);
                $view->assign('isGestor', ModUtil::apiFunc('Cataleg', 'user', 'isUserGestor'));
                // Obtenim estat del catàleg
                $catInfo = ModUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $cataleg['catId']));
                // Mostrar o no enllaç a activitats des de les orientacions
                $view->assign('showLinkActs', $catInfo['estat'] >= Cataleg_Constant::ACTIVITATS);
                if ($edito && !$do_pdf) {
                    $html = $view->fetch('user/Cataleg_user_edito.tpl');
                } else {
                    $html = $view->fetch('user/Cataleg_user_display.tpl');
                }
                if ($do_pdf) {
                    // Generem el PDF de la fitxa d'orientacions
                    // preparem el PDF
                    // Informació a mostrar
                    // Creació de l'objecte per a la capçalera del pdf
                    $headerView = Zikula_View::getInstance('Cataleg', false);
                    $l1 = $cataleg['nom'];
                    $headerView->assign('l1', $l1);
                    $l2 = $this->__("Prioritat") . " " . $item['ordre'] . ".- " . $item['nom'];
                    $headerView->assign('l2', $l2);
                    //$headerView->assign('mode', 'act');
                    $htmlHeader = $headerView->fetch('user/Cataleg_user_pdfHeader.tpl');
                    $pdf = ModUtil::func($this->name, 'user', 'startPdf');
                    // preparem el PDF                   
                    $footer = array(
                        'C' => array(
                            'content' => '{PAGENO}/{nb}',
                            'font-size' => 8,
                            'font-style' => '',
                            'font-family' => 'arial',
                            'color' => '#555555'
                        ),
                        'line' => 1,
                    );
                    ModUtil::func($this->name, 'user', 'addContentPdf', array('pdf' => $pdf, 'header' => $htmlHeader, 'content' => $html, 'footer' => $footer));
                    ModUtil::func($this->name, 'user', 'closePdf', array('pdf' => $pdf, 'filename' => 'orientacio.pdf', 'dest' => 'D'));
                } else { // no doPDF
                    return $html;
                }
            } else {
                // No accés
                $view = Zikula_View::getInstance('Cataleg', false);
                $view->assign('icon', 'important.png');
                $view->assign('msg', $this->__('Les orientacions de les línies prioritàries d\'aquest catàleg encara no estàn disponibles.<br /><br />Disculpeu les molèsties.'));
                return $view->fetch('user/Cataleg_user_msg.tpl');
            }
        } // No s'ha determinat priId
        else {
            return LogUtil::registerError($this->__('No es pot mostrar cap orientació. No s\'ha especificat prioritat.'));
        }
    }

    /**
     *  Mostra la llista d'activitats relacionades amb una prioritat determinada.
     * 
     * ### Paràmetres rebuts per GET:
     * * integer **priId** Identificador de prioritat
     * * integer **catId** Identificador de catàleg
     * * integer **page**  En el procés de paginat, indica la pàgina a mostrar 
     *      
     * @return void
     */
    public function activitats($args) {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $priId = isset($args['priId']) ? $args['priId'] : (int) FormUtil::getPassedValue('priId', null, 'GET');
        $catId = isset($args['catId']) ? $args['catId'] : (int) FormUtil::getPassedValue('catId', null, 'GET');
        $page = isset($args['page']) ? $args['page'] : (int) FormUtil::getPassedValue('page', 1, 'GET');

        if ($priId && is_numeric($priId)) {
            if (!$catId)
                $catId = ModUtil::apiFunc($this->name, 'user', 'getParent', array('id' => 'priId', 'value' => $priId));

            $view = Zikula_View::getInstance('Cataleg', false);

            if (ModUtil::apiFunc('Cataleg', 'user', 'haveAccess', array('accio' => 'activitats', 'id' => $catId))) {

                $defaultItemsPerPage = Cataleg_Constant::ITEMSPERPAGE;
                $itemsperpage = isset($args['itemsperpage']) ? $args['itemsperpage'] : (int) FormUtil::getPassedValue('itemsperpage', $defaultItemsPerPage, 'GET');
                $startnum = (($page - 1) * $itemsperpage);

                $prioritat = ModUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $priId));
                $activitats = ModUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatActivitats', array('priId' => $priId,
                            'startnum' => $startnum,
                            'itemsperpage' => $itemsperpage));

                $uni = array();
                $unitats = array();
                $guarda = '';
                foreach ($activitats as $activitat) {
                    if ($guarda != $activitat['sprId']) {
                        $subprioritats[$activitat['sprId']] = array('sprId' => $activitat['sprId'],
                            'nomSpr' => $activitat['nomSpr'],
                            'nomCurtSpr' => $activitat['nomCurtSpr'],
                            'ordreSpr' => $activitat['ordreSpr']);

                        $guarda = $activitat['sprId'];
                    }

                    $subprioritats[$activitat['sprId']]['actis'][] = $activitat;

                    // Carreguem arrays per a mostrar fitxa d'unitat inline
                    if (!in_array($activitat['uniId'], $uni)) {
                        $uni[] = $activitat['uniId'];
                        $unitats[] = ModUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $activitat['uniId']));
                    }
                }

                // Assignem els valors per al plugin d'Smarty per produir la pàgina
                $view->assign('do_pdf', $do_pdf);
                $view->assign('prioritat', $prioritat);
                $view->assign('activitats', $activitats);
                $view->assign('unitats', $unitats);
                $view->assign('subprioritats', $subprioritats);
                $view->assign('pager', array('numitems' => ModUtil::apiFunc('Cataleg', 'user', 'countActivitatsPrioritat', array('priId' => $priId)),
                    'itemsperpage' => $itemsperpage));
                // Generem la pàgina html
                return $view->fetch('user/Cataleg_user_activitats.tpl');
            } else {
                return $this->view->assign('icon', 'error.png')
                                ->assign('msg', $this->__('No teniu accés a aquesta informació en aquest catàleg.'))
                                ->fetch('user/Cataleg_user_msg.tpl');
            }
        } else {
            return LogUtil::registerArgsError();
        }
    }

    /**
     * Mostra el formulari de cerca d'activitats.
     *
     * > Si no es passa el **catId** corresponent a un catàleg, s'agafa per defecte l'actiu 
     *
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres rebuts per GET o l'array $args:
     * * integer **catId** [opcional] [default: id del catàleg actiu] Id del catàleg amb què vulguem treballar.
     *
     * @return void Torna el formulari de cerca principal
     */
    public function cerca($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $catId = FormUtil::getPassedValue('catId', isset($args['catId']) ? $args['catId'] : null, 'GET');

        // si no s'ha passat catId, agafem caId del catàleg per actiu
        if (!$catId) {
            $catId = ModUtil::apiFunc('Cataleg', 'user', 'getActiveCataleg');
        }
        $cataleg = ModUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));

        $prioritats = ModUtil::apiFunc('Cataleg', 'user', 'getAllPrioritatsCataleg', array('catId' => $catId));

        // recuperem unitats...   
        $unitats[0] = '';

        $unitatsCatalegActiu = ModUtil::apiFunc('Cataleg', 'user', 'GetAllUnits', array('catId' => $catId));
        foreach ($unitatsCatalegActiu as $uni) {
            $unitats[$uni['uniId']] = $uni['nom'];
        }

        //Recuperem destinataris, modalitats, presencialitat i llocs
        $pcerca = ModUtil::apiFunc('Cataleg', 'user', 'parametresCerca', $catId);
        $destinataris = $pcerca['pdestinataris'];
        $modscurs = $pcerca['pmodscurs'];
        $presencials = $pcerca['ppresencials'];
        $sstt = $pcerca['psstt'];

        // ordenació alfabètica
        asort($sstt);

        $this->view->assign('cataleg', $cataleg['nom']);
        $this->view->assign('catId', $catId);

        $this->view->assign('prioritats', $prioritats);
        $this->view->assign('unitats', $unitats);
        $this->view->assign('destinataris', $destinataris);
        $this->view->assign('modscurs', $modscurs);
        $this->view->assign('presencials', $presencials);
        $this->view->assign('sstt', $sstt);

        $this->view->assign('cerca', true);

        return $this->view->fetch('user/Cataleg_user_cerca.tpl');
    }

    /**
     * Mostra formulari de cerca de formació en centre (opció codi).
     *
     * > Si no es passa el **catId** corresponent a un catàleg, s'agafa per defecte l'actiu \n
     *
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** [opcional] [def: id catàleg actiu] Id del catàleg amb què vulguem treballar. Si no es passa, s'agafa l'actiu
     * 
     * ### Paràmetres rebuts per POST:
     * * string **centres** [opcional] Codis dels centres so·licitats separats per comes.
     * * boolean **consultat** [opcional] Si entrem després de seleccionar
     * * string **selcentre** Centre seleccionat
     *
     * @return void Torna el formulari de cerca de formació en centre
     */
    public function cercacentre($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }
        //extract($args);

        $centres = FormUtil::getPassedValue('centres', isset($args['centres']) ? $args['centres'] : null, 'POST');
        $consultat = FormUtil::getPassedValue('consultat', isset($args['consultat']) ? $args['consultat'] : null, 'POST');
        $selcentre = FormUtil::getPassedValue('selcentre', isset($args['selcentre']) ? $args['selcentre'] : null, 'POST');
        $catId = FormUtil::getPassedValue('catId', isset($args['catId']) ? $args['catId'] : ModUtil::apiFunc('Cataleg', 'user', 'getActiveCataleg'), 'GET');
echo "CATID: ".$catId;
        if (!isset($consultat)) {
            $consultat = '0';
        }
        
        $cataleg = ModUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));        

        // Si s'ha enviat una cerca de centres...
        if ($consultat == '1') {

            // controlem possibles errors sintàctics en l'entrada dels codis
            // comes duplicades o triplicades ...
            $centres = str_replace(',,,,', ',', $centres);
            $centres = str_replace(',,,', ',', $centres);
            $centres = str_replace(',,', ',', $centres);
            // comes finals o inicials...
            if (substr($centres, -1) == ",") {
                $centres = substr($centres, 0, -1);
            }
            if (substr($centres, 0, 1) == ",") {
                $centres = substr($centres, 1);
            }

            // eliminem espais 
            $centresprevi = explode(",", $centres);
            foreach ($centresprevi as $cent) {
                $acentres[] = trim($cent);
            }

            // si tenim algun centre per buscar...
            if ($acentres[0] != '') {
                $activitats = ModUtil::apiFunc('Cataleg', 'user', 'cercaconsultacentre', array('catId' => $catId, 'centres' => $acentres));

                // busquem si algun codi no té activitats
                foreach ($acentres as $cent) {
                    $centres_sol[$cent] = ModUtil::apiFunc('Cataleg', 'user', 'centre', $cent);
                    foreach ($activitats as $ac) {
                        if (trim($ac['centre']) == trim($cent)) {
                            $centres_sol[$cent]['trobat'] = '1';
                        }
                    }
                }
            }
                       
            $this->view->assign('activitats', $activitats);
            $this->view->assign('acentres', $centres_sol);
            $this->view->assign('centres', $centres);
            $this->view->assign('total', count($activitats));
        }
        
        $this->view->assign('catId', $catId);
        $this->view->assign('cataleg', $cataleg['nom']);        
        $this->view->assign('centres', $centres);
        $this->view->assign('selcentre', $selcentre);
        $this->view->assign('consultat', $consultat);
        $this->view->assign('opcio', 'codi');        
        
        return $this->view->fetch('user/Cataleg_user_cerca_encentre.tpl');
    }

    /**
     * Mostra formulari de cerca de formació en centre (opció zona).
     *
     * > Si no es passa el **catId** corresponent a un catàleg, s'agafa per defecte l'actiu 
     *
     * @param array $args Array amb els paràmetres de la funció
     *
     * ### Paràmetres rebuts per GET:
     * * integer **catId** [opcional] [def: id catàleg actiu] Id del catàleg amb què vulguem treballar. Si no es passa, s'agafa l'actiu
     *
     * ### Paràmetres rebuts per POST:
     * * * string **dt** [opcional] Delegació territorial sol·licitada.
     * * boolean **consultat** [opcional] Si entrem amb dades per fer la consulta
     * 
     * @return void Torna el formulari de cerca de formació en centre.
     */
    public function cercazona($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }
        extract($args);

        $dt = FormUtil::getPassedValue('dt', isset($args['dt']) ? $args['dt'] : null, 'POST');
        $consultat = FormUtil::getPassedValue('consultat', isset($args['consultat']) ? $args['consultat'] : null, 'POST');
        $catId = FormUtil::getPassedValue('catId', isset($args['catId']) ? $args['catId'] : ModUtil::apiFunc('Cataleg', 'user', 'getActiveCataleg'), 'GET');
        if (!isset($consultat)) {
            $consultat = '0';
        }

        $cataleg = ModUtil::apiFunc('Cataleg', 'user', 'getCataleg', array('catId' => $catId));

        $selectDT = ModUtil::apiFunc('Cataleg', 'user', 'getDTSelect');

        $this->view->assign('cataleg', $cataleg['nom']);
        $this->view->assign('selecDT', $selectDT);
        $this->view->assign('consultat', $consultat);
        $this->view->assign('opcio', 'zona');
        $this->view->assign('catId', $catId);

        // Si s'ha enviat una cerca de centres...
        if ($consultat == '1') {
            $activitats = ModUtil::apiFunc('Cataleg', 'user', 'cercaconsultazona', array('catId' => $catId, 'dt' => $dt));

            $delegacio = $selectDT[$dt];
            $this->view->assign('activitats', $activitats);
            // $this->view->assign('acentres', $centres_sol);
            $this->view->assign('delegacio', $delegacio);
            $this->view->assign('total', count($activitats));
        }

        return $this->view->fetch('user/Cataleg_user_cerca_encentre.tpl');
    }

    /**
     * Edició/gestió de les temàtiques per prioritat en les que participa o s'implica una unitat
     * @params $uniId id de la unitat
     */
    public function tematiques(){
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));

        $uniId = FormUtil::getPassedValue('uniId', null, 'GET');
        $uni = ModUtil::apiFunc($this->name, 'user', 'getUnitat', array('uniId'=> $uniId, 'simple' => true));

        if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $uniId))) {
            $prioritats = ModUtil::apiFunc($this->name, 'user', 'getAllPrioritatsCataleg', array('catId' => $uni['catId']));
            $result = array();
            foreach ($prioritats as $prioritat) {
                $result[$prioritat['priId']]['prioritat'] = $prioritat['nom'];
                $result[$prioritat['priId']]['ordre'] = $prioritat['ordre'];
                $result[$prioritat['priId']]['tematiques'] = ModUtil::apiFunc($this->name, 'user', 'getUnitatsImplicades', array('priId' => $prioritat['priId'], 'uniId' => $uniId));
            }

            $view = Zikula_View::getInstance('Cataleg', false);
            $view->assign('prioritats', $result);
            $view->assign('unitat', $uni);
            return $view->fetch('user/Cataleg_user_tematiques.tpl');
        }
    }
    
    /**
     * Edició/gestió de les temàtiques per prioritat en les que participa o s'implica una unitat.
     * Aplicació dels nous valors o creació d'una nova entrada
     * @params $uid id de la unitat
     * @params $m (mode e -> edicio // a -> addició)
     * @params $impunitId (id del registre corresponent a la temàtica /unitats implicades
     */
    public function setTematica($args){
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ));
        
        $item = array();               
        $item['impunitId']   = FormUtil::getPassedValue('impunitId', null, 'POST');
        $item['priId']       = FormUtil::getPassedValue('priId', null, 'POST');
        $item['uniId']       = FormUtil::getPassedValue('uniId', null, 'POST');
        $item['tematica']    = FormUtil::getPassedValue('tematica', null, 'POST');
        $item['pContacte']    = FormUtil::getPassedValue('pContacte', null, 'POST');
        $item['email']       = FormUtil::getPassedValue('email', null, 'POST');
        $item['telContacte'] = FormUtil::getPassedValue('telContacte', null, 'POST');
        $item['dispFormador']= FormUtil::getPassedValue('dispFormador', 0, 'POST');
        if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $item['uniId']))) {
        //print_r($item); exit(0);
            ModUtil::apiFunc($this->name, 'admin', 'saveImpunit', $item);
        //return ModUtil::func($this->name, 'user', 'tematiques', array('uniId' => $item['uniId']));
            return system::redirect(ModUtil::url($this->name, 'user', 'tematiques', array('uniId' => $item['uniId'])));                        
        } else LogUtil::registerError ($this_>__('No teniu permís per modificar les temàtiques'));
    }
    
    /**
     * Esborra el registre impunitId especificat a la taula d'unitats implicades/temàtiques
     * @params $impunitId id del registre de la taula unitatsImplicades
     * @params $uniId id de la unitat per verificar permisos i calcular la URL de retorn
     * @return pàgina de temàtiques
     */
    public function deleteImpunit(){
        $impunitId = FormUtil::getPassedValue('impunitId', null, 'GET');
        $uniId = FormUtil::getPassedValue('uniId', null, 'GET');

        if (ModUtil::apiFunc($this->name, 'user', 'haveAccess', array('accio' => 'new', 'id' => $uniId))) {
            $where = 'impunitId =' . $impunitId;
            DBUtil::deleteWhere('cataleg_unitatsImplicades', $where);
            return system::redirect(ModUtil::url($this->name, 'user', 'tematiques', array('uniId' => $uniId)));  
        }
    }
}

