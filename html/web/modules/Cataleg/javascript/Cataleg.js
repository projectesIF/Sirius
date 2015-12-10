
/**
 * @author Josep Ferràndiz Farré <jferran6@xtec.cat>
 * @author Jordi Fons Vilardell  <jfons@xtec.cat>
 * @author Joan Guillén Pelegay  <jguille2@xtec.cat>
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Cataleg
 * @version 1.0
 * @copyright Departameny d'Ensenyament. Generalitat de Catalunya 2012-2013
 */

/*
 *  Actualitza eix i llista de subprioritats cada vegada que es selecciona 
 *  una prioritat
 */

function getEixSubpri(obj){
    var cerca = document.getElementById('cerca').value;
    getEix(obj, cerca); 
    getSubpri(obj, cerca);    
}
 
//Gestionar el canvi de prioritat per seleccionar l'eix corresponent
function getEix(obj, cerca){

    $('eix').update('<img src="images/ajax/indicator.white.gif" />');
    var b={
        priId:obj.value,
        cerca : cerca
    };
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=getEix",{
        parameters: b,
        onComplete: getEix_response,
        onFailure: getEix_failure
    });
}

function getEix_response(req){    
    if(!req.isSuccess()){
        Zikula.showajaxerror(req.getMessage());
        return
    }

    var b=req.getData();
    $('eix').update(b.content);
    
}

function getEix_failure(req){

}

//Gestionar el canvi de prioritat per seleccionar les subprioritats corresponents
function getSubpri(obj, cerca){
    var b={
        priId:obj.value,
        cerca:cerca
    };   
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=getSubPrioritats",{
        parameters: b,
        onComplete: getSubpri_response,
        onFailure: getSubpri_failure
    });
}

function getSubpri_response(req){    
    if(!req.isSuccess()){
        Zikula.showajaxerror(req.getMessage());
        return
    }
    
    var b=req.getData();
    $('selectorSubPri').update(b.content);
   
}

function getSubpri_failure(){

}

/*
 * Actualitza la llista d'unitats relacionades amb el catàleg origen
 * de la importació
 * Es crida des de la plantilla Cataleg_user_import.tpl
 */

function catalegChange(id){
    //updateImportUnits
    $('ulist').update('<img src="images/ajax/indicator.white.gif" style="margin-left: auto; margin-right: auto" />');
    //var cdest = ;

    var a = {
        catOrig: id,
        catDest: document.getElementById('catDest').value
    };
//    alert('CataleOri: '+a.catOrig+' CatDest: '+a.catDest);
    var c= new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=updateImportUnits",{
            parameters: a,
            onComplete: catalegChange_response,
            onFailure: failure
    });
}

function catalegChange_response(req){
    if(!req.isSuccess()){
        Zikula.showajaxerror(req.getMessage());
        return
    }
    var b=req.getData();
    //alert(b.content);
    $('ulist').update(b.content);
    $('alist').update('');
}


/*
 * uDestChange
 * Actualitza la llista d'activitats relacionades amb el catàleg origen
 * de la importació i la unitat seleccionada
 * Es crida des de la plantilla Cataleg_user_importUnits.tpl
 */

function uDestChange(id){
    //updateImportUnits
    $('alist').update('<img src="images/ajax/indicator.white.gif" style="margin-left: auto; margin-right: auto" />');
    var a = {
        uniId: id,
        catOrig: document.getElementById('catalegs').value, //options[document.getElementById('catalegs').selectecIndex].value,
        catDest: document.getElementById('catDest').value
    };
    //alert('CataleOri: '+a.catOrig+' CatDest: '+a.catDest);
    var c= new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=updateActsList",{
            parameters: a,
            onComplete: uDestChange_response,
            onFailure: failure
    });
}

function uDestChange_response(req){
    if(!req.isSuccess()){
        Zikula.showajaxerror(req.getMessage());
        return
    }
    var b=req.getData();
    //alert(b.content);
    $('alist').update(b.content);
}

/*
 * Verificació de l'existència dels codis de centre introduits i visualització
 * del resultat 
 */
