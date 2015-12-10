{nocache}{*include file='user/Cataleg_user_menu.tpl'*}{/nocache}
{insert name='getstatusmsg'}
<div class="userpageicon">{img modname='Cataleg' src='activitats.png'}</div>
{pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació :: Activitats"}
<div><a href="{modurl modname='Cataleg' type='user' func='display' priId=$prioritat.priId }"><span style="text-decoration:underline; ">{gt text="Orientacions"}</span></a></div>
<a id="inici"> </a>
<h3><a href="{modurl modname="Cataleg" type="user" func="cataleg" catId=$prioritat.cataleg.catId}" title="{gt text='Anar al catàleg'}" alt="{gt text='Anar al catàleg'}">
            {$prioritat.cataleg.nom}</a>
</h3>           
<h3 style="filter: alpha(opacity=70); opacity: 0.7;">{gt text="Eix: "}{$prioritat.eix.ordre}. {$prioritat.eix.nom}</h3>
<h3>{gt text="Prioritat: "}{$prioritat.ordre}. {$prioritat.nom} </h3>
<table class="z-datatable">    
    {foreach from=$subprioritats item='subpri' name='subpri'}      
        <thead>
            <tr>
                <td colspan = '5'><h4 class="z-block-title" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">{if isset($subpri.ordreSpr)}{$subpri.ordreSpr|safehtml}. {$subpri.nomCurtSpr|safehtml}{else}{gt text="Sense subprioritat"}{/if} </h4></td>
            </tr>
            <tr>
                <th></a></th>
                <th>{gt text='Títol'}</a></th>
                <th>{gt text='Unitat'}</a></th>
             </tr>
        </thead>
        <tbody>
            {assign var='activitats' value=$subpri.actis}
            {foreach from=$activitats item='acti' name='acti'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td width="2%" style="text-align:center">{if $acti.activa eq 0}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
                    {if $acti.prioritaria}{img modname='cataleg' src='star.png' title= 'Activitat prioritzada' alt='Activitat prioritzada'}{/if}</td>                    
                    <td width="60%"><a href="{modurl modname='Cataleg' type='user' func='show' back='acts' actId=$acti.actId priId=$prioritat.priId}">{if strlen($acti.tGTAF) > 0}{$acti.tGTAF}-{/if}{$acti.titol|safehtml}</a></td>
                    <td width="38%">                          
                    {*    <a class='iframe' href="index.php?module=cataleg&type=user&func=display_unitat&uniId={$acti.uniId|safehtml}">{$acti.nomUni|safehtml}</a>*}
                        <a class='inline' href="#inline_content-{$acti.uniId}">{$acti.nomUni|safehtml}</a>
                    </td>
                </tr>                                  
            {/foreach}
        </tbody>
    {/foreach}
</table>

{include file="user/Cataleg_user_display_unitat.tpl"}
   
{pager display='page' rowcount=$pager.numitems limit=$pager.itemsperpage posvar='page' maxpages='10'}
<a title='{gt text="Inici de la pàgina"}' href="#inici">
{img modname='Cataleg' src='gotop.png' __alt='Inici' style="opacity:0.8; filter:alpha(opacity=80);"}
</a>
