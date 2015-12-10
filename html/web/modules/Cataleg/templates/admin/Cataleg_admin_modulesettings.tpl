    {ajaxheader modname=Cataleg filename=Cataleg.js}
    {pageaddvar name='javascript' value='jQuery'}
 
    {insert name='getstatusmsg'}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text="Gestió del mòdul"}</h3>
</div>
{if $level > 0}
    <h3>{gt text="Administradors del Catàleg"}</h3>
    <ul>
        <li><a href="{modurl modname='Cataleg' type='admin' func='importFormulari'}">{gt text="Importació de catàlegs"}</a></li>
        <li><a href="{modurl modname='Cataleg' type='admin' func='importgest'}">{gt text="Gestió de la importació d'activitats"}</a></li>
        <li><a href="{modurl modname='Cataleg' type='admin' func='exportaCentres'}">{gt text="Exporta els centres de Sirius (csv)"}</a></li>
        <li><a href="{modurl modname='Cataleg' type='admin' func='importaCentres'}">{gt text="Importa centres des d'un csv"}</a></li>
        <li><a href="{modurl modname='Cataleg' type='admin' func='gtafEntitiesGest'}">{gt text="Gestió de les entitats de gtaf"}</a></li>
    </ul>
        {assign var='noblock' value=true}
        {include file="block/Cataleg_block_config.tpl"}
{/if}
{if $level > 1}
    <h3>{gt text="Administradors de Sirius"}</h3>
    <ul>
        <li><a href="{modurl modname='Cataleg' type='admin' func='grupsZikulagest'}">{gt text="Assignació dels grups de Zikula generals del catàleg"}</a></li>
        <li><a href="{modurl modname='Cataleg' type='admin' func='auxgest'}">{gt text="Gestió de la taula auxiliar i llistats"}</a></li>
        <li><a href="{modurl modname='Cataleg' type='admin' func='phpinfo'}">{gt text="Obtenció de dades de l'entorn de php"}</a></li>
    </ul>
{/if}
