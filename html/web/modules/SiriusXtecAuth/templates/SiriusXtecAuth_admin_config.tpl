{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text="Administració del mòdul"}</h3>
</div>
{$login_redirect}<br>
   <form name="SiriusXtecAuthConf" id="SiriusXtecAuthConf" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <fieldset>
           <legend>{gt text='Configuració de la funcionalitat del mòdul'}</legend>
           <div class="z-formrow">
               <label for="ldap_active">{gt text="Ldap activat:"}</label>
               <input type="checkbox" name="ldap_active" value="1" {if $MVars.ldap_active} checked="true"{/if}>
           </div>
           <div class="z-formrow">
               <label for="users_creation">{gt text="Crea usuaris nous:"}</label>
               <input type="checkbox" name="users_creation" value="1" {if $MVars.users_creation} checked="true"{/if}>
           </div>
           <div class="z-formrow">
               <label for="new_users_activation">{gt text="Nous usuaris actius:"}</label>
               <input type="checkbox" name="new_users_activation" value="1" {if $MVars.new_users_activation} checked="true"{/if}>
           </div>
           <div class="z-formrow">
               <label for="iw_write">{gt text="Escriu nom i cognoms en crear l'usuari:"}</label>
               <input type="checkbox" name="iw_write" value="1" {if $MVars.iw_write} checked="true"{/if}>
               {gt text="Nota: Només escriurà nom i cognoms si el mòdul IWusers està actiu"}
           </div>
           <div class="z-formrow">
               <label for="iw_lastnames">{gt text="Separa els cognoms en dos camps:"}</label>
               <input type="checkbox" name="iw_lastnames" value="1" {if $MVars.iw_lastnames} checked="true"{/if}>
               {gt text="Nota: Si no s'activa escriurà els cognoms en el camp 'cognom1'"}
           </div>
           <div class="z-formrow">
               <label for="new_users_groups[]">{gt text="Grups assignats als nous usuaris"}</label>
               <select name="new_users_groups[]" multiple='multiple'>
                   {foreach item='grup' key='gid' from=$allGroups}
                       <option value={$gid} {if $grup.sel}selected{/if}>{$grup.name}</option>
                   {/foreach}
               </select>
           </div>
        </fieldset>
        <fieldset>
            <legend>{gt text='Configuració de la connectivitat ldap'}</legend>
            <div class="z-formrow">
               <label for="ldap_server">{gt text="Servidor ldap:"}</label>
               <input type="text" name="ldap_server" value="{$MVars.ldap_server}">
           </div>
           <div class="z-formrow">
               <label for="ldap_basedn">{gt text="Base dn:"}</label>
               <input type="text" name="ldap_basedn" value="{$MVars.ldap_basedn}">
           </div>
           <div class="z-formrow">
               <label for="ldap_searchattr">{gt text="Atribut de cerca:"}</label>
               <input type="text" name="ldap_searchattr" value="{$MVars.ldap_searchattr}">
           </div>
        </fieldset>
        <fieldset>
            <legend>{gt text='Connectivitat amb aplicatius XTEC de gestió'}</legend>
            <div class="z-formrow">
               <label for="loginXtecApps">{gt text="Valida a gtaf i e13 en validar-se:"}</label>
               <input type="checkbox" name="loginXtecApps" value="1" {if $MVars.loginXtecApps} checked="true"{/if}>
           </div>
           <div class="z-formrow">
               <label for="logoutXtecApps">{gt text="Tanca la sessió del gtaf i e13 en sortir:"}</label>
               <input type="checkbox" name="logoutXtecApps" value="1" {if $MVars.logoutXtecApps} checked="true"{/if}>
           </div>
		   {if $login_redirect neq 1}
		       <p class="z-informationmsg">{gt text="El tancament de sessions de gtaf i e13 és possible, ja que en el mòdul 'Users', el paràmetre 'WCAG-compliant log-in and log-out' no està operatiu. Només caldrà ajustar el valor del temps de la pantalla de tancament per optimitzar l'ús."}</p>
		   {else}
			   <p class="z-warningmsg">{gt text="El tancament de sessions de gtaf i 13 no és possible. Per poder-lo utilitzar cal habilitar al mòdul 'Users' el paràmetre 'WCAG-compliant log-in and log-out'."}</p>
		   {/if}
           <div class="z-formrow">
               <label for="gtafProtocol">{gt text="Protocol de la URL del gtaf:"}</label>
               <input type="text" name="gtafProtocol" value="{$MVars.gtafProtocol}">
           </div>
            <div class="z-formrow">
               <label for="gtafURL">{gt text="URL del gtaf:"}</label>
               <input type="text" name="gtafURL" value="{$MVars.gtafURL}">
           </div>
           <div class="z-formrow">
               <label for="e13Protocol">{gt text="Protocol de la URL de l'e13:"}</label>
               <input type="text" name="e13Protocol" value="{$MVars.e13Protocol}">
           </div>
           <div class="z-formrow">
               <label for="e13URL">{gt text="URL de l'e13:"}</label>
               <input type="text" name="e13URL" value="{$MVars.e13URL}">
           </div>
		   <div class="z-formrow">
               <label for="loginTime">{gt text="Temps (ms) per la pantalla de validació:"}</label>
               <input type="text" name="loginTime" value="{$MVars.loginTime}">
           </div>
		   <div class="z-formrow">
               <label for="logoutTime">{gt text="Temps (ms) per la pantalla de tancament:"}</label>
               <input type="text" name="logoutTime" value="{$MVars.logoutTime}">
           </div>
        </fieldset>       
    </form>
    <div id="botons" class="z-buttonrow z-buttons z-center">
        <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:desa();" title="{gt text="Desa"}">{gt text="Desa"}</button>
        <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
    </div>
    
<script>
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php";
}
function desa(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    document.SiriusXtecAuthConf.action="index.php?module=SiriusXtecAuth&type=admin&func=updateConfig";
    document.SiriusXtecAuthConf.submit();
}
</script>
