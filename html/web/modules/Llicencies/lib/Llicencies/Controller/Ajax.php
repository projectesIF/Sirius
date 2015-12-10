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

class Llicencies_Controller_Ajax extends Zikula_AbstractController {
    
    /*
     * Obté una llista de treball que compleixen els criteris especificats al
     * formulari de cerca
     */
    public function search($args) {
        if (!SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per accedir a aquesta informació.'));
        }       
/*        if ($this->request->isGet()) $nCriteris="!!!GET !!!";
        if ($this->request->isPost()) $nCriteris="!!!POST !!!";
 */        
        $autoria = $this->request->request->get('autor');
        //$autoria = $this->request->request->get('autor', '');
        $titol = $this->request->request->get('titol', '');
        $tema = $this->request->request->get('tema', '');
        $tema_txt = $this->request->request->get('tema_txt', '');
        $subtema = $this->request->request->get('subtema', '');
        $subtema_txt = $this->request->request->get('subtema_txt', '');
        $tipus = $this->request->request->get('tipus', '');
        $tipus_txt = $this->request->request->get('tipus_txt', '');
        $curs = $this->request->request->get('curs', '');
        $curs_txt = $this->request->request->get('curs_txt', '');
        $admin = $this->request->request->get('admin', false);
        $estat = $this->request->request->get('estat', '');
        
        $nCriteris = strlen($autoria.$titol.$tema_txt.$subtema_txt.$tipus_txt.$curs_txt.$estat);
        //$nCriteris = ($autoria!= '') || ($titol!= '') || ($tema!= '') || ($subtema!= '') || ($tipus!= '') || ($curs!= '') || ($estat!= '');
        //$nCriteris = !is_null($autoria) || !is_null($titol) || !is_null($tema) || !is_null($subtema) || !is_null($tipus) || !is_null($curs) || !is_null($estat);
        // Fer la cerca de llicències segons criteris especificats
        $list = ModUtil::apiFunc('Llicencies', 'user', 'search', 
                           array('autoria'=> $autoria,
                                 'titol'  => $titol,
                                 'tema'   => $tema,
                                 'subtema'=> $subtema,
                                 'tipus'  => $tipus,
                                 'curs'   => $curs,
                                 'estat'  => $estat
                ));

        // ************************************
        $view = Zikula_View::getInstance($this->name);
        $view->assign('list', $list);
        $view->assign('nc', $nCriteris);
        $view->assign('count', count($list));
        $view->assign('where', $where);
        $view->assign('titol', $titol);
        $view->assign('autor', $autoria);
        $view->assign('tema', $tema_txt);
        $view->assign('subtema', $subtema_txt);
        $view->assign('tipus', $tipus_txt);
        $view->assign('curs', $curs);
        $view->assign('admin', $admin);
        $view->assign('estat', $estat);

         // Carreguem la plantilla amb les dades del resultat
        $content = $this->view->fetch('Llicencies_user_list.tpl');

        return new Zikula_Response_Ajax(array('content' => $content));
    }
    
    /*
     * Mostra el contingut detallat del treball relatiu a una llicència
     */
    public function detail($args)
    {
        $codi_treball = $this->request->request->get('codi_treball', '');
        // Get work info
        $detail = ModUtil::apiFunc('Llicencies', 'user', 'detail', $codi_treball);
        $docRoot = ModUtil::getVar($this->name, 'LlicenciesDocRoot');
        $curs_noSlash = str_replace("/", "", $detail['curs']);
        
        $view = Zikula_View::getInstance($this->name);
        $view->assign('docRoot', $docRoot);
        $view->assign('curs_noSlash', $curs_noSlash);
        $view->assign('detail', $detail);
        $view->assign('codi_treball', $codi_treball);
        
         // Carreguem la plantilla amb les dades del resultat
        $content = $this->view->fetch('Llicencies_user_detail.tpl');

        return new Zikula_Response_Ajax(array('content' => $content));
    }
        
