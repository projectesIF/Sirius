{*include file="user/Cataleg_user_menu_cerca.tpl"*}

{*<h1>{gt text="Cerca d'activitats per zona (Delegació territorial)"} - {$cataleg}</h1>*}

<form id="cataleg_user_cerca_zona" class="z-form" action="{modurl modname='Cataleg' type='user' func='cercazona' catId=$catId}" method="post" >
    <input type="hidden" name="consultat" value="1"></input>
    {*<input type="hidden" name="catId" value="{$catId}"></input>*}

    <fieldset>
        <legend style="font-weight: bold; color: #6A6A6A;"> Cerca per zona</legend>

        {gt text="Delegació territorial"}     
        <select id="dt" name="dt">  
            {html_options options=$selecDT}  
        </select>

        <div class="z-buttonrow z-buttons z-center" style="padding-top: 20px;">  
            <button id="cataleg_button_submit" class="z-btgreen" type="submit" name="Envia" value="1" title="{gt text='Envia consulta'}">{img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Cerca' __title='Cerca' } {gt text='Cerca'}</button>      
            <a id="cataleg_button_netja" href="{modurl modname='Cataleg' type='user' func='cercazona' catId=$catId}" class="z-btblue">{img modname='core' src='agt_reload.png' set='icons/extrasmall' __alt='Neteja' __title='Neteja'} {gt text='Neteja'}</a>
            <a id="cataleg_button_cancel" href="{modurl modname='Cataleg' type='user' func='cataleg' catId=$catId}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel·la' __title='Cancel·la'} {gt text='Cancel·la'}</a>
        </div>
    </fieldset>

</form>

{* Si s'ha demanat una cerca mostrem resultats*}
{if $consultat eq 1}
    <div id="resultatszona"> {include file="user/Cataleg_user_resultats_zona.tpl"}</div>
{/if}