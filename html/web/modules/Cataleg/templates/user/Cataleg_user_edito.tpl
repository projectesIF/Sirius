{*
EDICIó D'ORIENTACIONS --------------------------------------------
*}
{nocache}{*include file='user/Cataleg_user_menu.tpl'*}{/nocache}
{insert name='getstatusmsg'}
<div class="userpageicon">{img modname='Cataleg' src='orientacions.png'}</div>
{pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació :: Orientacions"}
<div class="usercontainer">
    {*<div class="userpageicon"><a href="{modurl modname="Cataleg" type="user" func="display" priId=$item.priId pdf=1}" title="{gt text='Exporta a PDF'}" alt="{gt text='Exporta a PDF'}">{img modname='Cataleg' src='pdf.jpg' style="float:right; margin: 0 0 15px 15px;"} </a></div>
    <div><a href="{modurl modname='Cataleg' type='user' func='main'}"><span style="text-decoration:underline; font-size:10px">{gt text="Inici"}</span></a>&nbsp;|
        <a href="{modurl modname='Cataleg' type='user' func='activitats' priId=$item.priId }"><span style="text-decoration:underline; font-size:10px">{gt text="Activitats"}</span></a></div>*}<br>

    <h3>Catàleg: {$cataleg.nom}</h3>
    <h3>Eix: {$eix.ordre}. {$eix.nom}</h3>
    <h2>Prioritat: {$item.ordre}. {$item.nom}</h2>
    <form class="z-form" name="editOrientacio" action="index.php?module=Cataleg&type=user&func=updateOri" method="post" enctype="application/x-www-form-urlencoded">
        <input type=hidden name=priId value={$item.priId}></input>
       {* <fieldset>
            <legend>Subprioritats</legend>
            <table class="z-datatable">
                <thead>
                    <tr>
                        <th>{gt text='Nom'}</th>
                    </tr>
                </thead>   
                {foreach from=$subpri item='subpri'}
                    <tr class="{cycle values='z-odd,z-even'}">
                        <td title="{$subpri.nom|@nl2br}"><span style="font-weight:bold;">{$subpri.ordre|safehtml}</span>-{$subpri.nomCurt|safehtml} </td>
                    </tr>
                {/foreach}
            </table>
        </fieldset> *}

        <br />

        <fieldset style="text-align:justify;text-justify:inter-word;">
            <legend>{gt text="Orientacions"}</legend>
            <textarea rows=15 name="orientacions" id="orientacions">{$item.orientacions}
            </textarea>
        </fieldset>
        <br>
{*
        <fieldset>
            {if ($uniImplicades|@count>1)}       
                <legend>{gt text="Unitats implicades"}</legend>
            {else}
                <legend>{gt text="Unitat implicada"}</legend>
            {/if}
            <table class="z-datatable">
                <thead>
                    <tr>
                        <th>{gt text='Temàtica'}</th>
                        <th>{gt text='Unitat'}</th>
                        <th>{gt text='Persona de contacte'}</th>
                        <th>{gt text='Email'}</th>
                        <th>{gt text='Telèfon'}</th>
                        <th>{img modname='core' src='group.png' set='icons/extrasmall' __alt="Disposa de persones formadores?" __title="Disposa de persones formadores?"}</th>
                    </tr>
                </thead>   
                {foreach from=$uniImplicades item='unitat'}
                    <tr class="{cycle values='z-odd,z-even'}">
                        <td>{$unitat.tematica|safehtml}</td>
                        {if !$pdf}
                            <td style="cursor:handpointer">
                                <a class='inline' href="#inline_content-{$unitat.uniId}">{$unitat.nomUni|safehtml}</a>
                            </td>
                        {else}
                            <td>{$unitat.nomUni|safehtml}</td>
                        {/if}
                        <td>{$unitat.pContacte|safehtml} </td>
                        <td>{$unitat.email|safehtml} </td>
                        <td>{$unitat.telContacte|safehtml} </td>
                        <td style="text-align:center">{if $unitat.dispFormador|safehtml}{gt text="Sí"}{else}{gt text="No"}{/if}</td>
                    </tr>
                {/foreach}
            </table>

        </fieldset>
 *}
        <br>

        <fieldset style="text-align:justify;text-justify:inter-word;">
            <legend>{gt text="Recursos"}</legend>
            <textarea rows=10 name ='recursos' id='recursos'>{$item.recursos}</textarea>
        </fieldset>
    </form>
    <div id="botons" class="z-buttonrow z-buttons z-center">
        <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:document.editOrientacio.submit();" title="{gt text="Desar la fitxa d'activitat i tornar a la llista d'activitats."}">{gt text="Desar"}</button> 
        <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:history.go(-1);" title="{gt text="Cancel·lar"}">{gt text="Cancel·lar"}</button> 
    </div>
</div>       
{notifydisplayhooks eventname='Cataleg.ui_hooks.Cataleg.form_edit' id=null}
<style type="text/css">
    table.gridtable {	
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        text-align:baseline;
    }
</style>