{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2>{if $edit}{gt text="Edició d'un catàleg"}{else}{gt text="Creació d'un nou catàleg"}{/if}</h2>

    <form name="nouCat" id="nouCat" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="catId" name="catId" {if $edit}value="{$cat.catId}"{/if}>
        <fieldset>
           <legend>{gt text='Informació del catàleg'}</legend>
           <div class="z-formrow">
               <label for="anyAcad">{gt text="Curs"}</label>
               <input style="width:9em" type="text" id="anyAcad" name="anyAcad" size="9" maxlength="9" {if $edit}value="{$cat.anyAcad}"{/if}/>
           </div>
           <div class="z-formrow">
               <label for="nom">{gt text="Nom"}</label>
               <input type="text" id="nom" name="nom" size="50" maxlength="75" {if $edit}value="{$cat.nom}"{/if}/>
           </div> 
            
           <div class="z-formrow">
                <label for="editable">{gt text="Editable"}</label>
                <input type="checkbox" name="editable" value="1" {if $edit}{if $cat.editable eq 1}checked="checked"{/if}{/if}/>
           </div>
           <div class="z-formrow">
                <label for="estat">{gt text="Estat"}</label>
                <table>
                   <tr>
                        <td align="center" width="80px"><input type="radio" id="estata" name="estat" value="{'Cataleg_Constant::TANCAT'|constant}" {if $edit}{if $cat.estat eq 'Cataleg_Constant::TANCAT'|constant}checked="checked"{/if}{/if}></td>
                        <td align="center" width="80px"><input type="radio" id="estatb" name="estat" value="{'Cataleg_Constant::LES_MEVES'|constant}" {if $edit}{if $cat.estat eq 'Cataleg_Constant::LES_MEVES'|constant}checked="checked"{/if}{/if}></td>
                        <td align="center" width="80px"><input type="radio" id="estatc" name="estat" value="{'Cataleg_Constant::ORIENTACIONS'|constant}" {if $edit}{if $cat.estat eq 'Cataleg_Constant::ORIENTACIONS'|constant}checked="checked"{/if}{/if}></td>
                        <td align="center" width="80px"><input type="radio" id="estatd" name="estat" value="{'Cataleg_Constant::ACTIVITATS'|constant}" {if $edit}{if $cat.estat eq 'Cataleg_Constant::ACTIVITATS'|constant}checked="checked"{/if}{/if}></td>
                        <td align="center" width="80px"><input type="radio" id="estate" name="estat" value="{'Cataleg_Constant::OBERT'|constant}" {if $edit}{if $cat.estat eq 'Cataleg_Constant::OBERT'|constant}checked="checked"{/if}{/if}></td>
                   </tr>
                   <tr>
                       <td align="center" style="padding-top:5px">{img modname='cataleg' src='anullada.png' __title='Tancat' __alt='Tancat'}</td>
                       <td align="center">{img modname='cataleg' src='esborrany.png' __title='Les meves activitats' __alt='Les meves activitats'}</td>
                       <td align="center">{img modname='cataleg' src='enviada.png' __title='Orientacions' __alt='Orientacions'}</td>
                       <td align="center">{img modname='cataleg' src='cat_activitats.png' __title='Activitats' __alt='Activitats'}</td>
                       <td align="center">{img modname='cataleg' src='validada.png' __title='Obert' __alt='Obert'}</td>
                   </tr>
                   <tr>
                       <td align="center">{gt text="Tancat"}</td>
                       <td align="center">{gt text="Les Meves Activitats"}</td>
                       <td align="center">{gt text="Orientacions"}</td>
                       <td align="center">{gt text="Activitats"}</td>
                       <td align="center">{gt text="Obert"}</td>
                   </tr>
                   
                </table>
           </div>
           </div>
                   <div id="botons" class="z-buttonrow z-buttons z-center">
                       {if $edit}
                           <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita el catàleg"}">{gt text="Edita"}</button>
                       {else}
                   <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea el catàleg"}">{gt text="Crea"}</button>                    
                       {/if}
                   <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
                   </div>
</form>
   
<script type="text/javascript"  language="javascript">
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=catalegsgest";
}
function save(){
    if (document.getElementById('anyAcad').value.length == 0 || document.getElementById('nom').value.length == 0 || !jQuery("#nouCat input[name='estat']:checked").val()) {
        alert('S\'han d\'introduir totes les dades per a poder desar el catàleg.');
    } else {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.nouCat.action="index.php?module=Cataleg&type=admin&func=addeditCat";
        document.nouCat.submit();
    }
}
</script>