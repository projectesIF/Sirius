{pageaddvar name='javascript' value='jQuery'}
{pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació :: Cerca d'activitats"}
{ajaxheader modname=Cataleg filename=Cataleg.js}

{include file="user/Cataleg_user_menu_cerca.tpl"}


<h1>{gt text="Cerca d'activitats" } - <a href="{modurl modname='Cataleg' type='user' func='cataleg' catId=$catId}">{$cataleg}</a></h1>

<form id="cataleg_user_cerca" class="z-form" action="javascript:void(0)" method="post" >
    <input type="hidden" name="consultat" value="1"></input>
    <input type="hidden" name="catId" value="{$catId}"></input>

    <fieldset>
     {*   <legend style="font-weight: bold; color: #6A6A6A;"> Cerca </legend> *}

        <div id="eix" style="max-height: 22px;"> {include file="user/Cataleg_user_eix.tpl"}</div>
        <div id="selectorPri" style="overflow:hidden; max-height: 23px;">{include file="user/Cataleg_user_selectPrioritat.tpl"}</div>     
        <div id="selectorSubPri" style="overflow:hidden; max-height: 23px;">{include file="user/Cataleg_user_selectSubPrioritat.tpl"}</div>

        <div style="padding-top: 5px;">
            {gt text="El títol conté"}
            <input name="titol" type="text" size="60" maxlength="255" value="{$titol}" /> <i>(Cerca no sensible a majúscules/minúscules)</i>
        </div>

        <div style="padding-top: 5px;">
            {gt text="Unitat"}
            <select name=unitat>
                {html_options options=$unitats}
            </select>
        </div>

        <div style="padding-top: 4px;">    
            <table>
                <tr>
                    <td style="width: 32%;">
                        {gt text="Modalitat"}
                        <select name="modcurs">
                            {html_options options=$modscurs}
                        </select></td>
                    <td style="width: 28%;">                 
                        {gt text="Presencialitat"}
                        <select name=presencial>
                            {html_options options=$presencials}
                        </select></td>
                    <td style="width: 30%;">
                        {gt text="Lloc"}
                        <select name="lloc">
                            {html_options options=$sstt}
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <div style="padding-top: 5px;">
            <fieldset>
                <legend> Destinataris </legend>
                {counter start=0 print=false assign=n}

                <table>
                    <tr>
                        {foreach from=$destinataris item='desti' key='key' name=u}   
                            {counter print=false}

                            {if $n eq 1 or $n eq 5 or $n eq 9}

                                <td  valign="top" style="width: 22%;">
                                {/if}

                                {if isset($seldestinatari) && in_array($key,$seldestinatari)}
                                    <input type="checkbox" name="destinatari" value="{$key}" checked/> {$desti}<br>
                                {else}
                                    <input type="checkbox" name="destinatari" value="{$key}"/> {$desti}<br>
                                {/if}
                                {if  $n eq 4 or $n eq 8}

                                </td>
                            {/if}

                            {*                  n = {$n} <br>*}
                        {/foreach}       
                    </tr>
                </table>

            </fieldset>
        </div>

        <div class="z-buttonrow z-buttons z-center" style="padding-top: 4px;">  
            {*         <button id="cataleg_button_submit" class="z-btgreen" type="submit" onclick="javascript:cercajs(this.form);" name="Envia" value="1" title="{gt text='Envia consulta'}">{img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Envia' __title='Envia' } {gt text='Envia'}</button>               *}
            <button id="cataleg_button_submit" class="z-btgreen" type="button" onclick="javascript:getCerca(getElementById('cataleg_user_cerca'))" name="Envia" value="1" title="{gt text='Envia consulta'}">{img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Cerca' __title='Cerca' } {gt text='Cerca'}</button>              
            {*<a id="cataleg_button_submit" href="#" class="z-btgreen" onclick="javascript:getCerca(getElementById('cataleg_user_cerca'));" >{img modname='core' src='button_ok.png' set='icons/extrasmall' __alt='Cerca' __title='Cerca'} {gt text='Cerca'}</a>*}
            <a id="cataleg_button_neteja" href="{modurl modname='Cataleg' type='user' func='cerca' catId=$catId}" class="z-btblue">{img modname='core' src='agt_reload.png' set='icons/extrasmall' __alt='Neteja' __title='Neteja'} {gt text='Neteja'}</a>
            <a id="cataleg_button_cancel" href="{modurl modname='Cataleg' type='user' func='cataleg' catId=$catId}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel·la' __title='Cancel·la'} {gt text='Cancel·la'}</a>
        </div>
    </fieldset>

</form>

{* Si s'ha demanat una cerca mostrem resultats*}

<div id="resultats" style="display:none"> {include file="user/Cataleg_user_resultats.tpl"}</div>

