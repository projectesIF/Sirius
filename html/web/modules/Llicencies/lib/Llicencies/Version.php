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

class Llicencies_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta=array();
        $meta['version'] 	= '1.0.0';
        $meta['description'] 	= $this->__('Consulta i edició de les llicències d\'estudi retribuïdes.');
        $meta['displayname'] 	= $this->__('Llicències');
        $meta['url'] 		= $this->__('le');
        $meta['securityschema'] = array('Llicencies::' => '::');
	$meta['core_min']       = '1.3.5'; // requires minimum 1.3.5
        $meta['core_max']       = '1.3.99'; 
        return $meta;
    }
}