function updateInfoCentres(){
    var str=document.getElementById('lloc').value;
    var n=str.replace(" ",""); 
    if (n != "") {
        $('infoCentres').update('<img src="images/ajax/indicator.white.gif" style="margin-left: auto; margin-right: auto" />');
        jQuery('#lloc').hide();
    
        var a={
            centres:document.getElementById('lloc').value
        };
        //alert(a.centres);
        var c= new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=checkCentres",{
            parameters: a,
            onComplete: updateInfoCentres_response,
            onFailure: failure
        });
    }
}

function updateInfoCentres_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    jQuery('#lloc').show();
    var b=a.getData();
    //document.getElementById('lloc').value = b.codis_ori;
    $('lloc').value = b.codis_ori;
    $('lcentres').value = b.codis;
    $('infoCentres').update(b.content);
    //document.getElementById('lcentres').value = b.codis;
     
}
function failure(){

}

// Actualització des de l'administració del mòdul, dels paràmetres del bloc de novetats i
// la data de publicació del catàleg.
function updateConf(){
    jQuery('#frmConf').slideUp();
    var a = {       
        dp: document.getElementById('datepicker').value,
        showNew: document.getElementById('showNew').checked?1:0,
        showMod: document.getElementById('showMod').checked?1:0,
        dies: document.getElementById('dies').value,
        dataOk: document.getElementById('dataOk').value
        
    };
    var c= new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=updateConfig",{
            parameters: a,
            onComplete: updateConf_response,
            onFailure: failure
    });    
}

function updateConf_response(req){
    if(!req.isSuccess()){
        Zikula.showajaxerror(req.getMessage());
        return
    }
    var b=req.getData();     
    jQuery('#frmConf').html(b.content);
    jQuery('#frmConf').slideDown();
    jQuery("#msg").show().delay(5000).fadeOut('slow');
   
}

function emmascara(element){
    // Carreguem una imatge per superposar a la informació que es mostra i
    // i evitar que es puguin modificar els valors mentre es carreguen els valors 
    // per defecte    

    var ample= document.getElementById(element).offsetWidth;// amplada de la taula
    var alt  = document.getElementById(element).offsetHeight;
    var img  = document.createElement("img");     // Creem element imatge

    //img.src="images/ajax/indicator.white.gif"; // Associem arxiu imatge
    img.src="modules/Cataleg/images/over.png";

    img.style.position = 'absolute';           // Posicionem respecte el div 
    img.style.zIndex = '10';
    img.style.top = 0;
    img.style.left= 0;
    img.style.width = ample+"px";
    img.style.height = alt+"px";
    img.style.opacity = "0.1";     
    document.getElementById(element).appendChild(img);

    var img2 = document.createElement("img");
    img2.src="modules/Cataleg/images/indicator.gif";
    img2.style.position = 'absolute';           // Posicionem respecte el div 
    img2.style.zIndex = '20';
    img2.style.top = ((alt/2) - (img2.height/2)) +'px';
    img2.style.left= ((ample/2) - (img2.width/2)) +'px';
    img2.style.opacity = "0.8";  
    document.getElementById(element).appendChild(img2);
    
    // Fi emmascarament dades per pantalla
}
// Fitxa Activitats aplicar els valors per defecte als elements a gestionar en 
// funció del servei triat (ST, SE o Unitat)
function changeOps(op){
    
    emmascara("elemGestio");

    var b={
        def:op
    };
    
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=refreshDefauls",{
        parameters: b,
        onComplete: changeOps_response,
        onFailure: changeOps_failure
    });
}

function changeOps_response(req){
    if(!req.isSuccess()){
        Zikula.showajaxerror(req.getMessage());
        return
    }
    
    var b=req.getData();
    $('elemGestio').update(b.content);
}

function changeOps_failure(){
   
}

// Mostrar el formulari exportació cataleg complet a PDF
function frmDocument(catId){
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=frmDocument&catId="+catId,{
        onComplete: frmDocument_response,
        onFailure: failure
    });    
}

