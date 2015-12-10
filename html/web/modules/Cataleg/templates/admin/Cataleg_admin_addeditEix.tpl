{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2><a href="{modurl modname='Cataleg' type='admin' func='eixosgest' catId=$cat.catId}">{$cat.nom}</a>  -  {if $edit}{gt text="Edició d'un eix"}{else}{gt text="Creació d'un nou eix"}{/if}</h2>

    <form name="nouEix" id="nouEix" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="catId" name="catId" value="{$cat.catId}">
        {if $edit}<input type="hidden" id="eixId" name="eixId" value="{$eix.eixId}">{/if}
        <fieldset>
           <legend>{gt text='Informació de l\'eix'}</legend>
           <div class="z-formrow">
               <label for="ordre">{gt text="Ordre"}</label>
               <input type="text" id="ordre" name="ordre" size="2" maxlength="2" {if $edit}value="{$eix.ordre}"{/if}/>
           </div>
           
           <div class="z-formrow">
               <label for="nomCurt">{gt text="Nom (curt)"}</label>
               <textarea id="nomCurt" name="nomCurt" rows="2" cols="90" maxlength="100">{if $edit}{$eix.nomCurt}{/if}</textarea>
           </div>
           <div class="z-formrow">
               <label for="nom">{gt text="Nom"}</label>
               <textarea id="nom" name="nom" rows="3" cols="90" maxlength="255">{if $edit}{$eix.nom}{/if}</textarea>
           </div> 
            
           <div class="z-formrow">
                <label for="visible">{gt text="Visibilitat"}</label>
                <input type="checkbox" name="visible" value="1" {if $edit}{if !isset($eix.visible) || $eix.visible eq 1}checked="checked"{/if}{/if}/>
           </div>
           <div class="z-formrow">
               <label for="descripcio">{gt text="Descripció"}</label>
               <textarea id="descripcio" name="descripcio" rows="4" cols="90" maxlength="350">{if $edit}{$eix.descripcio}{/if}</textarea>
           </div>
           
           
           </div>
                   <div id="botons" class="z-buttonrow z-buttons z-center">
                       {if $edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita l'eix"}">{gt text="Edita"}</button>
                       {else}
                   <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea l'eix"}">{gt text="Crea"}</button>                    
                   {/if}
                   <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$cat.catId});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                   </div>
</form>
<script type="text/javascript"  language="javascript">
function cancel($catId){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=eixosgest&catId="+$catId;
}
function save(){
    if (document.getElementById('ordre').value.length == 0 || document.getElementById('nomCurt').value.length == 0 || document.getElementById('nom').value.length == 0) {
        alert('S\'ha d\'informar dels camps \'Ordre\', \'Nom (curt)\' i \'Nom\' per a poder desar l\'eix.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.nouEix.action="index.php?module=Cataleg&type=admin&func=addeditEix";
        document.nouEix.submit();
    }
}
</script>