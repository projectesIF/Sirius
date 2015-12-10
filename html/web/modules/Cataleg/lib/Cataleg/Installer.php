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
 
class Cataleg_Installer extends Zikula_AbstractInstaller
{
    public function install(){
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        // Verificar si el mòdul IWusers està instal·lat. Si no ho està, retorna error
        $modid = ModUtil::getIdFromName('IWusers');
        $modinfo = ModUtil::getInfo($modid);
        if ($modinfo['state'] != 3) {
            return LogUtil::registerError($this->__('El mòdul IWusers és imprescindible. Cal tenir-lo instal·lat abans d\'instal·lar el mòdul del catàleg.'));
        }

        // Verificar versió del mòdul IWusers
        $versionNeeded = '3.1.0';
        if ($modinfo['version'] < $versionNeeded) {
            throw new Zikula_Exception_Forbidden($this->__('Versió del mòdul IWusers incorrecta. Versió mínima 3.1.0'));
        }
        // Crear les taules del mòdul
        if (!DBUtil::createTable('cataleg')||
            !DBUtil::createTable('cataleg_eixos')||
            !DBUtil::createTable('cataleg_prioritats')||
            !DBUtil::createTable('cataleg_unitatsImplicades')||
            !DBUtil::createTable('cataleg_subprioritats')|| 
            !DBUtil::createTable('cataleg_activitats')||               
            !DBUtil::createTable('cataleg_activitatsZona')||   
            !DBUtil::createTable('cataleg_unitats')||
            !DBUtil::createTable('cataleg_responsables')||
            !DBUtil::createTable('cataleg_contactes')||
            !DBUtil::createTable('cataleg_auxiliar')||
            !DBUtil::createTable('cataleg_centresActivitat')||
	    !DBUtil::createTable('cataleg_centres')||
            !DBUtil::createTable('cataleg_gestioActivitatDefaults')||
            !DBUtil::createTable('cataleg_importTaules')||
            !DBUtil::createTable('cataleg_importAssign') ||
            !DBUtil::createTable('cataleg_gtafEntities') ||
            !DBUtil::createTable('cataleg_gtafGroups')
            )
            return false;

        ModUtil::setVar($this->name, 'novetats', array('dataPublicacio'=>'','diesNovetats'=> 15, 'showNew' => true, 'showMod' => true));
        
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
        //Successfull
        return true;
    }

    /**
     * Desinstal·lació del mòdul Cataleg
     * 
     * @return bool true si ha anat tot bé, false en qualsevol altre cas.
     */
    public function uninstall()
    {
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        // Esborrar taules del mòdul          
        if (!DBUtil::dropTable('cataleg')||
            !DBUtil::dropTable('cataleg_eixos')||
            !DBUtil::dropTable('cataleg_prioritats')||
            !DBUtil::dropTable('cataleg_unitatsImplicades')||
            !DBUtil::dropTable('cataleg_subprioritats')|| 
            !DBUtil::dropTable('cataleg_activitats')||               
            !DBUtil::dropTable('cataleg_activitatsZona')||   
            !DBUtil::dropTable('cataleg_unitats')||
            !DBUtil::dropTable('cataleg_responsables')||
            !DBUtil::dropTable('cataleg_contactes')||
            !DBUtil::dropTable('cataleg_auxiliar')||
            !DBUtil::dropTable('cataleg_centresActivitat')||
	    !DBUtil::dropTable('cataleg_centres')||
            !DBUtil::dropTable('cataleg_gestioActivitatDefaults')||
            !DBUtil::dropTable('cataleg_importTaules')||
            !DBUtil::dropTable('cataleg_importAssign')||
            !DBUtil::dropTable('cataleg_gtafEntities')||
            !DBUtil::dropTable('cataleg_gtafGroups')
            ) 
        return false;
        //Esborrar variables del mòdul
        $this->delVars();
        // unregister hook handlers
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());
        return true;
    }
    
    public function upgrade($oldversion){
        switch ($oldversion) {
            case '1.0.0':
                if (!DBUtil::createTable('cataleg_gtafEntities') ||
                    !DBUtil::createTable('cataleg_gtafGroups') ){
                    return false;
                }
            case '1.1.0':
                $gZ = ModUtil::getVar('Cataleg','grupsZikula');
                $gZ['Sirius'] = $gZ['Cataleg'];
                unset($gZ['Cataleg']);
                $gZ['ExSirius'] = $gZ['ExCataleg'];
                unset($gZ['ExCataleg']);
                $gZ['Personals'] = $gZ['Usuaris'];
                unset($gZ['Usuaris']);
                $gZ['LectorsCat'] = $gZ['Lectors'];
                unset($gZ['Lectors']);
                $gZ['EditorsCat'] = $gZ['Editors'];
                unset($gZ['Editors']);
                ModUtil::setVar('Cataleg','grupsZikula',$gZ);
                $var2 = ModUtil::getVar('Cataleg', 'usuarisCataleg');
                if (isset($var2)) ModUtil::delVar('Cataleg', 'usuarisCataleg');
                
            case '1.1.1':
                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
            case '1.1.2':
            case '1.1.3':
        }
        return true;
    }

}