function frmDocument_response(r){
    if(!r.isSuccess()){
        Zikula.showajaxerror(r.getMessage());
        return
    }
    var b=r.getData();
    $('documentPDF').update(b.content);
}


// Evita la introducció de caràcters no numèrics
function nomesDigits(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
    }
}

// Desplega una àrea de text
// element
// tipus
function showtxtarea(element){
    switch (element) {    
        case 'obj':
            var nboto = '1';
            var tipus = "#obj";
            var div   = "#d_obj";
            var max   = 5;
            break;
        case 'con':
            var nboto = '2';
            var tipus = "#con";
            var div   = "#d_con";
            var max   = 10;
            break;
    }
    var e = jQuery(tipus).find("textarea:visible").length;
    var idx = e + 1;
    jQuery(div+idx).show("slow");
    if (idx == max) {
        jQuery('#plus'+nboto).hide();
    }
    // Mostrem el botó d'ocultar elements'
    jQuery('#minus'+nboto).show();
}

// Amaga una àrea de text
function hidetxtarea(element){
    switch (element) {    
        case 'obj':
            var nboto = '1';
            var tipus = "#obj";
            var div   = "#d_obj";
            break;
        case 'con':
            var nboto = '2';
            var tipus = "#con";
            var div   = "#d_con";
            break;
    }
   
    // Si no hi ha text ocultar l'àrea de text
    var e = jQuery(tipus).find("textarea:visible").length;
    if ($(element + e).value <=" "){
        jQuery('#plus'+nboto).show();
        jQuery(div +e).hide("slow");
        if (e == 3) {
            jQuery('#minus'+nboto).hide();
        }
    }    
}

// ****************************************************************************
var theTable, theTableBody

function appendRow(form) {
    //init()
    theTable = document.getElementById('contactes');
    theTableBody = theTable.tBodies[0];
    insertTableRow(form, -1)
    jQuery('#menysp').show('slow');
    jQuery('#nPersContacte').val(theTableBody.rows.length)         
}

function insertTableRow(form, where) {
    var newCell
    var newRow = theTableBody.insertRow(where)
    var nr  = theTableBody.rows.length
    newCell = newRow.insertCell(0)
    newCell.innerHTML = "<input style='width:98%' maxlenght = '150' type='text' name='contacte["+nr+"][pContacte]' id='pcontatcte"+nr+"'></input>"
    newCell = newRow.insertCell(1)
    newCell.innerHTML = "<input size='35' maxlenght = '50' type='text' name='contacte["+nr+"][email]' id='email"+nr+"'></input>"
    newCell = newRow.insertCell(2)
    newCell.innerHTML = "<input size='15' maxlenght = '20' type='text' name='contacte["+nr+"][telefon]' id='telf"+nr+"'></input>"    
}

function removeRow(form) {
    theTable = document.getElementById('contactes');
    theTableBody = theTable.tBodies[0];
    theTableBody.deleteRow(theTableBody.rows.length-1)      
    jQuery('#nPersContacte').val(theTableBody.rows.length)
    if (theTableBody.rows.length == 1) {
            jQuery('#menysp').hide('slow');
        }
}

function toogleCentres(){
    
    //alert(jQuery("#tabast").find("option:selected").text());
    if (jQuery("#tabast").find("option:selected").text() == 'Professorat d\'un centre') {
        jQuery('#centres').show('slow');
        jQuery('#hihacentres').val(1);
    }
    else {
        // esborrar el contingut del camps centres?
        //jQuery('#lloc').val("");
        jQuery('#hihacentres').val(0);
        jQuery('#centres').hide('slow');
    }
    
}

function checkContent(obj){
    //alert(obj.value);
    var e = obj.getAttribute('id').substr(0,1);
   if (obj.value <= " "){
       if (e == 'o') jQuery('#minus1').show();
       else jQuery('#minus2').show();
   }
}

