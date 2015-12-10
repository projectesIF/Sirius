<?php

/**
 * @authors Josep Ferràndiz Farré <jferran6@xtec.cat>
 * @authors Joan Guillén Pelegay  <jguille2@xtec.cat>
 *
 * @par Llicència:
 * GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Llicencies
 * @version 1.0
 * @copyright Departament d'Ensenyament. Generalitat de Catalunya 2013-2014
 */

class Llicencies_Installer extends Zikula_AbstractInstaller
{
    public function install(){
        if (!SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Crear les taules del mòdul
        if (!DBUtil::createTable('llicencies')||
            !DBUtil::createTable('llicencies_curs')||
            !DBUtil::createTable('llicencies_tema')||
            !DBUtil::createTable('llicencies_subtema')||
            !DBUtil::createTable('llicencies_tipus')||
            !DBUtil::createTable('llicencies_modalitat')||
            !DBUtil::createTable('llicencies_estats') 
            )
            return false;
        ModUtil::setVar($this->name, 'LlicenciesDocRoot', "http://www.xtec.es/sgfp/llicencies/");
        //Successfull
        return true;
    }

    /**
     * Desinstal·lació del mòdul Llicencies
     *
     * @return bool true si ha anat tot bé, false en qualsevol altre cas.
     */
    public function uninstall()
    {
        if (!SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        // Esborrar taules del mòdul
        if (!DBUtil::dropTable('llicencies')||
            !DBUtil::dropTable('llicencies_curs')||
            !DBUtil::dropTable('llicencies_tema')||
            !DBUtil::dropTable('llicencies_subtema')||
            !DBUtil::dropTable('llicencies_tipus')||
            !DBUtil::dropTable('llicencies_modalitat')||
            !DBUtil::dropTable('llicencies_estats')
            )
            return false;
        //Esborrar variables del mòdul
        $this->delVars();
        return true;
    }

    public function upgrade($oldversion){
        return true;
    }

}

