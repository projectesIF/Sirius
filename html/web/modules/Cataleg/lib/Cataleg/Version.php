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

class Cataleg_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta=array();
        $meta['version'] = '1.1.3';
        $meta['description'] = $this->__('Elaboració i consulta del catàleg unificat de formació permanent del Departament d\'Ensenyament.');
        $meta['displayname'] = $this->__('Catàleg');
        $meta['url'] = $this->__('cataleg');
        $meta['securityschema'] = array('Cataleg::' => '::','CatalegAdmin::' => '::', 'SiriusAdmin::' => '::');
        $meta['capabilities'] = array(HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true));
        return $meta;
    }
    /**
     * Define the hook bundles supported by this module.
     *
     * @return void
     */
    protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_SubscriberBundle(
            $this->name, 'subscriber.Cataleg.ui_hooks.Cataleg',
            'ui_hooks',
            $this->__('Cataleg Hooks')
        );
        
        $bundle->addEvent('form_edit', 'Cataleg.ui_hooks.Cataleg.form_edit');

        $this->registerHookSubscriberBundle($bundle);
    }
}