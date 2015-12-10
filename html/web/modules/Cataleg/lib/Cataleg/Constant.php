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

/**
* Cataleg constants globals del mòdul.
*/
class Cataleg_Constant
{
    
    // El nom del mòdul.
    const MODNAME = 'Cataleg';
    
    
    // Estats del catàleg
    const TANCAT       = 0;    
    const LES_MEVES    = 1;    
    const ORIENTACIONS = 2;
    const ACTIVITATS   = 3;
    const OBERT        = 4;
    
    // Estats de fitxa d'activitats
    const ESBORRANY   = 0;
    const ENVIADA     = 1;
    const PER_REVISAR = 2;
    const VALIDADA    = 3;
    const MODIFICADA  = 4;
    const ANULLADA    = 5;
    
    // Files per defecte de llistats paginats
    const ITEMSPERPAGE = 12;

}

