{pageaddvar name='javascript' value='jQuery'}
{pageaddvar name='javascript' value='vendor/bootstrap/js/bootstrap.js'}
{pageaddvar name='javascript' value='vendor/zikula1.4/bootstrap-zikula.js'}
{pageaddvar name='stylesheet' value='vendor/bootstrap/css/bootstrap.css'}
{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large'}</div>
    <h2>{if $edit}{gt text="Edició d'un usuari"}{else}{gt text="Creació d'un nou usuari"}{/if}</h2>

    <form name="nouuser" id="nouuser" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="uid" name="uid" {if $edit}value="{$user.uid}"{/if}>
        <fieldset>
            <legend style="margin-bottom:0px">{gt text='Informació de l\'usuari'}</legend>
            <div class="z-formrow">
                <label for="uname">{gt text="Nom d'usuari"}</label>
                <input type="text" id="uname" name="uname" size="25" maxlength="25" {if $edit}value="{$user.uname}"{/if}/>
            </div>
            <div class="row">
            {if !$edit || ($edit && !$user.pw)}
                <div class="col-xs-6">
                    <input id="user_setpass_no" type="radio" name="setpass" value="0" checked="checked"/>
                    <label for="user_setpass_no">{gt text="L'usuari només pot entrar per LDAP"}</label>
                </div>
                <div class="col-xs-6">
                    <input id="user_setpass_yes" type="radio" name="setpass" value="1"/>
                    <label for="user_setpass_yes">{gt text="Crea una contrasenya manual"}</label>
                </div>
                <input type="hidden" name="prev_pass" value="0"/>
            {else}
                <div class="col-xs-4">
                    <input id="user_setpass_no" type="radio" name="setpass" value="0" checked="checked"/>
                    <label for="user_setpass_no">{gt text="L'usuari manté la seva contrasenya manual assignada"}</label>
                </div>
                <div class="col-xs-4">
                    <input id="user_setpass_yes" type="radio" name="setpass" value="1"/>
                    <label for="user_setpass_yes">{gt text="Modifica la contrasenya existent"}</label>
                </div>
                <div class="col-xs-4">
                    <input id="user_setpass_cl" type="radio" name="setpass" value="2"/>
                    <label for="user_setpass_cl">{gt text="Esborra la contrasenya manual, imposant el mètode LDAP"}</label>
                </div>
                <input type="hidden" name="prev_pass" value="1"/>
            {/if}
            </div>
            <div data-switch="setpass" data-switch-value="1">
                <div class="z-formrow">
                    <label for="password">{gt text="Nova contrasenya"}</label>
                    <input type="password" id="password" name="password" size="25" maxlength="25" />
                </div>
                <div class="z-formrow">   
                    <label for="rpassword">{gt text="Repeteix la nova contrasenya"}</label>
                    <input type="password" id="rpassword" name="rpassword" size="25" maxlength="25" />
                </div>
                <div style="text-align:center;">
                    {gt text="Desemmascara"}<input type="checkbox" id="unmask" onchange="javascript:un_mask();" style="margin-left:6px;vertical-align:middle">
                    <span  style="padding-left:40px">{gt text="Imposa el canvi de contrasenya"}<input type="checkbox" id="changeme" name="changeme" value='1' style="margin-left:6px;vertical-align:middle"{if isset($user.__ATTRIBUTES__._Users_mustChangePassword) && $user.__ATTRIBUTES__._Users_mustChangePassword}checked{/if}></span>
                </div>
            </div>
            <p class="z-informationmsg">{gt text="El 'Nom d'usuari' i la 'Contrasenya' són les dades de connexió a Sirius. Els usuaris sense contrasenya assignada podran validar-se per LDAP."}</p>
            <div class="z-formrow">
                <label for="iw_nom">{gt text="Nom"}</label>
                <input type="text" id="iw_nom" name="iw_nom" size="25" maxlength="25" {if $edit}value="{$user.iw.nom}"{/if}/>
            </div>
            <div class="z-formrow">
                <label for="iw_cognom1">{gt text="Cognoms"}</label>
                <input type="text" id="iw_cognom1" name="iw_cognom1" size="25" maxlength="25" {if $edit}value="{$user.iw.cognom1}"{/if}/>
            </div>
            <!--       Inicialment separàvem els dos cognoms, seguint la guia del iwusers.
            <div class="z-formrow">
                <label for="iw_cognom2">{gt text="Segon cognom"}</label>
                <input type="text" id="iw_cognom2" name="iw_cognom2" size="25" maxlength="25" {if $edit}value="{$user.iw.cognom2}"{/if}/>
            </div>
            -->
            <div class="z-formrow">
                <label for="email">{gt text="email"}</label>
                <input type="text" id="email" name="email" size="60" maxlength="60" {if $edit}value="{$user.email}"{/if}/>
            </div>
            <input type="hidden" name="groups[0]" value ="{$grupCat.Sirius}">
            <fieldset>
                <legend style="margin-bottom:0px">GTAF</legend>
                {assign var='iwcode' value=$user.iw.code}
                <label>{gt text="Codi GTAF actual:"} <span style="background-color:white;padding:2px;border:1px solid grey">{if $user.iw.code}{$user.iw.code} - {if $gtafInfo.entities.$iwcode.nom}{$gtafInfo.entities.$iwcode.nom}{else}{gt text="Codi desconegut"}{/if}{else}{gt text="Cap codi registrat"}{/if}</span></label>
                <div class="row">
                    <div class="col-xs-4">
                        <input id="user_setcode_no" type="radio" name="setcode" value="0" checked="checked"/>
                        <label for="user_setcode_no">{gt text="No modifiquis la informació GTAF de l'usuari"}</label>
                    </div>
                    <div class="col-xs-4">
                        <input id="user_setcode_yes" type="radio" name="setcode" value="1"/>
                        <label for="user_setcode_yes">{gt text="Tria una Entitat GTAF per a l'usuari"}</label>
                    </div>
                    <div class="col-xs-4">
                        <input id="user_setcode_yesm" type="radio" name="setcode" value="2"/>
                        <label for="user_setcode_yesm">{gt text="Introdueix el codi GTAF manualment"}</label>
                    </div>
                </div>
                <div data-switch="setcode" data-switch-value="1">
                    <div class="z-formrow">
                        <label for="gtafGroup">{gt text="Grup d'entitats GTAF"}</label>
                        <select id="gtafGroup" name="gtafGroup"onchange="javascript:dropdownlist(document.nouuser.gtafGroup.options[document.nouuser.gtafGroup.selectedIndex].value);"/>
                            <option value="">{gt text="Tria un grup"}</option>
                            {foreach from=$gtafInfo.groups item='group'}
                                <option value="{$group.gtafGroupId}">{$group.nom}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="z-formrow">   
                        <label for="gtafEntity">{gt text="Entitat GTAF"}</label>
                        <select id="gtafEntity" name="gtafEntity" onchange="javascript:writecode()"/>
                        </select>
                    </div>
                    <input type="hidden" id="iwcode_s" name="iwcode_s" readonly size="4" maxlength="4"/>
                </div>
                <div data-switch="setcode" data-switch-value="2">
                    <div class="z-formrow">
                        <label for="iwcode_m">{gt text="Codi GTAF"}</label>
                        <input type="text" id="iwcode_m" name="iwcode_m" size="4" maxlength="4" onblur="javascript:checkcode();"/>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend style="margin-bottom:0px"><a id="cataleg_users_tipus" class="z-toggle-link" href="javascript:void(0)">{gt text="Tipus d'usuari"}</a></legend>
                <div id="cataleg_users_tipus_details" style="display:none">
                    <div><input type="radio" name="groups[1]" value="{$grupCat.Personals}" {if !$edit || isset($user.gr.Personals)}checked{/if}>  {gt text="Usuari personal"}</div>
                    <div><input type="radio" name="groups[1]" value="{$grupCat.Generics}" {if isset($user.gr.Generics)}checked{/if}>  {gt text="Usuari genèric"}</div>
                    <p class="z-informationmsg">{gt text="Els usuaris genèrics no poden modificar el seu perfil"}</p>
                </div>
            </fieldset>
            <fieldset>
                <legend style="margin-bottom:0px"><a id="cataleg_users_gestform" class="z-toggle-link" href="javascript:void(0)">{gt text="Rols especials"}</a></legend>
                <div id="cataleg_users_gestform_details" style="display: none">
                    <div class="row">
                    <div class="col-xs-4">
                        <input type="radio" id="gestform" name="groups[2]" value="{$grupCat.Gestform}" {if !$edit || isset($user.gr.Gestform)}checked{/if}> {gt text="Usuari gestor de formació"}
                    </div>
                    <div class="col-xs-4">
                        <input type="radio" name="groups[2]" value="{$grupCat.LectorsCat}" {if isset($user.gr.LectorsCat)}checked{/if}> {gt text="Només lector del catàleg"}
                    </div>
                    <div class="col-xs-4">
                        <input type="radio" id='caprol'name="groups[2]" value="" {if $edit && !isset($user.gr.Gestform) && !isset($user.gr.LectorsCat)}checked{/if}> {gt text="Cap rol general de formació"}
                    </div>
                    </div>
                    <div class="row">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-xs-12"><input type="checkbox" id="gestors" style="-webkit-appearance:radio;-moz-appearance:radio" {if $gestor !=1} readonly onclick="return false" onkeydown="return false"{/if} name="groups[]" value="{$grupCat.Gestors}" {if isset($user.gr.Gestors)}checked{/if}>  {gt text="Gestor de Sirius"}</div>
                    </div>
                </div>
            </fieldset>
            <fieldset><legend style="margin-bottom:0px">{gt text="Rols addicionals"}</legend>
<!--                <div><input type="checkbox" id="lectors" style="-webkit-appearance:radio;-moz-appearance:radio;" name="groups[]" value="{$grupCat.LectorsCat}" {if isset($user.gr.LectorsCat)}checked{/if}>  {gt text="Lector del Catàleg"}</div>-->
                <div><input type="checkbox" id="editors" style="-webkit-appearance:radio;-moz-appearance:radio;" readonly onclick="return false" onkeydown="return false" name="groups[]" value="{$grupCat.EditorsCat}" {if $edit}{if isset($user.gr.EditorsCat)}checked{/if}{/if}>  {gt text="<b>Editor del Catàleg.</b> Tria directament les unitats on pot editar"}</div>
                <div style="margin-left:30px">
                    {foreach from=$allGroupsUnits item='unit' key='key'}
                        <div><input type="checkbox" style="-webkit-appearance:radio;-moz-appearance:radio;" class="unitat" name="groups[]" value="{$unit.gid}" {if isset($user.uni.$key)}checked{/if} onchange="javascript:chgunit();">  {$unit.name}</div>
                    {/foreach}
                </div>
                <div><input type="checkbox" id="odissea" style="-webkit-appearance:radio;-moz-appearance:radio;" name="groups[]" value="{$grupCat.Odissea}" {if isset($user.gr.Odissea)}checked{/if}>  {gt text="Administrador d'Odissea"}</div>
                <div><input type="checkbox" id="cert" style="-webkit-appearance:radio;-moz-appearance:radio;" name="groups[]" value="{$grupCat.Cert}" {if isset($user.gr.Cert)}checked{/if}>  {gt text="Accés a Certificació"}</div>
                <div><input type="checkbox" id="ga" style="-webkit-appearance:radio;-moz-appearance:radio;" name="groups[]" value="{$grupCat.gA}" {if isset($user.gr.gA)}checked{/if}>  {gt text="Grup addicional A"}</div>
                <div><input type="checkbox" id="gb" style="-webkit-appearance:radio;-moz-appearance:radio;" name="groups[]" value="{$grupCat.gB}" {if isset($user.gr.gB)}checked{/if}>  {gt text="Grup addicional B"}</div>
            </fieldset>
            <div id="botons" class="z-buttonrow z-buttons z-center">
                {if $edit}
                    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Edita l'usuari"}">{gt text="Edita"}</button>
                {else}
                    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:save();" title="{gt text="Crea l'usuari"}">{gt text="Crea"}</button>                    
                {/if}
                <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
            </div>
        </fieldset>           
    </form>
</div>
<script src="javascript/jquery/jquery-1.7.0.js"></script>
<script type="text/javascript"> var $jq=jQuery.noConflict(true);</script>
<script type="text/javascript"  language="javascript">
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=usersgest";
}
function save(){
    if (document.getElementById('uname').value.length == 0 ) {
        alert('{{gt text="S\'ha d\'informar del nom d\'usuari"}}');
    } else if (document.getElementById('iw_nom').value == ''){
        alert('{{gt text="S\'ha d\'informar del nom de l\'usuari"}}');
    } else if (document.getElementById('iw_cognom1').value == ''){
        alert('{{gt text="S\'ha d\'informar del primer cognom de l\'usuari"}}');
    } else if (document.getElementById('email').value == ''){
        alert('{{gt text="S\'ha d\'informar de l\'email de l\'usuari"}}');
    } else if (document.getElementById('iw_cognom1').value == ''){
        alert('{{gt text="S\'ha d\'informar dels cognoms de l\'usuari"}}');
    } else if (document.getElementById('email').value.search(/\S+@\S+\.\S+/)==-1) {
        alert('{{gt text="L\'email introduit no és vàlid"}}');
    // Ara el sistema deixa crear l'usuari sense contrasenya, ja que disposem de la validació xtec-ldap
    //} else if ((document.getElementById('uid').value.length == 0) && (document.getElementById('password').value == '')) {
    //    alert('{{gt text="En crear un usuari s\'ha de definir la seva contrasenya inicial"}}');
    } else if (document.getElementById('password').value != document.getElementById('rpassword').value) {
        alert('{{gt text="Les dues contrasenyes no són iguals"}}');
    } else if ((document.getElementById('password').value.length != 0) && (document.getElementById('password').value.length < {{$minpass}})) {
        alert('{{gt text="La contrasenya ha de tenir almenys"}} {{$minpass}} {{gt text="caràcters"}}');
    } else if (document.getElementById('password').value == document.getElementById('uname').value) {
        alert('{{gt text="La contrasenya no pot coincidir amb el nom d\'usuari"}}');
//    } else if(!document.getElementById('gestform').checked){
//        alert('{{gt text="Aquest usuari es desarà sense tenir el rol general de 'Gestor de formació'"}}');
        // Evitar el submit múltiple. Desactivar botó
//        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
//        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
//        document.nouuser.action="index.php?module=Cataleg&type=admin&func=addeditUser";
//        document.nouuser.submit();
    } else {
        // Evitar el submit múltiple. Desactivar botó
        document.getElementById("caprol").name="caprol";
        if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
        if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
        document.nouuser.action="index.php?module=Cataleg&type=admin&func=addeditUser";
        document.nouuser.submit();
    }
    
}
function un_mask(){
         if (document.getElementById('unmask').checked == true) {
            document.getElementById('password').type="text";
             document.getElementById('rpassword').type="text";
        } else {
            document.getElementById('password').type="password";
            document.getElementById('rpassword').type="password";
      }
    
}
function chgunit(){
    if ($jq(".unitat").is(":checked")) {
        $jq("#editors").attr("checked","checked");
    } else {
        $jq("#editors").removeAttr("checked");
    }
    
}
function dropdownlist(listindex){
    document.nouuser.iwcode_s.value = '';
    document.nouuser.gtafEntity.options.length = 0;
    switch (listindex) {
        {{foreach from=$gtafInfo.groups item='group'}}
            case "{{$group.gtafGroupId}}":
                document.nouuser.gtafEntity.options[document.nouuser.gtafEntity.options.length]=new Option("{{gt text="Tria l'entitat GTAF"}}","");
                {{foreach from=$group.entities item='entity'}}
                    document.nouuser.gtafEntity.options[document.nouuser.gtafEntity.options.length]=new Option("{{$entity.gtafEntityId}} - {{$entity.nom}} - [{{$entity.tipus}}]","{{$entity.gtafEntityId}}");
                {{/foreach}}
                break;
        {{/foreach}}
    }
    return true;
}
function writecode(){
    {{assign var=codigtaf value=document.nouuser.gtafEntity.value}}
    document.nouuser.iwcode_s.value = {{$codigtaf}};
}
function checkcode(){
    {{assign var="ent_code" value=document.nouuser.iwcode_m.value}}
    var entities_code = {{$gtafInfo.entities_code|@json_encode}}
    if (jQuery.inArray(document.nouuser.iwcode_m.value,entities_code) == -1) {
        alert("{{gt text="El codi GTAF introduit no existeix a la base de dades d'entitats GTAF de Sirius"}}");
    }
}
/*Event.observe(window,
              'load',
              cataleg_users_checks,
              false);

function cataleg_users_checks()
{
    if($('cataleg_users_gestform_collapse')) {
        cataleg_users_gestform_check();
    }
}
function cataleg_users_gestform_check()
{
    $('cataleg_users_gestform_collapse').observe('click', cataleg_users_gestform_click);
    $('cataleg_users_gestform_collapse').addClassName('z-toggle-link');
    cataleg_users_gestform_click();
}
function cataleg_users_gestform_click()
{
    if ($('cataleg_users_gestform_details').style.display != 'none') {
        Element.removeClassName.delay(0.9, $('cataleg_users_gestform_collapse'), 'z-toggle-link-open');
    } else {
        $('cataleg_users_gestform_collapse').addClassName('z-toggle-link-open');
    }
    switchdisplaystate('cataleg_users_gestform_details');
}*/
jQuery( ".z-toggle-link" ).click(function() {
    var tId = jQuery(this).attr('id');
    jQuery( "#"+tId+"_details" ).toggle( "slow", function() {
        jQuery( "#"+tId ).toggleClass( "z-toggle-link-open" );
    });
});
</script>