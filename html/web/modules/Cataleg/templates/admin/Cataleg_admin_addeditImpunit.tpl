{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2><a href="{modurl modname='Cataleg' type='admin' func='eixosgest' catId=$cat.catId}">{$cat.nom}</a> - {if $edit}{gt text="Edició de la unitat implicada"}{else}{gt text="Creació d'una nova unitat implicada"}{/if}<br><br><a href="{modurl modname='Cataleg' type='admin' func='prioritatsgest' eixId=$eix.eixId}">{$eix.ordre} {$eix.nomCurt}</a><br>{$prior.ordre} {$prior.nomCurt}</h2>

    <form name="novaImpunit" id="novaImpunit" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="catId" name="catId" value="{$cat.catId}">
        <input type="hidden" id="eixId" name="eixId" value="{$eix.eixId}">
        <input type="hidden" id="priId" name="priId" value="{$prior.priId}">
        {if $edit}<input type="hidden" id="impunitId" name="impunitId" value="{$impunit.impunitId}">{/if}
        <fieldset>
           <legend>{gt text='Informació de la unitat implicada'}</legend>
           <div class="z-formrow">
               <label for="tematica">{gt text="Temàtica"}</label>
               <input type="text" id="tematica" name="tematica" size="50" maxlength="50" {if $edit}value="{$impunit.tematica}"{/if}/>
           </div>
           
           <div class="z-formrow">
               <label for="pContacte">{gt text="Persona de contacte"}</label>
               <input type="text" id="pContacte" name="pContacte" size="120" maxlength="120" {if $edit}value="{$impunit.pContacte}"{/if}/>
           </div>
           <div class="z-formrow">
               <label for="email">{gt text="email"}</label>
               <input type="text" id="email" name="email" size="50" maxlength="50" {if $edit}value="{$impunit.email}"{/if}/>
           </div>
           <div class="z-formrow">
               <label for="telContacte">{gt text="Telèfon"}</label>
               <input type="text" id="telContacte" name="telContacte" size="20" maxlength="20" {if $edit}value="{$impunit.telContacte}"{/if}/>
           </div>
           <div class="z-formrow">
                <label for="dispFormador">{gt text="Disposen de formadors"}</label>
                <input type="checkbox" name="dispFormador" value="1" {if $edit}{if $impunit.dispFormador eq 1}checked="checked"{/if}{/if}/>
           </div>
           </fieldset>

           <fieldset>
    <legend>{gt text="Unitat responsable"}</legend>
    <select id="uniId" name="uniId">
        {foreach item="unit" from=$units}
            <option value="{$unit.uniId}" {if $edit}{if $unit.uniId == $impunit.uniId}selected="selected"{/if}{/if}>{$unit.nom}</option>
        {/foreach}
    </select>    
         </fieldset>   
<div id="botons" class="z-buttonrow z-buttons z-center">
                       {if !$edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea la unitat implicada"}">{gt text="Crea"}</button>
                       {else}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita la unitat implicada"}">{gt text="Edita"}</button>
                       {/if}   
                           <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$prior.priId});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                           </div>                    
         
           </div>
</form>
<script type="text/javascript"  language="javascript">
function cancel($priId){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=editPrior&priId="+$priId;
}
function save(){
    if (document.getElementById('pContacte').value.length == 0) {
        alert('S\'ha d\'informar del camp \'Persona de contacte\' per a poder desar la unitat implicada.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.novaImpunit.action="index.php?module=Cataleg&type=admin&func=addeditImpunit";
        document.novaImpunit.submit();
    }
}
</script>