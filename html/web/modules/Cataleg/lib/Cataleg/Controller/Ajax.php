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

class Cataleg_Controller_Ajax extends Zikula_Controller_AbstractAjax {

    public function edit() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per accedir a aquest mòdul.'));
        }

        $actId = $this->request->getPost()->get('actId', '');
        if (!isset($actId)) {
            throw new Zikula_Exception_Fatal($this->__('Falta un paràmetre: actId'));
        }

        $content = ModUtil::func('Cataleg', 'user', 'edit', array('id' => $actId));
        return new Zikula_Response_Ajax(array('content' => $content));
    }

    /* Funció : getEix
     * 		Obtenir eix donada una prioritat
     * @author: Josep Ferràndiz i Farré (jferran6@xtec.cat)
     * @param : prId id de la prioritat
     * @return: Array amb informació de l'eix
     */

    public function getEix() {

        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per accedir a aquest mòdul.'));
        }

        $prId = $this->request->getPost()->get('priId', '');
        $cerca = $this->request->getPost()->get('cerca', '');

        if (isset($prId)) {
            //Busquem informació de la prioritat per obtenir informació de l'eix. 
            $pr = ModUtil::apiFunc($this->name, 'user', 'getPrioritat', array('priId' => $prId));
            // Preparem les dades per retornar a la plantilla
            $view = Zikula_View::getInstance($this->name);
            $view->assign('eixinfo', $pr['eix']);
            $view->assign('cerca', $cerca);
            // Carreguem la plantilla amb les dades de l'eix
            $content = $view->fetch('user/Cataleg_user_eix.tpl');
            return new Zikula_Response_Ajax(array('content' => $content));
        } else {
            throw new Zikula_Exception_Fatal($this->__('Falta un paràmetre: priId'));
        }
    }

    /**
     * @name  : getSubPrioritats
     * 		Obtenir subprioritats donada una prioritat
     * 
     * @param : priId id de la prioritat
     * @return: Array amb les subprioritats
     */
    public function getSubPrioritats() {

        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per accedir a aquest mòdul.'));
        }
        //$prId = FormUtil::getPassedValue('priId', isset($args['priId']) ? $args['priId'] : null, 'GET');
        $prId = $this->request->getPost()->get('priId', '');
        $cerca = $this->request->getPost()->get('cerca', '');
        if (!isset($prId)) {
            throw new Zikula_Exception_Fatal($this->__('Falta un paràmetre: priId'));
        }
        $content = null;
        if ($prId) {
            // Obtenim les subprioritats de la prioritat triada
            $sp = ModUtil::apiFunc($this->name, 'user', 'getAllSubprioritats', array('priId' => $prId));

            // Preparem les dades per retornar a la plantilla
            $view = Zikula_View::getInstance('Cataleg', false);
            $view->assign('subpr', $sp);
            $view->assign('cerca', $cerca);
            // Carreguem la plantilla amb les dades
            $content = $view->fetch('user/Cataleg_user_selectSubPrioritat.tpl');
        }
        return new Zikula_Response_Ajax(array('content' => $content));
    }
        
    /**
     *  En el procés d'importació d'activitats (Cataleg_user_import.tpl) actualitza 
     *  la llista d'unitats origen de la importació
     */
    public function updateImportUnits() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per a realitzar aquesta acció.'));
        }

        $catOrig = $this->request->getPost()->get('catOrig', '');
        $catDest = $this->request->getPost()->get('catDest', '');

        $content = null;
        if (isset($catOrig) && is_numeric($catOrig)) {
            // Obtenim la llista d'unitats
            $ud = ModUtil::apiFunc($this->name, 'user', 'getAllUserUnits', array('catId' => $catDest, 'fields' => array('uniId', 'catId', 'nom', 'gzId')));
            $uo = ModUtil::apiFunc($this->name, 'user', 'getAllUserUnits', array('catId' => $catOrig, 'fields' => array('uniId', 'catId', 'nom', 'gzId')));

            /* $d = $catDest." - ".print_r($ud, true);
              $o = $catOrig." - ".print_r($uo, true);
              throw new Zikula_Exception_Fatal("desti: ".$d." Origen: ".$o);
             */
            $view = Zikula_View::getInstance('Cataleg', false);
            $view->assign('uOrig', $uo);
            $view->assign('uDest', $ud);
            // Carreguem la plantilla amb les dades
            $content = $view->fetch('user/Cataleg_user_importUnits.tpl');
        } else {
            throw new Zikula_Exception_Fatal($this->__('Falta un paràmetre: no s\'ha especificat cap catàleg.'));
        }
        // Actualitzem la plantilla
        return new Zikula_Response_Ajax(array('content' => $content));
    }

    
    /**
     *  En el procés d'importació d'activitats (Cataleg_user_import.tpl) actualitza 
     *  la llista d'activitats a importar en funció del catàleg origen i la unitat
     *  del catàleg origen
     */
    public function updateActsList() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADD)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per a realitzar aquesta acció.'));
        }

        $catOrigId = $this->request->getPost()->get('catOrig', '');
        $catDestId = $this->request->getPost()->get('catDest', '');
        $uniId = $this->request->getPost()->get('uniId', '');

        $content = null;
        if (isset($uniId) && is_numeric($uniId)) {
            // Obtenim la llista d'unitats
            $content = ModUtil::func($this->name, 'user', 'updateActsList', array('uniId' => $uniId,
                        'catOrigId' => $catOrigId,
                        'catDestId' => $catDestId));
            /*
              $acts = ModUtil::apiFunc($this->name, 'user', 'getAllUnitatActivitats', array('uniId' => $uniId));
              $equiv = ModUtil::apiFunc($this->name, 'user', 'getCompatTable', array('catOrigId' => $catOrigId, 'catDestId' => $catDestId));
              $view = Zikula_View::getInstance('Cataleg', false);
              $view->assign('acts', $acts);
              $view->assign('equiv', $equiv);
              // Carreguem la plantilla amb les dades
              $content = $view->fetch('user/Cataleg_user_importActsList.tpl');
             */
        } else {
            throw new Zikula_Exception_Fatal($this->__('Falta un paràmetre: no s\'ha especificat cap unitat.'));
        }
        // Actualitzem la plantilla
        return new Zikula_Response_Ajax(array('content' => $content));
    }

    /**
     * Actualització de les variables de mòdul relatives al bloc de novetats del 
     * catàleg i la data de publicació.
     */
    public function updateConfig(){

        $dies = $this->request->getPost()->get('dies', '');
        $dataPublicacio = $this->request->getPost()->get('dp', '');
        $showNew = (bool)$this->request->getPost()->get('showNew', '');
        $showMod = (bool)$this->request->getPost()->get('showMod', '');
        $dataOk = $this->request->getPost()->get('dataOk', '');

        $novetats  = array();
        $novetats['diesNovetats'] = $dies;
        $novetats['showNew'] = $showNew;
        $novetats['showMod'] = $showMod;
        
        if ($dataOk)  $novetats['dataPublicacio'] = $dataPublicacio;
        // Actualitzem la variable de mòdul
        ModUtil::setVar($this->name, 'novetats', $novetats);

        $view = Zikula_View::getInstance($this->name, false);       
        $view->assign('dies',$dies);
        $view->assign('dp',  $dataPublicacio);
        $view->assign('showNew', $showNew);
        $view->assign('showMod', $showMod);
        $view->assign('noblock', true);
        
        $content = $view->fetch('block/Cataleg_block_config.tpl');
        return new Zikula_Response_Ajax(array('content' => $content));   

    }
    /**
     * @name  : refreshDefauls
     * 		Obtenir valors per defecte en l'organització/gestió d'una activitat
     *          donada una opció organitzativa determinada (SE, ST o Unitat)
     * 
     * @param : def valor de l'opció escollida
     * @return: plantilla amb les opcions per defecte corresponents
     */
    public function refreshDefauls($args) {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per accedir a aquest mòdul.'));
        }

        $opc = $this->request->getPost()->get('def', '');
        if (!$opc) {
            throw new Zikula_Exception_Fatal($this->__('Falta un paràmetre.'));
        }
        $opsGest = ModUtil::apiFUnc('Cataleg', 'user', 'getOpcionsGestio');
        $default = (!is_null($opc)) ? 'op' . $opc : null;

        $view = Zikula_View::getInstance('Cataleg', false);
        $view->assign('def', $default);
        $view->assign('opsGest', $opsGest);
        // Carreguem la plantilla amb les dades
        $content = $view->fetch('user/Cataleg_user_elemGestio.tpl');

        return new Zikula_Response_Ajax(array('content' => $content));
    }

    /**
     *   Revisió de l'existència dels centres introduïts
     *   
     * @return Zikula_Response_Ajax
     * @throws Zikula_Exception_Fatal
     */
    public function checkCentres() {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per accedir a aquest mòdul.'));
        }

        $centres = $this->request->getPost()->get('centres', '');

        if ($centres) {
            $c = str_replace(" ", "", $centres); // Treiem caràcters en blanc
            // Processar codis dels centres per verificar existència
            $nomsCentres = ModUtil::apiFunc($this->name, 'user', 'checkCentres', array('centres' => $c));

            Zikula_AbstractController::configureView();
            $view = Zikula_View::getInstance('Cataleg', false);
            $view->assign('nomsCentres', $nomsCentres);
            $content = $view->fetch('user/Cataleg_user_centres.tpl');
            $codis = $nomsCentres['codis'];
        }
        return new Zikula_Response_Ajax(array('codis_ori' => $c, // Els codis introduïts a la plantilla
                    'codis' => $nomsCentres['codis'], // Els codis de centre vàlids
                    'content' => $content));
    }

    public function test($args) {

        $view = Zikula_View::getInstance('Cataleg', false);

        $view->assign('msg', "Missatge de prova");

        // Carreguem la plantilla amb les dades
        $content = $view->fetch('user/test.tpl');

        return new Zikula_Response_Ajax(array('content' => $content));
    }

    public function getCerca($args) {

        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per accedir a aquest mòdul.'));
        }

        $items['prioritat'] = $this->request->getPost()->get('prioritat', '');
        $items['subprioritat'] = $this->request->getPost()->get('subprioritat', '');
        $items['unitat'] = $this->request->getPost()->get('unitat', '');
        $items['titol'] = $this->request->getPost()->get('titol', '');
        $items['modcurs'] = $this->request->getPost()->get('modcurs', '');
        $items['lloc'] = $this->request->getPost()->get('lloc', '');
        $items['presencial'] = $this->request->getPost()->get('presencial', '');
        $desti = $this->request->getPost()->get('desti', '');
        $items['destinatari'] = unserialize($desti);
        $items['catId'] = $this->request->getPost()->get('catId', '');


        // Preparem les dades per retornar a la plantilla
        $view = Zikula_View::getInstance($this->name);

        if ($items['titol'] == '' && $items['eix'] == 0 && $items['prioritat'] == 0 && $items['subprioritat'] == 0 && $items['unitat'] == 0 &&
                $items['modcurs'] == 0 && $items['presencial'] == 0 && $items['destinatari'] == 0 && $items['gestor'] == 0 && $items['lloc'] == 0) {
            $view->assign('buida', true);
        } else {
 
            $activitats = ModUtil::apiFunc('Cataleg', 'user', 'cercaConsulta', array('catId' => $items['catId'],
                        'eix' => $items['eix'],
                        'prioritat' => $items['prioritat'],
                        'subprioritat' => $items['subprioritat'],
                        'titol' => $items['titol'],
                        'unitat' => $items['unitat'],
                        'destinatari' => $items['destinatari'],
                        'modcurs' => $items['modcurs'],
                        'presencial' => $items['presencial'],
                        'lloc' => $items['lloc'],
                        'gestor' => $items['gestor']));
        }

        $etiqueta = array('eix' => 'Eix',
            'prioritat' => 'Prioritat',
            'subprioritat' => 'Subprioritat',
            'titol' => 'El títol conté',
            'unitat' => 'Unitat',
            'destinatari' => 'Destinatari',
            'modcurs' => 'Modalitat',
            'presencial' => 'Presencialitat',
            'gestor' => 'Intervé en la gestió',
            'lloc' => 'Lloc');

        if ($titol != "") {
            $infoacti['titol'] = "- El títol conté: '" . $titol . "'";
        }

        foreach ($items as $clau => $valor) {
            if ($valor > 0 && $clau != "catId") {
                if ($clau == 'modcurs' ||
                        $clau == 'presencial' ||
                        $clau == 'gestor' ||
                        $clau == 'lloc') {

                    $mostra = ModUtil::apiFunc('Cataleg', 'user', 'getNomAux', $valor);
                } elseif ($clau == 'destinatari') {
                    $mostra = '';
                    foreach ($items['destinatari'] as $dest) {
                        $mostra .= ModUtil::apiFunc('Cataleg', 'user', 'getNomAux', $dest) . "' o '";
                    }
                    $mostra = substr($mostra, 0, -5);
                } elseif ($clau == 'prioritat') {
                    $prior = ModUtil::apiFunc('Cataleg', 'user', 'getPrioritat', array('priId' => $valor));
                    $mostra = $prior['nomCurt'];
                } elseif ($clau == 'subprioritat') {
                    $subprior = ModUtil::apiFunc('Cataleg', 'user', 'getSubPrioritat', array('sprId' => $valor));
                    $mostra = $subprior['nomCurt'];
                } elseif ($clau == 'unitat') {
                    $unitat = ModUtil::apiFunc('Cataleg', 'user', 'getUnitat', array('uniId' => $valor));
                    $mostra = $unitat['nom'];
                }
                $infoacti[$clau] = "- " . $etiqueta[$clau] . ": '" . $mostra . "'";
            }
        }

        $view->assign('activitats', $activitats);
        $view->assign('infoacti', $infoacti);
        $view->assign('cerca', true);
        $view->assign('catId', $items['catId']);

        // Carreguem la plantilla amb les dades del resultat
        $content = $view->fetch('user/Cataleg_user_resultats.tpl');

        /*
          foreach ($_POST as $clau => $valor) {
          //      $torna .= "|" . $clau . "| = " . $valor . "<br>";
          }

          $torna .= " <br>" . $desti . "<br>";
          $torna .= " Compta \$destinatari = " . count($destinatari) . "<br>";
          if ($destinatari > 0) {
          foreach ($destinatari as $valor) {
          $torna .= $valor . "<br>";
          }
          }
         * 
         */
        return new Zikula_Response_Ajax(array('content' => $content));
    }

}
