{pageaddvar name='javascript' value='jQuery'}
{adminheader}
{if $auxElement.action eq 'edit'}{assign var='a' value=true}{else}{assign var='a' value=false}{/if}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2><a href="{modurl modname='Cataleg' type='admin' func='auxgest'}">{gt text="Taula auxiliar"}</a>  -  {if $a}{gt text="Edició d'un element"}{else}{gt text="Creació d'un nou element"}{/if}</h2>

    <form name="nouElement" id="nouElement" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        {if $a}<input type="hidden" id="auxId" name="auxId" value="{$auxElement.auxId}">{/if}
        <fieldset>
           <legend>{gt text='Informació de l\'element'}</legend>
           <div class="z-formrow">
               <label for="tipus">{gt text="Tipus"}</label>
               <select type="text" id="tipus" name="tipus">
                   <option value='abast' {if $a}{if $auxElement.tipus eq 'abast'}selected="selected"{/if}{/if}>{gt text="abast - Abast de la formació"}</option>
                   <option value='curs' {if $a}{if $auxElement.tipus eq 'curs'}selected="selected"{/if}{/if}>{gt text="curs - Modalitat de la formació"}</option>
                   <option value='dest' {if $a}{if $auxElement.tipus eq 'dest'}selected="selected"{/if}{/if}>{gt text="dest - Destinataris"}</option>
                   <option value='gest' {if $a}{if $auxElement.tipus eq 'gest'}selected="selected"{/if}{/if}>{gt text="gest - Unitats de gestió"}</option>
                   <option value='pres' {if $a}{if $auxElement.tipus eq 'pres'}selected="selected"{/if}{/if}>{gt text="pres - Presencialitat"}</option>
                   <option value='sstt' {if $a}{if $auxElement.tipus eq 'sstt'}selected="selected"{/if}{/if}>{gt text="sst - Serveis Territorials"}</option>
                </select>
           </div>
           <div class="z-formrow">
               <label for="nom">{gt text="Nom"}</label>
               <input type="text" size="50" maxlength="50"id="nom" name="nom" {if $a}value="{$auxElement.nom}"{/if}>
           </div> 
           <div class="z-formrow">
               <label for="nomCurt">{gt text="Nom curt"}</label>
               <input type="text" size="10" maxlength="10"id="nomCurt" name="nomCurt" {if $a}value="{$auxElement.nomCurt}"{/if}>
           </div> 
           <div class="z-formrow">
               <label for="ordre">{gt text="Ordre"}</label>
               <input type="text" size="3" maxlength="3"id="ordre" name="ordre" {if $a}value="{$auxElement.ordre}"{/if}>
           </div> 
            
           <div class="z-formrow">
                <label for="visible">{gt text="Visibilitat"}</label>
                <input type="checkbox" name="visible" value="1" {if !isset($auxElement.visible) || $auxElement.visible eq 1}checked="checked"{/if}/>
           </div>
                
           
           </div>
                   <div id="botons" class="z-buttonrow z-buttons z-center">
                       {if $a}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita l'element"}">{gt text="Edita"}</button>
                       {else}
                   <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea l'element"}">{gt text="Crea"}</button>                    
                   {/if}
                   <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                   </div>
</form>
   
<script type="text/javascript"  language="javascript">
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=auxgest";
}
function save(){
    if (document.getElementById('ordre').value.length == 0 || document.getElementById('nom').value.length == 0) {
        alert('S\'ha d\'informar dels camps \'Ordre\' i \'Nom\'.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.nouElement.action="index.php?module=Cataleg&type=admin&func=addeditAuxElement";
        document.nouElement.submit();
    }
}
</script>