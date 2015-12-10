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
class Llicencies_Api_Admin extends Zikula_AbstractApi {
    /*
     * Menú d'administració
     */
    public function getlinks($args) {
        if (SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url('Llicencies', 'admin', 'main', array()), 'text' => $this->__('Treballs'), 'class' => 'z-icon-es-view');
            $links[] = array('url' => ModUtil::url('Llicencies', 'admin', 'getConfig', array()), 'text' => $this->__('Configuració'), 'class' => 'z-icon-es-config');
            $links[] = array('url' => ModUtil::url('Llicencies', 'admin', 'ieTables', array()), 'text' => $this->__('Importa/Exporta'), 'class' => 'z-icon-es-import');
        }
        return $links;
    }
    
    /*
     * Estableix la URL base on es troben els documents de les llicències
     * Actualment http://www.xtec.cat/sgfp/llicencies/
     */
    public function setDocRoot($value)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));
        if (ModUtil::setVar($this->name, 'LlicenciesDocRoot', $value)){
               LogUtil::registerStatus(__('El paràmetre s\'ha actualitzat correctament.'));               
        }
        else
            LogUtil::registerError(__('El paràmetre no s\'ha pogut actualitzar.'));
        return true;
    }
        
    /*
     * Esborrar un registre de la taula llicencies segons codi_treball
     */
    public function remove($id)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));

        return DBUtil::deleteObjectByID('llicencies', $id, 'codi_treball');
    }

    /*
     * Actualitza les dades relatives al treball d'una llicència
     */
    public function update($record)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Llicencies::', '::', ACCESS_ADMIN));
        return DBUtil::updateObject($record, 'llicencies', '', 'codi_treball');
    }
    
    public function getTables()
    {
        return DBUtil::getTables();
    }
    
    /*
     * Verifica que l'arxiu CSV i la taula selecciona per a importar són compatibles:
     * Al csv no hi ha columnes repetides i cadascuna té correspondència amb una columna 
     * de la taula
     */
    public function checkCSV($args)
    {
        $result = array();
        $result['correcte'] = false;
        $t = $args['dbTable'];
        $h = $args['csvHeader'];
        
        // Camps de la taula $t
        $tColumns = ModUtil::apiFunc($this->name, 'admin', 'getTableFields', $t);

        if (count($h) == count(array_unique($h))){
            // No hi ha camps duplicats a la capçalera del csv
            // Comprovar que els camps del csv són també camps de la taula
            $dif = array_diff($h, $tColumns);
            if (empty($dif)){
                // Tots els camps del csv ho són de la taula
                $idx = ModUtil::apiFunc($this->name, 'admin' ,'getPrimaryKey', $t);
                if (in_array($idx, $h)) {
                    // El csv conté el camp clau de la taula
                    $result['correcte'] = true;
                    $result['msg'] = __('L\'arxiu csv és correcte.');
                    $result['clau'] = $idx;
                } else {
                    // No s'inclou la clau primària'                    
                    $result['msg'] = __('Els camps del csv no contenen la clau primària ['.$idx.'] de la taula '. $t);
                }
            } else{
                // Error: hi ha camps del csv que no ho són de la taula
                $txtDif = implode(',',$dif);
                $result['msg'] = __('Els camps del csv: '.$txtDif .' no es troben la taula '. $t);
            }
        } else {
            //Error: hi ha camps del csv repetits
            $result['msg'] = __('Hi ha camps del csv repetits.');
        }
        return $result;        
    }
    
    /*
     * Retorna la clau primària simple d'una taula
     */
    public function getPrimaryKey($tableName)
    {
        $sql = "show index from " .$tableName." where Key_name = 'PRIMARY'";
        $def =DBUtil::marshallObjects(DBUtil::executeSQL($sql));
        return $def[0]['Column_name'];         
    }

    /*
     * Retorna els camps d'una taula
     */
    public function getTableFields($tableName)
    {
        $tables = DBUtil::getTables();        
        return $tables[$tableName."_column"];
    }
          
}
