{pageaddvar name='javascript' value='jQuery'}
{pageaddvar name='stylesheet' value='vendor/bootstrap/css/bootstrap.css'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2><a href="{modurl modname='Cataleg' type='admin' func='eixosgest' catId=$cat.catId}">{$cat.nom}</a>  -  {if $edit}{gt text="Edició d'una prioritat"}{else}{gt text="Creació d'una nova prioritat"}{/if}<br><br><a href="{modurl modname='Cataleg' type='admin' func='prioritatsgest' eixId=$eix.eixId}">Eix: {$eix.ordre} {$eix.nom}</a></h2>

    <form name="novaPrior" id="novaPrior" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="catId" name="catId" value="{$cat.catId}">
        <input type="hidden" id="eixId" name="eixId" value="{$eix.eixId}">
        {if $edit}<input type="hidden" id="priId" name="priId" value="{$prior.priId}">{/if}
        <fieldset>
           <legend>{gt text='Informació de la prioritat'}</legend>
           <div class="z-formrow">
               <label for="ordre">{gt text="Ordre"}</label>
               <input type="text" id="ordre" name="ordre" size="2" maxlength="2" {if $edit}value="{$prior.ordre}"{/if}/>
           </div>
           
           <div class="z-formrow">
               <label for="nomCurt">{gt text="Nom (curt)"}</label>
               <textarea id="nomCurt" class="noeditor name="nomCurt" rows="3" cols="90" maxlength="200">{if $edit}{$prior.nomCurt}{/if}</textarea>
           </div>
           <div class="z-formrow">
               <label for="nom">{gt text="Nom"}</label>
               <textarea id="nom" class="noeditor" name="nom" rows="3" cols="90" maxlength="255">{if $edit}{$prior.nom}{/if}</textarea>
           </div> 
            
           <div class="z-formrow">
                <label for="visible">{gt text="Visibilitat"}</label>
                <input type="checkbox" name="visible" value="1" {if $edit}{if !isset($prior.visible) || $prior.visible eq 1}checked="checked"{/if}{/if}/>
           </div>
           <br>
           <fieldset style="text-align:justify;text-justify:inter-word;">
            <legend>{gt text="Orientacions"}</legend>
            <textarea rows=15 name="orientacions" id="orientacions">{if $edit}{$prior.orientacions}{/if}
            </textarea>
        </fieldset>
        <br>
        <br>

        <fieldset style="text-align:justify;text-justify:inter-word;">
            <legend>{gt text="Recursos"}</legend>
            <textarea rows=10 name ='recursos' id='recursos'>{if $edit}{$prior.recursos}{/if}</textarea>
        </fieldset>
        <br>
<div id="botons" class="z-buttonrow z-buttons z-center">
                       {if !$edit }
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea la prioritat"}">{gt text="Crea"}</button>
                           <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$eix.eixId});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                           <br><br><p class="z-formnote z-informationmsg">{gt text="Un cop creada la prioritat, es podran definir subprioritats i unitats implicades en editar-la."}</p>
                           </div>
                       {else}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita la prioritat"}">{gt text="Edita"}</button>
                           <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$eix.eixId});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                           <button id="btn_delete" class="z-bt-delete"  type="button"   onclick="javascript:esborraPrioritat({$prior.priId});" title="{gt text="Esborra la prioritat"}">{gt text="Esborra"}</button>
                           <br><br><p class="z-warningmsg">{gt text="En esborrar la prioritat, s'esborraran també les seves subprioritats i la informació de les persones de contacte de les unitats implicades"}</p>
                           </div>                    
                   
     </form>   
        <br>
        <fieldset>
            <legend>{gt text="Subprioritats"}</legend>
            {include file="admin/Cataleg_admin_subprioritatsgest.tpl"}
        </fieldset>
        <br>
        <fieldset>
            <legend>{gt text="Unitats implicades"}</legend>
            {include file="admin/Cataleg_admin_impunitsgest.tpl"}
        </fieldset>
           
           {/if}
           
           </div>
                   
{notifydisplayhooks eventname='Cataleg.ui_hooks.Cataleg.form_edit' id=null}
   
<script type="text/javascript"  language="javascript">
function cancel($eixId){
// Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    if (document.getElementById("btn_delete")) document.getElementById("btn_delete").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=prioritatsgest&eixId="+$eixId;
}
function save(){
    if (document.getElementById('ordre').value.length == 0 || document.getElementById('nomCurt').value.length == 0 || document.getElementById('nom').value.length == 0) {
        alert('S\'ha d\'informar dels camps \'Ordre\', \'Nom (curt)\' i \'Nom\' per a poder desar la prioritat.');
    } else if (isNaN(document.getElementById('ordre').value)) {
        alert('L\'ordre ha de tenir un valor numèric');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        if (document.getElementById("btn_delete")) document.getElementById("btn_delete").disabled = true;
        document.novaPrior.action="index.php?module=Cataleg&type=admin&func=addeditPrior";
        document.novaPrior.submit();
    }
}
function esborraPrioritat($priId) {
    var $mess = '{{gt text="En esborrar la prioritat, s\'esborraran també les seves subprioritats i la informació de les persones de contacte de les unitats implicades."}}\n\n{{gt text="Tanmateix, es comprovarà abans que la prioritat no té activitats associades del catàleg ni establertes equivalències per a la importació."}}\n\n{{gt text="Voleu esborrar-la?"}}';
    if (confirm($mess)) {
    window.location = "index.php?module=Cataleg&type=admin&func=deletePrior&priId="+$priId;
    }
}
</script>