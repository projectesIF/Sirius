<?php

/**
 * @authors Josep Ferràndiz Farré <jferran6@xtec.cat>
 * @authors Joan Guillén Pelegay  <jguille2@xtec.cat> 
 * 
 * @par Llicència: 
 * GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Cataleg
 * @version 1.0
 * @copyright Departament d'Ensenyament. Generalitat de Catalunya 2012-2013
 */

include 'vendor/csvImporter/CsvImporter.php';

class Llicencies_Controller_Admin extends Zikula_AbstractController {

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
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));

        $cursos = ModUtil::apiFunc($this->name, 'user', 'getYears');
        $temes = ModUtil::apiFunc($this->name, 'user', 'getTopicList');
        $subtemes = ModUtil::apiFunc($this->name, 'user', 'getSubtopicList');
        $tipus = ModUtil::apiFunc($this->name, 'user', 'getTypeList');
        $estats = ModUtil::apiFunc($this->name, 'user', 'getEstats');

        $this->view->assign('cursos', $cursos);
        $this->view->assign('temes', $temes);
        $this->view->assign('subtemes', $subtemes);
        $this->view->assign('tipus', $tipus);
        $this->view->assign('estats', $estats);

        //echo "<pre>"; print_r($estats); echo "</pre>"; 
        $view = Zikula_View::getInstance($this->name, false);
        return $view->fetch('Llicencies_admin_main.tpl');
    }

    /*
     * Configuració dels paràmeters del mòdul
     * docRoot: url on s'allotgen les memòries o els resums de les memòries en format digital
     * Actualment aquest paràmetre és: http://www.xtec.cat/sgfp/llicencies/
     */

    public function getConfig() {
        // Security check will be done in catalegsgest()
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));

        $docRoot = ModUtil::getVar($this->name, 'LlicenciesDocRoot');

        $view = Zikula_View::getInstance('Llicencies', false);
        $view->assign('root', $docRoot);

        return $view->fetch('Llicencies_admin_config.tpl');
    }

    /*
     * Formulari per seleccionar la taula per importar/exportar des de o cap a
     * un arxiu csv
     * 
     * @return void (zikula_view: Llicencies_admin_ieTables.tpl)
     */

    public function ieTables() {
        // Security check will be done in catalegsgest()
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));

        $post_max_size = ini_get('post_max_size');

        $view = Zikula_View::getInstance('Llicencies', false);
        $a1 = array('llicencies', 'llicencies_curs', 'llicencies_estats', 'llicencies_modalitat', 'llicencies_tema', 'llicencies_subtema', 'llicencies_tipus');
        $t = array_combine($a1, $a1);
        $view->assign('tables', $t);
        $view->assign('post_max_size', $post_max_size);
        return $view->fetch('Llicencies_admin_ieTables.tpl');
    }

    /**
     * Exporta els registres de la taula seleccionada a un fitxer csv
     *      
     * 
     * @return void (carrega la plantilla per importar/exportar taules)
     */
    public function exportaTaula() {
        // Security check
        //$this->checkCsrfToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));
        if ($this->request->isPost()) {
            $taula = $this->request->request->get('taula_exp', false);
        }
        if (is_null($taula)) {
            LogUtil::registerError(__('L\'exportació de dades no és possible. No s\'ha especificat cap taula.'));            
        } else {
            $titlerow = DBUtil::getColumnsArray($taula);
            $datarows = DBUtil::selectObjectArray($taula);

            $date = date('_Ymd_Hi');
            FileUtil::exportCSV($datarows, $titlerow, ';', '"', $taula . $date . '.csv');
        
        }
        // Després de exportCSV no executa aquest redirect
        //return system::redirect(ModUtil::url('Llicencies', 'admin', 'ieTables#tabs-2'));
        $this->redirect(ModUtil::url('llicencies', 'admin', 'ieTables'));
    }

    /**
     * Importa, a la taula seleccionada, les dades d'un csv
     * 
     * Els registres existents s'actualitzen i els nous s'inserten
     * 
     * @return void (carrega la plantilla per importar/exportar taules)
     */
    public function importaTaula() {

        // Security check 
        $this->checkCsrfToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));
        if ($this->request->isPost()) {
            $taula = $this->request->request->get('taula_imp', false);
            $importFile = $this->request->files->get('importFile', null);
        }

        if (is_null($importFile)) {
            LogUtil::registerError(__('No s\'ha pogut processar l\'arxiu. Probablement supera la mida màxima.'));
        } else {
            $import = new CsvImporter($importFile['tmp_name'], true, null,';');

            $header = $import->getHeader();

            $check = ModUtil::apiFunc($this->name, 'admin', 'checkCSV', array('dbTable' => $taula, 'csvHeader' => $header));

            // Comprovar capçaleres del csv
            if (!$check['correcte']) {
                // Errades a l'arxiu CSV
                LogUtil::registerError($check['msg']);
            } else {
                // Obtenció del contingut del fitxer csv
                $data = $import->get();
                // Obtenció de les dades de la taula
                $tContent = DBUtil::selectFieldArray($taula, $check['clau']);
                // echo '<pre> tContent: ';print_r($tContent); echo '</pre>';

                LogUtil::registerStatus($check['msg']);
                //LogUtil::registerStatus(print_r($data,true));
                $update = array();
                $insert = array();
                foreach ($data as $row => $record) {
                    if (in_array($record[$check['clau']], $tContent)) {
                        $update[] = $record;
                    } else {
                        $insert[] = $record;
                    }
                }

                $inserts = count($insert);
                $updates = count($update);
                $ins = true;
                $upd = true;
                if ($inserts) {
                    $ins = (DBUtil::insertObjectArray($insert, $taula) && ($inserts));
                    $mi = __('S\'han afegit ' . $inserts . ' registres.');
                }
                if ($updates) {
                    $upd = (DBUtil::updateObjectArray($update, $taula, $check['clau'])) && ($updates);
                    $mu = __('S\'han actualitzat ' . $updates . ' registres.');
                }
                if (($ins) && ($upd))
                    LogUtil::registerStatus(__('La importació de dades cap a la taula:' . $taula . ' s\'ha realitzat correctament.') . " " . $mi . " " . $mu);
                else
                    LogUtil::registerError(__('No s\'han pogut modificar totes les dades de la taula: ' . $taula));
            }
        }
        $this->redirect(ModUtil::url('llicencies', 'admin', 'ieTables'));
    }

}
