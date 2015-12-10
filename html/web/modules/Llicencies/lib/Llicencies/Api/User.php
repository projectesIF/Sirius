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
class Llicencies_Api_User extends Zikula_AbstractApi {

    public function getYears() {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        return DBUtil::selectFieldArray('llicencies_curs', 'curs', '', 'curs', false, 'curs');
    }

    public function getTopicList() {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        return DBUtil::selectFieldArray('llicencies_tema', 'nom', '', 'nom', false, 'codi_tema');
    }

    public function getSubtopicList() {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        return DBUtil::selectFieldArray('llicencies_subtema', 'nom', '', 'nom', false, 'codi_subt');
    }

    public function getTypeList() {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        return DBUtil::selectFieldArray('llicencies_tipus', 'nom', '', 'nom', false, 'codi_tipus');
    }
    
    public function getEstats() {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        return DBUtil::selectFieldArray('llicencies_estats', 'descripcio', '', 'id_estat', false, 'id_estat');
    }
       
    public function getModalitats() {
        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        $rs = DBUtil::selectFieldArray('llicencies_modalitat', 'descripcio', '', 'id_mod', false, 'id_mod');
        foreach ($rs as $key => &$value) {
            $value = $key."-".$value;
        }
        return $rs;
    }
    
    /*
     * Busca els registres que compleixen les condicions especificades al formulari
     * de criteris de cerca i retorna el conjunt de registres que satisfan les condicions
     * @param array $args valors per establir el filtre de cerca
     * @retun array $rs Conjunt de registres que satisfan les condicions
     */
    public function search($args) {

        //Verificar permisos
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        $orderby = "cognoms, nom, curs ";
        $where = null;
        if ($args['autoria'] != "") {
            $aut = str_replace(" ", "%", $args['autoria']);
            $where = "( concat(nom,concat(' ',cognoms)) like '%" . $aut . "%' OR  concat(cognoms,concat(' ',nom)) like '%" . $aut . "%') ";
        }

        if ($args['titol'] != "") {
            if (!is_null($where))
                $where .= " AND ";
            $where .= "titol like '%" . $args['titol'] . "%' ";
        }

        if ($args['tema']) {
            if (!is_null($where))
                $where .= " AND ";
            $where .= "tema =" . $args['tema'] . " ";
        }

        if ($args['subtema']) {
            if (!is_null($where))
                $where .= " AND ";
            $where .= "subtema =" . $args['subtema'] . " ";
        }

        if ($args['tipus']) {
            if (!is_null($where))
                $where .= " AND ";
            $where .= "tipus =" . $args['tipus'] . " ";
        }

        if ($args['curs'] != "") {
            if (!is_null($where))
                $where .= " AND ";
            $where .= "curs ='" . $args['curs'] . "'";
        }
        
        if ($args['estat'] != "") {
            if (!is_null($where))
                $where .= " AND ";
            $where .= "estat ='" . $args['estat'] . "'";
        }
        // Obtenim els registres que compleixen els criteris de cerca
        $rs = DBUtil::selectObjectArray('llicencies', $where, $orderby);

        // Generació de l'enllaç a la informació de la llicència
        foreach ($rs as &$record) {
            switch ($record['estat']) {
                case  0:
                    if ($record['curs'] > "2002/03")
                        //$record['link'] = 'index.php?module=le&func=detail&c=' . $record['codi_treball'];
                        $record['link'] = 'c';
                    else {
                        if ($record['url'] != "") {
                            $record['link'] = 'u'; 
                            $record['url']  = trim($record['url'], '#');
                        }
                        else 
                            $record['link'] ='#';
                    }
                    break;
            }
        }
        return $rs;
    }
    
    /*
     * Obté tota la informació relativa a un treball específic
     * @param code (codi_treball) Identifica el treball
     */
    public function detail($code)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_READ));
        $info = array();
        if ($code) {
            $where = 'codi_treball='.$code;
            $info = DBUtil::selectObject('llicencies', $where);            
        }
        return $info;
    }
}
