{pageaddvar name='javascript' value='jQuery'}
{pageaddvar name='stylesheet' value='vendor/bootstrap/css/bootstrap.css'}
{adminheader}
<div class="z-admincontainer">
    <div>
    <h2>{img modname='core' src='filenew.png' set='icons/small'}<a href="{modurl modname='Cataleg' type='admin' func='unitatsgest' catId=$cat.catId}">{$cat.nom}</a>  -  {if $edit}{gt text="Edició d'una unitat"}{else}{gt text="Creació d'una nova unitat"}{/if}</h2>
    </div>
    <form name="novaUnitat" id="novaUnitat" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">        
        <input type="hidden" id="catId" name="catId" value="{$cat.catId}">
        <input type="hidden" id="isEditor" name="isEditor" value="{$isEditor}">
        {if $edit}<input type="hidden" id="uniId" name="uniId" value="{$unitat.uniId}">{/if}
        <fieldset>
            <legend>{gt text='Informació de la unitat'}</legend>
            {if !$isEditor}                   
                <div class="z-formrow">  
                    <label for="nom">{gt text="Nom"}</label>
                    <input type="text" class="noeditor" id="nom" name="nom" maxlength="255" required {if $edit}value="{$unitat.nom}"{/if}>
                </div>
                {*else}
                <input type="hidden" id="nom" name="nom" value="{$unitat.nom}">*}
            {/if}
            <div class="form-group">
                <label for="descripcio">{gt text="Descripció"}</label>
                <textarea id="descripcio" name="descripcio" rows="7" cols="90" maxlength="630">{if $edit}{$unitat.descripcio}{/if}</textarea>
            </div>
            {if !$isEditor}                             
                <div class="z-formrow">
                    <label for="activa">{gt text="Activa"}</label>
                    <input type="checkbox" name="activa" value="1" {if $edit}{if !isset($unitat.activa) || $unitat.activa eq 1}checked="checked"{/if}{/if}/>
                </div>
                <div class="z-formrow">
                    <label for="gzId">{gt text="Grup d'usuaris"}</label>
                    <select id="gzId" name="gzId">
                        {foreach item="grup" from=$grups}
                            <option value="{$grup.gid}" {if $edit}{if $grup.gid == $unitat.gzId}selected="selected"{/if}{/if}>{$grup.name}</option>
                        {/foreach}
                    </select>    
                </div>
            {/if}
            <div id="botons" class="z-buttonrow z-buttons z-center">
            {if !$edit}
                    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea la unitat"}">{gt text="Crea"}</button>
                    <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$cat.catId});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                    <br><br><p class="z-formnote z-informationmsg">{gt text="Un cop creada la unitat, es podran definir les persones responsables en editar-la."}</p>

                </div>
            {else}
                <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita la unitat"}">{gt text="Edita"}</button>
                <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$cat.catId});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                {if !$isEditor}
                    <button id="btn_delete" class="z-bt-delete"  type="button"  onclick="javascript:esborraUnitat({$unitat.uniId});" title="{gt text="Esborra la unitat"}">{gt text="Esborra"}</button>
                    <br><br><p class="z-warningmsg">{gt text="En esborrar la unitat, s'esborrarà també la informació de les seves persones responsables."}</p>
                {/if}
                </div>                    
                <br>
                <fieldset>
                    <legend>{gt text="Responsables"}</legend>
                    {include file="admin/Cataleg_admin_responsablesgest.tpl"}
                </fieldset>
            {/if}
    </form>
{notifydisplayhooks eventname='Cataleg.ui_hooks.Cataleg.form_edit' id="#orientacions"}   
<script type="text/javascript"  language="javascript">
function cancel($catId){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    if (document.getElementById("btn_delete")) document.getElementById("btn_delete").disabled = true;
    if (document.getElementById("isEditor").value == true) {
        window.location = "index.php?module=Cataleg&type=user&func=view&catId="+$catId;
    } else window.location = "index.php?module=Cataleg&type=admin&func=unitatsgest&catId="+$catId;
}
function save(){
    var element = document.getElementById('nom');
    if (typeof(element) != "undefined" && element != null && element.value.length == 0) {
        alert('S\'ha d\'informar el camp \'nom\' per a poder desar la unitat.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        if (document.getElementById("btn_delete")) document.getElementById("btn_delete").disabled = true;
        document.novaUnitat.action="index.php?module=Cataleg&type=admin&func=addeditUnitat";
        document.novaUnitat.submit();
    }
}
function esborraUnitat($uniId) {
        var $mess = '{{gt text="En esborrar la unitat, s\'esborrarà també la informació de les seves persones responsables."}}\n\n{{gt text="Tanmateix, es comprovarà abans que la unitat no té activitats ni persones de contacte d\'alguna prioritat del catàleg."}}\n\n{{gt text="Voleu esborrar-la?"}}';
        if (confirm($mess)) {
        window.location = "index.php?module=Cataleg&type=admin&func=deleteUnitat&uniId="+$uniId;
        }
    }
</script>