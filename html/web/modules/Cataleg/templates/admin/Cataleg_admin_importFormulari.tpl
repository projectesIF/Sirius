{adminheader}
<style>
    #catDest, #triaElements {
        display:none;
    }
    .optionhide {
        display:none;
    }
</style>
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Importació de catàlegs"}</h3>
</div>
<form name="importFormulari" id="importFormulari" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend>{gt text="Objectes d'importació"}</legend>
        <div class="z-formrow">
            <label for="catIdOri">{gt text="Catàleg d'origen"}</label>
            <select id="catIdOri" name="catIdOri" onchange="triaCat();">
                <option value=''>Tria un catàleg...</option>
                {foreach from=$cats item='cat' key='key'}
                    <option value={$cat.catId}>{$cat.nom}</option>
                {/foreach}
            </select>
        </div>
            
            {foreach from=$cats key='key' item='cat'}
            <div id="catori-{$key}" style="display:none">
                <p class="z-informationmsg">{gt text="El catàleg d'origen ('"}{$cat.nom}{gt text="') té "}{$cat.uni}{gt text=" unitat/s, "}{$cat.eix}{gt text=" eix/os, "}{$cat.pri}{gt text=" prioritat/s i "}{$cat.spr}{gt text=" subprioritat/s."}</p>
            </div>
            {/foreach}
            
        <div class="z-formrow" id="catDest">
            <label for="catIdDest">{gt text="Catàleg destí"}</label>
            <select id="catIdDest" name="catIdDest" onchange="triaCat2();">
                <option value=''>Tria un catàleg...</option>
                {foreach from=$cats item='cat' key='key'}
                    <option id="opt-{$cat.catId}" value={$cat.catId}>{$cat.nom}</option>
                {/foreach}
            </select>
        </div>
            {foreach from=$cats key='key' item='cat'}
            <div id="catdest-{$key}" style="display:none">
                <p class="z-informationmsg">{gt text="El catàleg destí ('"}{$cat.nom}{gt text="') ja té "}{$cat.uni}{gt text=" unitat/s, "}{$cat.eix}{gt text=" eix/os, "}{$cat.pri}{gt text=" prioritat/s i "}{$cat.spr}{gt text=" subprioritat/s."}</p>
            </div>
            {/foreach}
    </fieldset>
    <fieldset>
        <legend>{gt text="Opcions d'importació"}</legend>
        <div class="z-formrow">
            <label for="iTotTot">{gt text="Importa tots els elements"}</label>
            <input id="iTotTot" name="iTot" type="radio" value="1" style="margin:8px;" checked="checked" onchange="triaElements();">
        </div>
        <div class="z-formrow">
            <label for="iTotSome">{gt text="Tria els elements a importar"}</label>
            <input id="iTotSome" name="iTot" type="radio" value="0" style="margin:8px;" onchange="triaElements();">
        </div>
        <div id="triaElements">
            <fieldset>
                <fieldset>
                    <div class="z-formrow">
                        <label for="iEixos" style="margin-left:50px;">{gt text="Eixos"}</label>
                        <input id="iEixos" name="iEixos" type="checkbox" value="true" style="margin:8px;" onchange="triaEixos();">
                    </div>
                    <div class="z-formrow">
                        <label for="iPrioritats" style="margin-left:100px;">{gt text="Prioritats"}</label>
                        <input id="iPrioritats" name="iPrioritats" type="checkbox" value="true" style="margin:8px;" onchange="triaPrioritats();">
                    </div>
                    <div class="z-formrow">
                        <label for="iSubprioritats" style="margin-left:150px;">{gt text="Subprioritats"}</label>
                        <input id="iSubprioritats" name="iSubprioritats" type="checkbox" value="true" style="margin:8px;" onchange="triaSubprioritats();">
                    </div>
                </fieldset>
                <fieldset>
                    <div class="z-formrow">
                        <label for="iUnitats" style="margin-left:50px;">{gt text="Unitats"}</label>
                        <input id="iUnitats" name="iUnitats" type="checkbox" value="true" style="margin:8px;" onchange="triaUnitats();">
                    </div>
                    <div class="z-formrow">
                        <label for="iResponsables" style="margin-left:100px;">{gt text="Responsables"}</label>
                        <input id="iResponsables" name="iResponsables" type="checkbox" value="true" style="margin:8px;" onchange="triaResponsable();">
                    </div>
                </fieldset>
                <fieldset>
                    <div class="z-formrow">
                        <label for="iImpunits" style="margin-left:100px;">{gt text="Persones de contacte"}</label>
                        <input id="iImpunits" name="iImpunits" type="checkbox" value="true" style="margin:8px;" onchange="triaImpunits();">
                    </div>
                </fieldset>
            </fieldset>
        </div>
    </fieldset>
