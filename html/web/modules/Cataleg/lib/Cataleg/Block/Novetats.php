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

class Cataleg_Block_Novetats extends Zikula_Controller_AbstractBlock {

    protected function postInitialize() {
        // Set caching to false by default.
        $this->view->setCaching(false);
    }

    /**
     * initialise block
     *
     */
    public function init() {
        //Security check
        //SecurityUtil::registerPermissionSchema('Cataleg:novetatsblock:', 'Block title::');
        
    }

    /**
     * get information on block
     *
     * @return       array       Informació del bloc
     */
    public function info() {
        //Values
        return array(
            'text_type' => 'Novetats',
            'module' => 'Cataleg',
            'text_type_long' => $this->__('Mostra les novetats o modificacions del catàleg unificat de formació'),
            'allow_multiple' => false,
            'form_content' => true,
            'form_refresh' => true,
            'show_preview' => true);
    }

    /**
     * Gets user news
     *
     * @return	el bloc de novetats
     */
    public function display($blockinfo) {
        // Security check
        if (!SecurityUtil::checkPermission('Cataleg::', '::', ACCESS_READ))
            return;

        // Check if the module is available
        if (!ModUtil::available('Cataleg'))
            return;
        
        $renderedOutput = '';
        // Get the view object
        $view = Zikula_View::getInstance('Cataleg', false);

        $novetats = ModUtil::apiFunc($this->name, 'user', 'getNovetats');
        if ($novetats['novetats'] || $novetats['canvis']) {
            $view->assign('novetats', $novetats);

            $s = $view->fetch('block/Cataleg_block_Novetats.tpl');

            $blockinfo['content'] = $s;
            $renderedOutput = BlockUtil::themesideblock($blockinfo);
        }
        return $renderedOutput;
    }    

    public function update($blockinfo) {               
        $dies = (int) FormUtil::getPassedValue('dies', 100, 'POST');
        $dataOk = FormUtil::getPassedValue('dataOk', false, 'POST');
        $dataPublicacio = FormUtil::getPassedValue('dp', null, 'POST');
        $showNew = FormUtil::getPassedValue('showNew', false, 'POST');
        $showMod = FormUtil::getPassedValue('showMod', false, 'POST');
        
        //$blockinfo['url'] = $dies;
        $novetats  = ModUtil::getVar($this->name, 'novetats');
        $novetats['diesNovetats'] = $dies;
        $novetats['showNew'] = $showNew? true: false;
        $novetats['showMod'] = $showMod? true: false;
        
        if ($dataOk)  $novetats['dataPublicacio'] = $dataPublicacio;
        ModUtil::setVar($this->name, 'novetats', $novetats);
        return $blockinfo;
    }
    
    /** 
     * Obtenció dels paràmetres del bloc "Novetats del catàleg" i visualització a 
     * la plantilla per a la seva modificació
     * 
     * @param type $blockinfo
     * @return type
     */
    
    public function modify($blockinfo) {
        
        $novetats  = ModUtil::getVar($this->name, 'novetats');
        $dies = isset($novetats['diesNovetats'])? $novetats['diesNovetats'] : 0;
        $dp = isset($novetats['dataPublicacio'])? $novetats['dataPublicacio'] : "";
        $showNew = isset($novetats['showNew'])? $novetats['showNew'] : FALSE;
        $showMod = isset($novetats['showMod'])? $novetats['showMod'] : FALSE;
        // Get the view object
        $view = Zikula_View::getInstance('Cataleg', false);

        $view->assign('dies', $dies);
        $view->assign('dp',   $dp);
        $view->assign('showNew', $showNew);
        $view->assign('showMod', $showMod);
        
        return $view->fetch('block/Cataleg_block_config.tpl');
    }  

}
