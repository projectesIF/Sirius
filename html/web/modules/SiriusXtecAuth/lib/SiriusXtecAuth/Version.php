<?php

/**
 * Zikula Application Framework
 *
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Joan Guillén i Pelegay (jguille2@xtec.cat)
 * 
 * @category   Sirius Modules
 */

/**
 * Provides metadata for this module to the Extensions module.
 */

class SiriusXtecAuth_Version extends Zikula_AbstractVersion
{
    /**
     * Assemble and return module metadata.
     *
     * @return array Module metadata.
     */
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname'] = $this->__('SiriusXtecAuth');
        $meta['description'] = $this->__('Gestiona la validació a Sirius des de XTEC-ldap');
        $meta['url'] = $this->__('SiriusXtecAuth');
        $meta['version'] = '1.0.2';
        $meta['securityschema'] = array('SiriusXtecAuth::' => '::');

        return $meta;
    }
}

