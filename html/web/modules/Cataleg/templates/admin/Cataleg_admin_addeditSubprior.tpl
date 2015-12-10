{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2><a href="{modurl modname='Cataleg' type='admin' func='eixosgest' catId=$cat.catId}">{$cat.nom}</a> - {if $edit}{gt text="Edició de la subprioritat"}{else}{gt text="Creació d'una nova subprioritat"}{/if}<br><br><a href="{modurl modname='Cataleg' type='admin' func='prioritatsgest' eixId=$eix.eixId}">{$eix.ordre} {$eix.nomCurt}</a><br>{$prior.ordre} {$prior.nomCurt}</h2>

    <form name="novaSubprior" id="novaSubprior" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="catId" name="catId" value="{$cat.catId}">
        <input type="hidden" id="eixId" name="eixId" value="{$eix.eixId}">
        <input type="hidden" id="priId" name="priId" value="{$prior.priId}">
        {if $edit}<input type="hidden" id="sprId" name="sprId" value="{$subprior.sprId}">{/if}
        <fieldset>
           <legend>{gt text='Informació de la subprioritat'}</legend>
           <div class="z-formrow">
               <label for="ordre">{gt text="Ordre"}</label>
               <input type="text" id="ordre" name="ordre" size="2" maxlength="2" {if $edit}value="{$subprior.ordre}"{/if}/>
           </div>
           
           <div class="z-formrow">
               <label for="nomCurt">{gt text="Nom (curt)"}</label>
               <textarea id="nomCurt" name="nomCurt" rows="3" cols="90" maxlength="200">{if $edit}{$subprior.nomCurt}{/if}</textarea>
           </div>
           <div class="z-formrow">
               <label for="nom">{gt text="Nom"}</label>
               <textarea id="nom" name="nom" rows="3" cols="90" maxlength="255">{if $edit}{$subprior.nom}{/if}</textarea>
           </div> 
            
           <div class="z-formrow">
                <label for="visible">{gt text="Visibilitat"}</label>
                <input type="checkbox" name="visible" value="1" {if $edit}{if !isset($subprior.visible) || $subprior.visible eq 1}checked="checked"{/if}{/if}/>
           </div>
           
<div id="botons" class="z-buttonrow z-buttons z-center">
                       {if !$edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea la subprioritat"}">{gt text="Crea"}</button>
                       {else}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita la subprioritat"}">{gt text="Edita"}</button>
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
    if (document.getElementById('ordre').value.length == 0 || document.getElementById('nomCurt').value.length == 0 || document.getElementById('nom').value.length == 0) {
        alert('S\'ha d\'informar dels camps \'Ordre\', \'Nom (curt)\' i \'Nom\' per a poder desar la subprioritat.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.novaSubprior.action="index.php?module=Cataleg&type=admin&func=addeditSubprior";
        document.novaSubprior.submit();
    }
}
</script>