</form>
<div id="botons" class="z-buttonrow z-buttons z-center">
    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:importa();" title="{gt text="Importa"}">{gt text="Importa"}</button>
    <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
</div>
<script src="javascript/jquery/jquery-1.7.0.js"></script>
<script type="text/javascript"> var $jq=jQuery.noConflict(true);</script>
<script>
    function triaCat(){
    var x = document.importFormulari.catIdOri.selectedIndex;
    var a = document.importFormulari.catIdOri.options[x].value;
    if (a == '') {
    document.importFormulari.catIdDest.selectedIndex = 0;
    $jq("#catDest").slideUp();
    $jq("[id^=catori]").slideUp();
    $jq("[id^=catdest]").slideUp();
   
    
} else {

$jq("[id^=catori]").slideUp();
$jq("#catori-"+a).slideDown();
$jq("option").removeClass('optionhide');

$jq("#catDest").slideDown();
var y = document.importFormulari.catIdDest.selectedIndex;
if (x == y) {
document.importFormulari.catIdDest.selectedIndex = 0;
}
$jq("option#opt-"+a).addClass('optionhide');
}
}
function triaCat2(){
    var x = document.importFormulari.catIdDest.selectedIndex;
    var a = document.importFormulari.catIdDest.options[x].value;
    if (a == '') {
        $jq("[id^=catdest]").slideUp();
    } else {
        $jq("[id^=catdest]").slideUp();
        $jq("#catdest-"+a).slideDown();
    }
}
function triaElements(){
if (document.importFormulari.iTot[0].checked== true){
$jq("#triaElements").slideUp();
} else if (document.importFormulari.iTot[1].checked == true){
$jq("#triaElements").slideDown();
}
}
function triaEixos(){
if (document.importFormulari.iEixos.checked == false){
document.importFormulari.iPrioritats.checked = false;
document.importFormulari.iSubprioritats.checked = false;
document.importFormulari.iImpunits.checked = false;
}
}
function triaPrioritats(){
if (document.importFormulari.iPrioritats.checked == false){
document.importFormulari.iSubprioritats.checked = false;
document.importFormulari.iImpunits.checked = false;
} else if (document.importFormulari.iPrioritats.checked == true){
document.importFormulari.iEixos.checked = true;
}
}
function triaSubprioritats(){
if (document.importFormulari.iSubprioritats.checked == true){
document.importFormulari.iEixos.checked = true;
document.importFormulari.iPrioritats.checked = true;
}
}
function triaUnitats(){
if (document.importFormulari.iUnitats.checked == false){
document.importFormulari.iResponsables.checked = false;
document.importFormulari.iImpunits.checked = false;
}
}
function triaResponsable(){
if (document.importFormulari.iResponsables.checked == true){
document.importFormulari.iUnitats.checked = true;
}
}
function triaImpunits(){
if (document.importFormulari.iImpunits.checked == true){
document.importFormulari.iEixos.checked = true;
document.importFormulari.iPrioritats.checked = true;
document.importFormulari.iUnitats.checked = true;
}
}
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=modulesettings";
}
function importa(){
    if (document.importFormulari.catIdOri.value == '' ||document.importFormulari.catIdDest.value == ''||(document.importFormulari.iTot[0].checked== false && document.importFormulari.iEixos.checked == false && document.importFormulari.iUnitats.checked == false)) {
        alert('{{gt text="S\'ha d\'informar del catàleg origen, el de destinació, i els elements a importar."}}');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.importFormulari.action="index.php?module=Cataleg&type=admin&func=importCataleg";
        document.importFormulari.submit();
    }
}
</script>
