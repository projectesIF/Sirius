{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2>{if $edit}{gt text="Edició d'un grup d'entitats-gtaf"}{else}{gt text="Creació d'un nou grup d'entitats-gtaf"}{/if}</h2>

    <form name="GtafGroup" id="GtafGroup" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        {if $edit}<input type="hidden" id="prev_gtafGroupId" name="prev_gtafGroupId" value="{$gtafGroup.gtafGroupId}">{/if}
        <fieldset>
           <legend>{gt text="Informació del grup d'entitats-gtaf"}</legend>
           <div class="z-formrow">
               <label for="gtafGroupId">{gt text="Codi Grup"}</label>
               <input style="width:9em" type="text" id="gtafGroupId" name="gtafGroupId" size="5" maxlength="5" {if $edit}value="{$gtafGroup.gtafGroupId}"{/if}/>
           </div>
           <div class="z-formrow">
               <label for="nom">{gt text="Nom"}</label>
               <input type="text" id="nom" name="nom" size="50" maxlength="100" {if $edit}value="{$gtafGroup.nom}"{/if}/>
           </div> 
            
                   <div class='z-formrow'>
                       <label for="resp_uid">{gt text="Responsable"}</label>
                       <select name="resp_uid">
                           <option value=""></option>
                           {foreach item="user" from=$catusers}
                               <option value="{$user.zk.uid}" {if $edit}{if $gtafGroup.resp_uid == $user.zk.uid}selected="selected"{/if}{/if}>{$user.zk.uname} - {$user.iw.nom} {$user.iw.cognom1} {$user.iw.cognom2}</option>
                           {/foreach}
                       </select>
                   </div>
           </div>
                   <div id="botons" class="z-buttonrow z-buttons z-center">
                       {if $edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita el grup d'entitats"}">{gt text="Edita"}</button>
                       {else}
                   <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea el grup d'entitats"}">{gt text="Crea"}</button>                    
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
    var conf = true;
    var gtafGroups = {{$gtafGroups|@json_encode}}
    // Evitem el submit múltiple desactivant botons
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    // Checking mandatory elements
    if (document.getElementById('gtafGroupId').value.length == 0 || document.getElementById('nom').value.length == 0 ) {
        alert('S\'han d\'introduir totes les dades per a poder desar el grup d\'entitats.');
        conf = false;
    // Checking if new gtafGroupId is allowed
    } else if ((jQuery.inArray(document.getElementById('gtafGroupId').value, gtafGroups) != -1) && (document.getElementById('gtafGroupId').value != '{{$gtafGroup.gtafGroupId}}')) {
        alert('{{gt text="El codi de grup d\'entitats introduït ja es fa servir.\\n\\nTrieu un altre."}}');
        conf = false;
    // Confirm gtafEntitiesId changes for gtafGroupId changes
    {{if $edit}}
    } else if (document.getElementById('gtafGroupId').value != '{{$gtafGroup.gtafGroupId}}') {
        var mes = '{{gt text="Heu canviat el codi de grup.\\n\\nSi confirmeu el canvi, es procedirà a canviar la referència a totes les entitats vinculades amb aquest grup"}}';
        if (!confirm(mes)) conf = false;
    {{/if}}
    }
    //
    if (conf) {
        document.GtafGroup.action="index.php?module=Cataleg&type=admin&func=addeditGtafGroup";
        document.GtafGroup.submit();
    } else {
        document.getElementById("btn_save").disabled = false;
        document.getElementById("btn_cancel").disabled = false;
    }
}
</script>