    /*
     * Edició de les dades d'una llicència segons codi_treball
     */
    public function edit($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));
        
        $codi_treball = $this->request->request->get('codi_treball', '');
        // Obtenim tota la informació del treball
        $detail = ModUtil::apiFunc('Llicencies', 'user', 'detail', $codi_treball);
        
        $cursos     = ModUtil::apiFunc('Llicencies', 'user', 'getYears');
        $temes      = ModUtil::apiFunc('Llicencies', 'user', 'getTopicList');
        $subtemes   = ModUtil::apiFunc('Llicencies', 'user', 'getSubtopicList');
        $tipus      = ModUtil::apiFunc('Llicencies', 'user', 'getTypeList');
        $modalitats = ModUtil::apiFunc('Llicencies', 'user', 'getModalitats');
        $estats     = ModUtil::apiFunc('Llicencies', 'user', 'getEstats');
        
        $view = Zikula_View::getInstance($this->name);
        $view->assign('detail', $detail);
        $this->view->assign('cursos'    , $cursos);
        $this->view->assign('temes'     , $temes);
        $this->view->assign('subtemes'  , $subtemes);
        $this->view->assign('tipus'     , $tipus);               
        $this->view->assign('modalitats', $modalitats);        
        $this->view->assign('estats'    , $estats);               
        
// carreguem la plantilla per editar les dades
        $content = $this->view->fetch('Llicencies_admin_edit.tpl');

        return new Zikula_Response_Ajax(array('content' => $content));
    }
    
    /*
     * Actualitza la informació d'una llicència d'estudi
     */
    public function update($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));
        $dades['codi_treball'] = $this->request->request->get('codi_treball', '');
        $dades['titol'] = $this->request->request->get('titol', '');        
        $dades['nom'] = $this->request->request->get('nom', '');        
        $dades['cognoms'] = $this->request->request->get('cognoms', '');
        $dades['correuel'] = $this->request->request->get('correuel', '');
        $dades['modalitat'] = $this->request->request->get('modalitat', '');
        $dades['nivell'] = $this->request->request->get('nivell', '');
        $dades['capsa'] = $this->request->request->get('capsa', '');
        $dades['tema'] = $this->request->request->get('tema', '');
        $dades['subtema'] = $this->request->request->get('subtema', '');
        $dades['tipus'] = $this->request->request->get('tipus', '');
        $dades['curs'] = $this->request->request->get('curs', '');
        $dades['estat'] = $this->request->request->get('estat', '');
        $dades['caracteristiques'] = $this->request->request->get('caracteristiques', '');
        $dades['orientacio'] = $this->request->request->get('orientacio', '');
        $dades['resum'] = $this->request->request->get('resum', '');
        $dades['url'] = $this->request->request->get('url', '');
        $dades['web'] = $this->request->request->get('web', '');
        $dades['annexos'] = $this->request->request->get('annexos', '');
        $dades['altres'] = $this->request->request->get('altres', '');
        
        
        If(ModUtil::apiFunc($this->name, 'admin', 'update', $dades)){
            $msg  = __('Les modificacions s\'han guardat correctament.');
            $type = 'status';
        }
        else {
            $msg =__('S\'ha produit un error. Les modificacions no han estat enregistrades.');
            $type = 'error';
        }

        $view = Zikula_View::getInstance($this->name);
        $view->assign('msg', $msg);
        $view->assign('type', $type);
        $content = $this->view->fetch('Llicencies_msg.tpl');

        return new Zikula_Response_Ajax(array('content' => $content));
    }
    
    /*
     * Esborrar una llicència per codi_treball
     */
    public function remove($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));
        
        $codi_treball = $this->request->request->get('codi_treball', '');
        $view = Zikula_View::getInstance($this->name);
        
        if ($codi_treball) {
            $result = ModUtil::apiFunc($this->name, 'admin', 'remove', $codi_treball);
        } 
        else {
            $msg = __('Falta un paràmetre: codi_treball. Es cancel·la l\'acció.');
            $view->assign('msg', $msg);
            $view->assign('type', 'error');
            $content = $this->view->fetch('Llicencies_msg.tpl');
        }        
        return;
        //return new Zikula_Response_Ajax(array('content' => $content, 'row' => 't'.$codi_treball));
    }
    
    /*
     * Establir la ruta base on estan penjats els documents relatius a les memòries i els annexso
     * Actualment és http://www.xtec.cat/sgfp/llicencies/
     */
    public function setDocRoot($args)
    {
        if (!SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Fatal($this->__('No teniu autorització per a modificar aquesta informació.'));
        }
        $docRoot = $this->request->request->get('docRoot', '');

        ModUtil::apiFunc('Llicencies', 'admin', 'setDocRoot', $docRoot);

        $view = Zikula_View::getInstance($this->name);
        $view->assign('root', $docRoot);
        $content = $this->view->fetch('Llicencies_admin_setDocRoot.tpl');
        
        return new Zikula_Response_Ajax(array('content' => $content));
    }
}