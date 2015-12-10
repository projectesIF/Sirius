{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2>{if $edit}{gt text="Edició d'una entitat-gtaf"}{else}{gt text="Creació d'una nova entitat-gtaf"}{/if}</h2>

    <form name="GtafEntity" id="GtafEntity" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        {if $edit}<input type="hidden" id="prev_gtafEntityId" name="prev_gtafEntityId" value="{$gtafEntity.gtafEntityId}">{/if}
        <fieldset>
           <legend>{gt text="Informació de l'entitat-gtaf"}</legend>
           <div class="z-formrow">
               <label for="gtafEntityId">{gt text="Codi Entitat"}</label>
               <input style="width:9em" type="text" id="gtafEntityId" name="gtafEntityId" size="5" maxlength="5" {if $edit}value="{$gtafEntity.gtafEntityId}"{/if}/>
           </div>
           <div class="z-formrow">
               <label for="nom">{gt text="Nom"}</label>
               <input type="text" id="nom" name="nom" size="50" maxlength="100" {if $edit}value="{$gtafEntity.nom}"{/if}/>
           </div> 
            
           <div class="z-formrow">
                <label for="tipus">{gt text="Tipus"}</label>
                <table>
                   <tr>
                        <td align="center" width="80px"><input type="radio" id="tipusa" name="tipus" value="UNI" {if $edit}{if $gtafEntity.tipus eq 'UNI'}checked="checked"{/if}{/if}></td>
                        <td align="center" width="80px"><input type="radio" id="tipusb" name="tipus" value="ST" {if $edit}{if $gtafEntity.tipus eq 'ST'}checked="checked"{/if}{/if}></td>
                        <td align="center" width="80px"><input type="radio" id="tipusc" name="tipus" value="SE" {if $edit}{if $gtafEntity.tipus eq 'SE'}checked="checked"{/if}{/if}></td>
                   </tr>
                   <tr>
                       <td align="center">{gt text="Unitat"}</td>
                       <td align="center">{gt text="Servei Territorial"}</td>
                       <td align="center">{gt text="Servei Educatiu"}</td>
                   </tr>
                   
                </table>
           </div>
                   <div class='z-formrow'>
                       <label for="gtafGroupId">{gt text="Codi_grup"}</label>
                       <select name="gtafGroupId">
                            {foreach item="gtafGrup" from=$gtafGroups}
            <option value="{$gtafGrup.gtafGroupId}" {if $edit}{if $gtafGrup.gtafGroupId == $gtafEntity.gtafGroupId}selected="selected"{/if}{/if}>{$gtafGrup.gtafGroupId} - {$gtafGrup.nom}</option>
        {/foreach}
                       </select>
                   </div>
           </div>
                   <div id="botons" class="z-buttonrow z-buttons z-center">
                       {if $edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita l'entitat"}">{gt text="Edita"}</button>
                       {else}
                   <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea l'entitat"}">{gt text="Crea"}</button>                    
                       {/if}
                   <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                   </div>
</form>
   
<script type="text/javascript"  language="javascript">
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=gtafEntitiesGest";
}
function save(){
    if (document.getElementById('gtafEntityId').value.length == 0 || document.getElementById('nom').value.length == 0 || !jQuery("#GtafEntity input[name='tipus']:checked").val()) {
        alert('S\'han d\'introduir totes les dades per a poder desar l\'entitat.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        var gtafEntities = {{$gtafEntities|@json_encode}}
        if ((jQuery.inArray(document.getElementById('gtafEntityId').value, gtafEntities) != -1) && (document.getElementById('gtafEntityId').value != '{{$gtafEntity.gtafEntityId}}')) {
            alert('{{gt text="El codi d\'entitat introduït ja el fa servir una altra entitat.\\n\\nTrieu un altre."}}');
            document.getElementById("btn_save").disabled = false;
            document.getElementById("btn_cancel").disabled = false;
        }else{
        document.GtafEntity.action="index.php?module=Cataleg&type=admin&func=addeditGtafEntity";
        document.GtafEntity.submit();
        }
    }
}
</script>