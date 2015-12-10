{if isset($uOrig) && @count($uOrig) > 0}
    
    <div id="uOrig" style="padding-top:5px">
        <label for="uo">{gt text = "Des de: "}</label>
        <select id="unisOrigen" name="unisOrigen" onchange="uDestChange(this.value);" >
            {foreach item="uo" from=$uOrig}
                <option value="{$uo.uniId}">{$uo.nom}</option>
            {/foreach}
        </select>
    </div>
    <div id="alist"  style="padding-top:5px">    
        {include file="user/Cataleg_user_importActsList.tpl"}
    </div>
    <div id="uDest" style=" display:none; padding-top:5px">
       <span style="font-weight:bold"> <label for="ud"> {gt text = "Destí de les activitats seleccionades: "}</label></span>
        <select id="ud" name="ud">
            {foreach item="ud" from=$uDest}
                <option value="{$ud.uniId}">{$ud.nom}</option>
            {/foreach}
        </select>  
    </div>
    <div id="botons" class="z-buttonrow z-buttons z-center">
        <button id='btn_import' style="display:none;" class="z-bt-import" type="button" onclick="javascript:importa();" title="{gt text="Importa les activitats seleccionades"}">{gt text="Importa"}</button>
        <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
    </div>   
{else}
    <div class="z-errormsg">
        {gt text='No pertanys a cap unitat del catàleg origen seleccionat. No hi ha activitats per importar.'}
    </div>
{/if}

<script type="text/javascript">
    if (document.getElementById('unisOrigen'))
    window.onload = uDestChange(document.getElementById('unisOrigen').options[0].value);    

    function showHideImportButton(){
        var countchecked = $$("input.cbAct[type=checkbox]:checked").length;
        if (countchecked > 0) {
            // mostrar botó impoortar
            $$("#btn_import").show();
            $$("#uDest").show();
        } else {
            // ocultar botó importar
            $$("#btn_import").hide();
            $$("#uDest").hide();
        }
    }
    /*
     * Actualitza el valor del camp acts[] amb la opció escollida en el desplegable
     */
    function updateSelection(val, obj){
        $(obj).value=val;
    }
    
    // Selecciona / deselecciona totes les activitats de la llista
    function selectAll(obj){
        $$('#llista .cbAct').attr('checked', obj.checked);
        showHideImportButton();
    }

    function cancel(){
    // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_import")) document.getElementById("btn_import").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        window.location = "index.php?module=Cataleg&type=user&func=view";
    }
    
    // Verifica la correció de la selecció i crida la funció que farà realment la importació
    function importa(){
    // Verificar que totes les activitats seleccionades tenen informada la prioritat/subprioritat
        var a = 0;
        $$("input:checked").each(function(){
            if ($$(this).val() == ""){
            a = a+1;
            }
        });
        if (a == 0) {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.importForm.action="index.php?module=Cataleg&type=user&func=import";
        document.importForm.submit();
        } else {
            if (a == 1) {
                alert('{{gt text="Reviseu la selecció. Hi ha "}}'+a+'{{gt text=" activitat marcada per a importar sense prioritat assignada (\'Sense assignar\'). Només es poden importar activitats que informin de la prioritat o subprioritat."}}');
            } else {
                alert('{{gt text="Reviseu la selecció. Hi ha "}}'+a+'{{gt text=" activitats marcades per a importar sense prioritat assignada (Sense assignar). Només es poden importar activitats que informin de la prioritat o subprioritat."}}');
            }
        }
    }
</script>