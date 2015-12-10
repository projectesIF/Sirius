{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2><a href="{modurl modname='Cataleg' type='admin' func='unitatsgest' catId=$cat.catId}">{$cat.nom}</a> - {if $edit}{gt text="Edició de persones responsables"}{else}{gt text="Creació d'una nova persona responsable"}{/if}<br><a href="{modurl modname='Cataleg' type='admin' func='editUnitat' uniId=$unitat.uniId}">{$unitat.nom}</a></h2>

    <form name="nouResponsable" id="nouResponsable" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="catId" name="catId" value="{$cat.catId}">
        <input type="hidden" id="uniId" name="uniId" value="{$unitat.uniId}">
        {if $edit}<input type="hidden" id="respunitId" name="respunitId" value="{$responsable.respunitId}">{/if}
        <fieldset>
           <legend>{gt text='Informació de la persona responsable'}</legend>
           <div class="z-formrow">
               <label for="responsable">{gt text="Nom"}</label>
               <input type="text" id="responsable" name="responsable" size="120" maxlength="120" {if $edit}value="{$responsable.responsable}{/if}"/>
           </div>
           <div class="z-formrow">
               <label for="email">{gt text="email"}</label>
               <input type="text" id="email" name="email" size="50" maxlength="50" {if $edit}value="{$responsable.email}"{/if}/>
           </div>
           <div class="z-formrow">
               <label for="telefon">{gt text="Telèfon"}</label>
               <input type="text" id="telefon" name="telefon" size="20" maxlength="20" {if $edit}value="{$responsable.telefon}"{/if}/>
           </div>
                      
<div id="botons" class="z-buttonrow z-buttons z-center">
                       {if !$edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea la persona responsable"}">{gt text="Crea"}</button>
                       {else}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita la persona responsable"}">{gt text="Edita"}</button>
                       {/if}   
                           <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$unitat.uniId});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                           </div>                    
         
           </div>
</form>
<script type="text/javascript"  language="javascript">
function cancel($uniId){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=editUnitat&uniId="+$uniId;
}
function save(){
    if (document.getElementById('responsable').value.length == 0) {
        alert('S\'ha d\'informar del camp \'Nom\' per a poder desar la persona responsable.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.nouResponsable.action="index.php?module=Cataleg&type=admin&func=addeditResponsable";
        document.nouResponsable.submit();
    }
}
</script>