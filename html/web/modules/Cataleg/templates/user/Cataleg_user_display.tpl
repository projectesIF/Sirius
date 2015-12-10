{if !$pdf}
    {insert name='getstatusmsg'}
    <div class="userpageicon">
        {if $isGestor}
            <a href="{modurl modname='Cataleg' type='user' func='display' priId=$item.priId edito='1'}">{img modname='Cataleg' src='orientacions.png'}</a>
        {else}
            {img modname='Cataleg' src='orientacions.png'}
        {/if}
    </div>
    {pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació :: Orientacions"}
    <div class="usercontainer">           
        <a id="inici"> </a>
        {if $showLinkActs}
            <a href="{modurl modname='Cataleg' type='user' func='activitats' priId=$item.priId }"><span style="text-decoration:underline;">{gt text="Activitats"}</span></a></div>
        {/if}
        <h3> 
            <a href="{modurl modname="Cataleg" type="user" func="cataleg" catId=$cataleg.catId}" title="{gt text='Anar al catàleg'}" alt="{gt text='Anar al catàleg'}">
            {$cataleg.nom}</a>
        </h3>
            
        <h3 style="filter: alpha(opacity=70); opacity: 0.7;">{gt text="Eix: "}{$eix.ordre}. {$eix.nom}</h3>
        <h3>{gt text="Prioritat: "}{$item.ordre}. {$item.nom}</h3>
        <form class='z-form'>
            <fieldset>
                <legend>Subprioritats</legend>
                <table class="z-datatable">
                    <thead>
                        <tr>
                            <th>{gt text='Nom'}</th>
                        </tr>
                    </thead>   
                    {foreach from=$subpri item='subpri'}
                        <tr class="{cycle values='z-odd,z-even'}">
                            <td title="{$subpri.nom|safehtml}"><span style="font-weight:bold;">{$subpri.ordre|safehtml}</span>-{$subpri.nomCurt|safehtml} </td>
                        </tr>
                    {/foreach}
                </table>
            </fieldset>

            <br />

            <fieldset style="text-align:justify;text-justify:inter-word;">
                <legend>{gt text="Orientacions"}</legend>
                {$item.orientacions}
            </fieldset>
            <br>

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
            <br>

            <fieldset style="text-align:justify;text-justify:inter-word;">
                <legend>{gt text="Recursos"}</legend>
                {$item.recursos}
            </fieldset>
        </form>

    </div>       
    <div class="userpageicon"><a href="{modurl modname="Cataleg" type="user" func="display" priId=$item.priId pdf=1}" title="{gt text='Exporta a PDF'}" alt="{gt text='Exporta a PDF'}">{img modname='Cataleg' src='pdf.jpg' style="float:right; margin: 0 0 15px 15px;"} </a></div>

    {include file="user/Cataleg_user_display_unitat.tpl"}
    <a title='{gt text="Inici de la pàgina"}' href="#inici">
    {img modname='Cataleg' src='gotop.png' __alt='Inici' style="opacity:0.8; filter:alpha(opacity=80);"}
    </a>
{else}
    {* Plantilla per al PDF ------------------------------------------------- *}
    {*<span style="text-align:right; font-style:italic; font-size:10pt ">{gt text="Fitxa d'orientacions"}:&nbsp;{$cataleg.nom}</span><hr>*}
    <div style="font-size:10pt">
        <span style="font-weight:bold;font-size:10pt">Eix: </span>{$eix.ordre}. {$eix.nom}<br />
        <!--span style="font-weight:bold;font-size:10pt">Prioritat: </span--><h3>{$item.ordre}. {$item.nomCurt}</h3><hr> 

        <span style="text-align:justify;font-size:9pt">
            <span style="font-weight:bold;"> <br />{gt text="Subprioritats"}</span><br />
                {foreach from=$subpri item='subpri'}
                <span style="font-weight:bold;text-align:justify;">{$subpri.ordre|safehtml}</span>.- {$subpri.nom}
                <br />
            {/foreach}
            <hr>
        </span>
        <div style="text-align:justify;text-justify:inter-word;">
            <span style="font-weight:bold;text-align:justify;font-size:12pt">{gt text="Orientacions"}</span><br />
            {$item.orientacions}<br />
        </div>
    </div>
    <br />

    <span style="text-align:justify;font-size:12pt">
        <span style="font-weight:bold;">
            {if ($uniImplicades|@count>1)}
                {gt text="Unitats implicades"}
            {else}
                {gt text="Unitat implicada"}
            {/if}
        </span><br />

        <table class="gridtable">
            <thead>
                <tr>
                    <th >{gt text='Temàtica'}</th>
                    <th>{gt text='Unitat'}</th>
                    <th>{gt text='Contacte'}</th>
                    <th>{gt text='Email'}</th>
                    <th>{gt text='Telèfon'}</th>
                    <th>{img modname='core' src='group.png' set='icons/extrasmall' __alt="Disposa de persones formadores?" __title="Disposa de persones formadores?"}</th>
                </tr>
            </thead>   
            {foreach from=$uniImplicades item='unitat'}
                <tr style="font-size: 7pt;">
                    <td>{$unitat.tematica|safehtml}</td>
                    <td>{$unitat.nomUni|safehtml}</td>
                    <td>{$unitat.pContacte|safehtml} </td>
                    <td>{$unitat.email|safehtml} </td>
                    <td>{$unitat.telContacte|safehtml} </td>
                    <td style="text-align:center">{if $unitat.dispFormador|safehtml}{gt text="Sí"}{else}{gt text="No"}{/if}</td>
                </tr>
            {/foreach}
        </table>
        <br />
        <span style="font-weight:bold;font-size: 12pt;">{gt text="Recursos"}</span>
        <span style="text-align:justify;text-justify:inter-word;font-size:10pt">
            {$item.recursos|@safehtml}</span>
    </span>

{/if}

<style type="text/css">
    table.gridtable {	
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable th {
        border-width: 1px;
        font-size: 0.8em;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable td {
        border-width: 1px;
        font-size: 0.8em;
        color: black;
        padding: 8px;
        border-style: solid dotted;
        border-color: #666666;
        background-color: #ffffff;
        text-align:baseline;
    }
</style>