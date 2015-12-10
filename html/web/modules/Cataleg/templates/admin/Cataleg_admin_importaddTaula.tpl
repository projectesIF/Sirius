{adminheader}
<style>
    #catDest {
        display:none;
    }
    .optionhide {
        display:none;
    }
</style>
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Creació d'una taula d'importació d'activitats entre catàlegs"}</h3>
</div>
<form name="importTaula" id="importTaula" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend>{gt text="Nova taula d'importació"}</legend>
        <div class="z-formrow">
            <label for="catIdOri">{gt text="Catàleg d'origen"}</label>
            <select id="catIdOri" name="catIdOri" onchange="triaCat();">
                <option value=''>Tria un catàleg...</option>
                {foreach from=$cats item='cat' key='key'}
                    <option value={$cat.catId}>{$cat.nom}</option>
                {/foreach}
            </select>
        </div>
        <div class="z-formrow" id="catDest">
            <label for="catIdDest">{gt text="Catàleg destí"}</label>
            <select id="catIdDest" name="catIdDest">
                <option value=''>Tria un catàleg...</option>
                {foreach from=$cats item='cat' key='key'}
                    <option id="opt-{$cat.catId}" value={$cat.catId}>{$cat.nom}</option>
                {/foreach}
            </select>
        </div>
    </fieldset>
            </form>
<div id="botons" class="z-buttonrow z-buttons z-center">
    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:crea();" title="{gt text="Crea"}">{gt text="Crea"}</button>
    <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
</div>
<script src="javascript/jquery/jquery-1.7.0.js"></script>
<script type="text/javascript"> var $jq=jQuery.noConflict(true);</script>
<script>
    function triaCat(){
    var x = document.importTaula.catIdOri.selectedIndex;
    var a = document.importTaula.catIdOri.options[x].value;
    if (a == '') {
    document.importTaula.catIdDest.selectedIndex = 0;
    $jq("#catDest").slideUp();
} else {
$jq("option").removeClass('optionhide');
$jq("#catDest").slideDown();
var y = document.importTaula.catIdDest.selectedIndex;
if (x == y) {
document.importTaula.catIdDest.selectedIndex = 0;
}
$jq("option#opt-"+a).addClass('optionhide');
}
}
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=importgest";
}
function crea(){
    if (document.importTaula.catIdOri.value == '' ||document.importTaula.catIdDest.value == '') {
        alert('{{gt text="S\'ha d\'informar del catàleg origen i del catàleg destinació per a desar la taula."}}');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.importTaula.action="index.php?module=Cataleg&type=admin&func=importaddsaveTaula";
        document.importTaula.submit();
    }
}
</script>