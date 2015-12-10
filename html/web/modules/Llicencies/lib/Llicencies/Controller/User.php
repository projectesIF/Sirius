<?php

/**
 * @authors Josep Ferràndiz Farré <jferran6@xtec.cat>
 * @authors Joan Guillén Pelegay  <jguille2@xtec.cat>
 *
 * @par Llicència:
 * GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Cataleg
 * @version 1.0
 * @copyright Departament d'Ensenyament. Generalitat de Catalunya 2013-2014
 */
class Llicencies_Controller_User extends Zikula_AbstractController {
  
    public function main() {
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));

        $this->redirect(ModUtil::url('Llicencies', 'user', 'search'));
    }
    
    /*
     * Carrega el formulari per a la cerca de treballs de llicències
     */
    public function search(){
        // Check permission
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        //path to zk jquery lib
        $js      = new JCSSUtil;
        $scripts = $js->scriptsMap();
        $jquery  = $scripts['jquery']['path'];
        
        // Omplim les llistes desplegables del fromulari
        $cursos   = ModUtil::apiFunc('Llicencies', 'user', 'getYears');
        $temes    = ModUtil::apiFunc('Llicencies', 'user', 'getTopicList');
        $subtemes = ModUtil::apiFunc('Llicencies', 'user', 'getSubtopicList');
        $tipus    = ModUtil::apiFunc('Llicencies', 'user', 'getTypeList');
        
        $view = Zikula_View::getInstance($this->name);
        $view->assign('jquery'  , $jquery);
        $view->assign('cursos'  , $cursos);
        $view->assign('temes'   , $temes);
        $view->assign('subtemes', $subtemes);
        $view->assign('tipus'   , $tipus);        
        $view->assign('admin'   , false);
        // Carreagr el formulari per a fer la cerca de llicències d'estudi

        return $this->view->display('Llicencies_main.tpl');
    }
}