function getCerca(form) {
    
    $('resultats').update('<img src="images/ajax/indicator.white.gif" /> Cercant');
     
    var elems = form.elements;   
    var subpri = 0;
    var pri = 0;
    var d = new Array();
    var compta = 0;
    var desti = 0;
     
    for (var i=0;i<elems.length;i++)
    {
        var mostra = mostra + elems[i].name + ": " + elems[i].value + " - "+ elems[i].checked + " \n"; 
        if  (elems[i].name == "prioritat" && elems[i].checked){
            pri = elems[i].value;
        }
        if  (elems[i].name == "subprioritat" && elems[i].checked){
            subpri = elems[i].value;
        }
        if  (elems[i].name == "destinatari" && elems[i].checked){
            d[compta] = elems[i].value;
            compta = compta + 1;
        }       
    }

    if (d.length > 0){ 
        desti = serialize(d);
    }else{
        desti = 0;
    }

    var b={    
        prioritat:pri,
        subprioritat:subpri,
        unitat:elems.unitat.value,  
        titol:elems.titol.value,
        modcurs:elems.modcurs.value,
        presencial:elems.presencial.value,       
        lloc:elems.lloc.value,
        desti:desti,
        catId:elems.catId.value
    };
    
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=cataleg&func=getCerca",{
        parameters: b,
        onComplete: getCerca_response,
        onFailure: getCerca_failure
    });
    
}

function getCerca_response(req){    
    if(!req.isSuccess()){
        Zikula.showajaxerror(req.getMessage());
        return
    }

    var b=req.getData();
    
  
    $('resultats').update(b.content);  
    jQuery('#resultats').hide(0);
    // jQuery('#resultats').show('slow');
    jQuery('#resultats').fadeIn('slow');
 
}

function getCerca_failure(){

}


function serialize (mixed_value) {
    // http://kevin.vanzonneveld.net
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
    // +   bugfixed by: Jamie Beck (http://www.terabit.ca/)
    // +      input by: Martin (http://www.erlenwiese.de/)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   improved by: Le Torbi (http://www.letorbi.de/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   bugfixed by: Ben (http://benblume.co.uk/)
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
    var val, key, okey,
    ktype = '', vals = '', count = 0,
    _utf8Size = function (str) {
        var size = 0,
        i = 0,
        l = str.length,
        code = '';
        for (i = 0; i < l; i++) {
            code = str.charCodeAt(i);
            if (code < 0x0080) {
                size += 1;
            }
            else if (code < 0x0800) {
                size += 2;
            }
            else {
                size += 3;
            }
        }
        return size;
    },
    _getType = function (inp) {
        var match, key, cons, types, type = typeof inp;

        if (type === 'object' && !inp) {
            return 'null';
        }
        if (type === 'object') {
            if (!inp.constructor) {
                return 'object';
            }
            cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            types = ['boolean', 'number', 'string', 'array'];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    },
    type = _getType(mixed_value)
    ;

    switch (type) {
        case 'function':
            val = '';
            break;
        case 'boolean':
            val = 'b:' + (mixed_value ? '1' : '0');
            break;
        case 'number':
            val = (Math.round(mixed_value) == mixed_value ? 'i' : 'd') + ':' + mixed_value;
            break;
        case 'string':
            val = 's:' + _utf8Size(mixed_value) + ':"' + mixed_value + '"';
            break;
        case 'array': case 'object':
            val = 'a';
            /*
        if (type == 'object') {
          var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
          if (objname == undefined) {
            return;
          }
          objname[1] = this.serialize(objname[1]);
          val = 'O' + objname[1].substring(1, objname[1].length - 1);
        }
        */

            for (key in mixed_value) {
                if (mixed_value.hasOwnProperty(key)) {
                    ktype = _getType(mixed_value[key]);
                    if (ktype === 'function') {
                        continue;
                    }

                    okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                    vals += this.serialize(okey) + this.serialize(mixed_value[key]);
                    count++;
                }
            }
            val += ':' + count + ':{' + vals + '}';
            break;
        case 'undefined':
        // Fall-through
        default:
            // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
            val = 'N';
            break;
    }
    if (type !== 'object' && type !== 'array') {
        val += ';';
    }
    return val;
}
