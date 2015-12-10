{*include file="user/Cataleg_user_menu_cerca.tpl"*}

{*<h1>{gt text="Cerca d'activitats en centre"} - {$cataleg}</h1>*}

{*<div class="z-informationmsg">
Ompliu els camps amb les característiques que cerqueu <br />
Tots els camps són opcionals
</div>
*}
<form id="cataleg_user_cerca" class="z-form" action="{modurl modname='Cataleg' type='user' func='cercacentre' catId=$catId}" method="post" >
    <input type="hidden" name="consultat" value="1"></input>
    {*<input type="hidden" name="catId" value="{$catId}"></input>*}
       
    <fieldset>
        <legend style="font-weight: bold; color: #6A6A6A;"> Cerca per codi </legend>
        <div class="z-informationmsg">
            Els codis han d'anar separats per comes    
        </div>


        <div style="padding-top: 2px;">
            {gt text="Codi/s de centre"}
            <input name="centres" type="text" size="100" maxlength="355" value="{$centres}" /> 
        </div>


        {*
        <div style="padding-top: 24px;">
        {gt text="Centre"}
        <select name=selcentre>
        {html_options options=$selectcentres selected=$selcentre}
        </select>
        </div>
        *}
        <div class="z-buttonrow z-buttons z-center" style="padding-top: 20px;">  
            <button id="cataleg_button_submit" class="z-btgreen" type="submit" name="Envia" value="1" title="{gt text='Envia consulta'}">{img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Cerca' __title='Cerca' } {gt text='Cerca'}</button>      
            <a id="cataleg_button_neteja" href="{modurl modname='Cataleg' type='user' func='cercacentre' catId=$catId}" class="z-btblue">{img modname='core' src='agt_reload.png' set='icons/extrasmall' __alt='Neteja' __title='Neteja'} {gt text='Neteja'}</a>
            <a id="cataleg_button_cancel" href="{modurl modname='Cataleg' type='user' func='cataleg' catId=$catId}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel·la' __title='Cancel·la'} {gt text='Cancel·la'}</a>
        </div>
        
    </fieldset>

    {*
    {gt text="Participa en la gestió"}
    <select name=gestor>
    {html_options options=$gestors}
    </select>
    <br />   
    <br />  
    *}
</form>

{* Si s'ha demanat una cerca mostrem resultats*}
{if $consultat eq 1}
    <div id="resultatscentre"> {include file="user/Cataleg_user_resultats_centre.tpl"}</div>
{/if}