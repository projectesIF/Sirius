{ajaxheader modname=Cataleg filename=Cataleg.js}
{pageaddvar name='javascript' value='jQuery'}
{pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació :: Fitxa d'activitat"}
{insert name='getstatusmsg'}
<!-- la variable show indica si s'ha de tornar a la funció show (edició de la fitxa 
     des de la seva visualizació                                                -->
{if !isset($show)}{assign  var='show' value='0'}{/if}

{if ($unitats)}
    <div class="usercontainer"> 
        <div class="userpageicon">{img modname='core' src='windowlist.png' set='icons/large'}</div>
        <h2 style="text-align:left">{gt text="Fitxa d'activitat"}:&nbsp;{$cataleg.nom}</h2>
         <br />
        <form id="frmActivitat" class="z-form" name="frmActivitat" action="" method="post" enctype="application/x-www-form-urlencoded">
            {*<div class="userpageicon">{img modname='core' src='windowlist.png' set='icons/large'}</div>*}
            <input type="hidden" name="actId" value="{$actId}"></input>
            <input type="hidden" name="catId" id="catId" value={$cataleg.catId}></input>
            <input type="hidden" name="back" value="{$back}"></input>
            <fieldset>                
                <div id="eix"> <span style="font-family:helvetica">{include file="user/Cataleg_user_eix.tpl"}</span></div>                
                <div id="selectorPri">{include file="user/Cataleg_user_selectPrioritat.tpl"}</div>
                <div id="selectorSubPri">{include file="user/Cataleg_user_selectSubPrioritat.tpl"}</div>                
                <fieldset id="title">
                    <legend>{gt text="Títol de l'activitat"}</legend>                            
                    <input style="font-size:1.2em; font-weight:bold" id="titol" class="z-text" name="titol" maxlength="255" size="100" type="text" value="{$info.titol}"></input>
                    <br /><br />
                    {gt text="Tipus GTAF "}<input id="tGTAF" name="tGTAF" maxlength="4" size="4" value="{$info.tGTAF}" type="text" />&nbsp;&nbsp;
                    {gt text="Activitat prioritzada"}&nbsp;&nbsp;<input id="prioritzada" class="z-text" name="prioritzada" {if $info.prioritaria} checked="checked"{/if}value=1 type="checkbox"> </input>
                </fieldset>
            </fieldset>     
        {* Persones destinatàries --------------------------------------------------------------------- *}
        {include file="user/Cataleg_user_persDest.tpl"}
        {* GESTIÓ DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++++++++++++++ *}
        <fieldset id="fGestio">
            <legend>{gt text="Gestió"}</legend>
            <div id="t_ops">
                <table style="margin-left: auto; margin-right: auto; width:50%" border='0'> {* style="text-align:center" *}
                    <tr>
                        {* D'entrada no hi ha cap selecció -> sel ="" *}
                        <td style="width:33%"><input type="radio" id="op1" name="def" onchange="javascript:changeOps(1)"> {gt text="Servei educatiu"} </input></td>
                        <td style="width:33%"><input type="radio" id="op2" name="def" onchange="javascript:changeOps(2)"> {gt text="Servei territorial"}</input></td>
                        <td style="width:33%"><input type="radio" id="op3" name="def" onchange="javascript:changeOps(3)"> {gt text="Unitat"}</input></td>
                    </tr>
                </table><br>
            </div>
            {* llistes desplegables per seleccionar entitats de gestió de cada element a gestionar *}
            <div id="elemGestio" style="position:relative">
                {include file="user/Cataleg_user_elemGestio.tpl"}
            </div>
        </fieldset>    
        {* /GESTIÓ DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
        {* UNITAT RESPONSABLE DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++ *}
        <div id ="uniResp">
            {*include file="user/Cataleg_user_uniResp.tpl"*}
            <fieldset>
                <legend>{gt text="Unitat responsable"}</legend>
                <select id="uniResp" name="uniResp">
                    {foreach item="unitat" from=$unitats}
                        <option value="{$unitat.uniId}" {if $unitat.uniId == $info.uniId}selected="selected"{/if}>{$unitat.nom}</option>
                    {/foreach}
                </select>    
                <input type="hidden" name="nPersContacte" id="nPersContacte" value=1> </input>
                <table width='90%'>
                    <tr><td width=95%>                    
                            <TABLE class="z-datatable" ID="contactes" CELLPADDING=0 BORDER=0>
                                <thead>
                                    <tr>
                                        <th width=65%>{gt text="Persona de contacte"}</th>
                                        <th width=25%>{gt text="Correu"}</th>
                                        <th width=10%>{gt text="Telèfon/ext."}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {if ($info.contactes|@count)}
                                        {foreach item='c' from=$info.contactes name="i"}
                                            <tr>
                                                <td width=62%><input style="width:98%" maxlenght = "150" type='text' name="contacte[{$smarty.foreach.i.iteration}][pContacte]" id='pcontatcte1' value="{$c.pContacte}"></input></td>
                                                <td width=25%><input size="35" maxlenght = "50" type='text' name="contacte[{$smarty.foreach.i.iteration}][email]" id='email1' value="{$c.email}"></input></td>
                                                <td width=13%><input size="15" maxlenght = "20" type='text' name="contacte[{$smarty.foreach.i.iteration}][telefon]" id='telf1' value="{$c.telefon}"></input></td>
                                            </tr>
                                        {/foreach}
                                    {else}
                                        <tr>
                                            <td width=62%><input style="width:98%" maxlenght = "150" type='text' name="contacte[1][pContacte]" id='pcontatcte1'></input></td>
                                            <td width=25%><input size="35" maxlenght = "50" type='text' name="contacte[1][email]" id='email1'></input></td>
                                            <td width=13%><input size="15" maxlenght = "20" type='text' name="contacte[1][telefon]" id='telf1'></input></td>
                                        </tr>
                                    {/if}
                                </tbody>
                            </TABLE>
                        </td>
                        <td width=95% class="z-bottom z-left" >
                            <a class="z-icon-es-add" id="mesp" onclick="javascript:appendRow(this.form);" title="{gt text="Afegir una fila"}" href="javascript:void(0);"></a>
                            <a class="z-icon-es-remove" id="menysp" style="display:none;" onclick="javascript:removeRow(this.form);" title="{gt text="Eliminar la darrera fila"}" href="javascript:void(0);"></a>                    
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        {* /UNITAT RESPONSABLE DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++ *}
        {* CAMP PER INFORMAR DE LA NO OFERTA FINAL DE L'ACTIVITAT +++++++++++++++++++ *}
             <div id ="noOfert">
                <fieldset>
                    <legend>{gt text="Informació de l'anul·lació de l'activitat"}</legend>
                    {gt text="Activitat finalment no ofertada"}&nbsp;&nbsp;<input type="checkbox" name="activa" value=0 {if isset($info.activa) && $info.activa eq 0} checked="checked"{/if} style="vertical-align:middle">
                    <p class="z-informationmsg">{gt text="Si després de la inclusió de l'activitat en el catàleg, finalment no es pot ofertar, es pot marcar aquest camp i, així, informar d'aquest fet."}</p>
                </fieldset>
            </div>
        {* /CAMP PER INFORMAR DE LA NO OFERTA FINAL DE L'ACTIVITAT ++++++++++++++++++ *}
        {* CAMPS D'INTERCANVI D'INFORMACIÖ ENTRE EDITORS I GESTORS ++++++++++++++++++ *}
        <div id="observacions">
            {*include file="user/Cataleg_user_anotacions.tpl"*}           
            <div class="z-block z-blockposition-center z-bkey-myrole z-bid-8">
                <h4 class="z-block-title">{gt text="Missatges entre persones editores i validadores"}</h4>
                <div class="z-block-content"> 
                    {if ($info.estat == 'Cataleg_Constant::MODIFICADA'|constant) && is_null($info.dataModif)}
                       {gt text="Informar de la modificació al bloc de novetats"}&nbsp;&nbsp; <input type=checkbox id="novetat" name="novetat" value=1> </input> <br /><br />
                    {/if}
                    {if ($info.dataModif)}
                       {gt text="Esborrar la data de modificació per ocultar-la del bloc de novetats"}&nbsp;&nbsp; <input type=checkbox id="eraseMod" name="eraseMod" value=1> </input> <br /><br />                    
                    {/if}
                    <h4 style="color:#7f010a">{gt text="Aquestes observacions són de caràcter intern i no es mostren al catàleg"}</h4><br />
                    {if $level == 'edit'}
                        <h4>{gt text="Observacions per a les persones validadores"}</h4>
                        <textarea id="obs_editor" style="background-color:#99ff99" name="obs_editor" rows="5" cols="100">{$info.obs_editor}</textarea><br /><br />
                        {if (isset($info.actId) && (strlen($info.obs_validador)>0))}        
                            <h4>{gt text="Observacions per a les persones editores"}</h4>
                            <div style="background-color:orange">{$info.obs_validador|nl2br}</div>
                            <textarea id="obs_validador" style="display:none" name="obs_validador">{$info.obs_validador}</textarea>
                        {/if}
                    {else}            
                        {if (isset($info.actId) && (strlen($info.obs_editor)>0))}
                            <h4>{gt text="Observacions per a les persones validadores"}</h4>
                            <div style="background-color:orange">{$info.obs_editor|nl2br}</div><br />
                            <textarea id="obs_editor" style="display:none" name="obs_editor">{$info.obs_editor}</textarea>
                        {/if}
                        <h4>{gt text="Observacions per a les persones editores"}</h4>
                        <textarea id="obs_validador" style="background-color:#99ff99" name="obs_validador" rows="5" cols="100">{$info.obs_validador}</textarea>
                    {/if}                        
                </div>
            </div>            
        </div>           
        {* /CAMPS D'INTERCANVI D'INFORMACIÖ ENTRE EDITORS I GESTORS ++++++++++++++++++ *}
        <div id="botons" class="z-buttonrow z-buttons z-center">            
            {* Només drets d'edició *}            
            {if $level == 'edit'}
                {if (isset($actId))}                   
                    {if (($info.estat == 'Cataleg_Constant::ESBORRANY'|constant) || ($info.estat == 'Cataleg_Constant::PER_REVISAR'|constant ))}
                        <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:envia();" title="{gt text="Desa la fitxa d'activitat i torna a la llista d'activitats."}">{gt text="Desa"}</button>                    
                        <button id="btn_send" class="z-bt-archive" type="button" onclick="javascript:envia(1);" title="{gt text="Desa els canvis i envia la fitxa per a la seva publicació."}">  {gt text="Envia"}</button>                     
                    {elseif ($info.estat=='Cataleg_Constant::ENVIADA'|constant)}
                        <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:envia();" title="{gt text="Desa la fitxa d'activitat i torna a la llista d'activitats."}">{gt text="Desa"}</button>                    
                    {elseif (($info.estat=='Cataleg_Constant::VALIDADA'|constant) || ($info.estat=='Cataleg_Constant::MODIFICADA'|constant))}
                        <button id='btn_save' class="z-bt-modify" type="button" onclick="javascript:envia(4);" title="{gt text="Desa els canvis i publica la fitxa modificada."}">  {gt text="Modifica"}</button> 
                        {*<button class="z-bt-save" type="button" onclick="javascript:envia();" title="{gt text="Desar la fitxa d'activitat i tornar a la llista d'activitats."}">{gt text="Desar"}</button>*}
                    {/if}
                {else}
                    {* La fitxa és nova activitat *}
                    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:envia();" title="{gt text="Desa la fitxa d'activitat i torna a la llista d'activitats."}">{gt text="Desa"}</button>                    
                    <button id="btn_send" class="z-bt-archive" type="button" onclick="javascript:envia(1);" title="{gt text="Desa els canvis i envia la fitxa per a la seva publicació."}">  {gt text="Envia"}</button>                     
                {/if}
            {else} {* És gestor *}
                <table style="margin:auto">
                    <tr>
                        <td style="padding:2px 20px"><input type="radio" id="estatES" name="estat" value= {"Cataleg_Constant::ESBORRANY"|constant} {if (isset($info.estat) && $info.estat == 'Cataleg_Constant::ESBORRANY'|constant)} checked = "checked"{/if}>{gt text="Esborrany"}</input></td>
                        <td style="padding:2px 20px"><input type="radio" id="estatEN" name="estat" value= {"Cataleg_Constant::ENVIADA"|constant} {if (isset($info.estat) && $info.estat == 'Cataleg_Constant::ENVIADA'|constant)}checked = "checked"{/if}>{gt text="Enviada"}</input></td>
                        <td style="padding:2px 20px"><input type="radio" id="estatPE" name="estat" value= {"Cataleg_Constant::PER_REVISAR"|constant} {if (isset($info.estat) && $info.estat == 'Cataleg_Constant::PER_REVISAR'|constant)}checked = "checked"{/if}>{gt text="Cal revisar"}</input></td>
                        <td style="padding:2px 20px"><input type="radio" id="estatVA" name="estat" value= {"Cataleg_Constant::VALIDADA"|constant} {if (isset($info.estat) && $info.estat == 'Cataleg_Constant::VALIDADA'|constant)}checked = "checked"{/if}>{gt text="Validada"}</input></td>
                        <td style="padding:2px 20px"><input type="radio" id="estatMO" name="estat" value= {"Cataleg_Constant::MODIFICADA"|constant} {if (isset($info.estat) && $info.estat == 'Cataleg_Constant::MODIFICADA'|constant)}checked = "checked"{/if}>{gt text="Modificada"}</input></td>
                        <td style="padding:2px 20px"><input type="radio" id="estatAN" name="estat" value= {"Cataleg_Constant::ANULLADA"|constant} {if (isset($info.estat) && $info.estat == 'Cataleg_Constant::ANULLADA'|constant)}checked = "checked"{/if}>{gt text="Anul·lada"}</input></td>
                    </tr>
                </table>
                <br>
                <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:sendRadioValue();" title="{gt text="Desa la fitxa d'activitat i torna a la llista d'activitats."}">{gt text="Desa"}</button>
            {/if}
            <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel({$show});" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button> 
        </div>
        <!-- estat1 conté el valor de l'acció a realitzar -->
        <input id="estat1" name="estat1" type="hidden" value="" /> 
    </form>
    {if isset($actId)}
    <div class="z-block-content"> 
        <table width='100%'>
             <tr>
                 <td style="padding:2px; width:33%"><span style="font-style:italic">{gt text="Creada per: "}</span><span style="font-weight:bold">{$info.creador}</span>({$info.cr_date})</td>
                 <td style="text-align: center; padding:2px; width:33%"><span style="font-style:italic">{if $info.validador neq ""}{gt text="Validada per: "}</span><span style="font-weight:bold">{$info.validador}</span>({$info.dataVal}){/if}</td>
                 <td style="text-align: right; padding:2px; width:33%"><span style="font-style:italic">{gt text= "Darrera modificació: "}</span><span style="font-weight:bold">{$info.modificador}</span>({$info.lu_date})</td>
             </tr>  
         </table>
    </div>
    {/if}
</div> 
{/if}
{* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ *}
<script type="text/javascript"  language="javascript">
    
function cancel(show){
    document.getElementById("btn_cancel").disabled = true;
    if (show) {
        document.frmActivitat.action="index.php?module=Cataleg&type=user&func=show&actId={{$actId}}";
        //window.location = "index.php?module=Cataleg&type=user&func=show&actId={{$actId}}";
    } else {
        document.frmActivitat.action="index.php?module=Cataleg&type=user&func=view&catId={{$cataleg.catId}}";
    //    window.location = "index.php?module=Cataleg&type=user&func=view&catId={{$cataleg.catId}}";
    }
    document.frmActivitat.submit();
}

function envia(estat) {
    document.getElementById('estat1').value = estat;
    submitForm();
}

function sendRadioValue(){
    // Obtenir el valor del radio button seleccionat
    document.getElementById('estat1').value = jQuery("#frmActivitat input[name='estat']:checked").val(); 
    submitForm();
}

function submitForm(){
    if (jQuery("#frmActivitat input[name='prioritat']:checked").val() > 0) { 
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_send")) document.getElementById("btn_send").disabled = true;
        document.frmActivitat.action="index.php?module=Cataleg&type=user&func=save&show={{$show}}";
        document.frmActivitat.submit();
    } else {
        // No hi prioritat -> no es pot guardar la fitxa
        alert ('{{gt text="No es pot desar una fitxa d\'activitat sense indicar-ne la prioritat."}}');
    }
}

</script>