{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2>{if $edit}{gt text="Edició d'un grup d'usuaris"}{else}{gt text="Creació d'un grup d'usuaris"}{/if}</h2>

    <form name="nougrup" id="nougrup" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        {if $edit}<input type="hidden" id="gid" name="gid" value="{$grupUnitat.gid}">{/if}
        <fieldset>
           <legend>{gt text='Informació del grup'}</legend>
           <div class="z-formrow">
               <label for="name">{gt text="Nom del grup"}</label>
               <input type="text" id="name" name="name" size="255" maxlength="255" {if $edit}value="{$grupUnitat.name}"{/if}/>
           </div>
           <div class="z-formrow">
               <label for="description">{gt text="Descripció del grup"}</label>
               <input type="text" id="description" name="description" size="200" maxlength="200" {if $edit}value="{$grupUnitat.description}"{/if}/>
           </div>
           
           
           </div>
                   <div id="botons" class="z-buttonrow z-buttons z-center">
                       {if $edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita el grup"}">{gt text="Edita"}</button>
                       {else}
                   <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea el grup"}">{gt text="Crea"}</button>                    
                   {/if}
                   <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                   </div>
</form>
   
<script type="text/javascript"  language="javascript">
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=groupsgest";
}
function save(){
    if (document.getElementById('name').value.length == 0 || document.getElementById('description').value.length == 0) {
        alert('S\'ha d\'informar dels camps \'Nom\' i \'Descripció\' per a poder desar el grup.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.nougrup.action="index.php?module=Cataleg&type=admin&func=addeditGroup";
        document.nougrup.submit();
    }
}
</script>