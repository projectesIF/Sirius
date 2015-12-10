{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="import" size="small"}
    <h3>{if $case eq entities}{gt text='Importa entitats-gtaf'}{else}{gt text="Importa grups d'entitats-gtaf"}{/if}</h3>
</div>

{if $importResults neq ''}
<div class="z-errormsg">
    {$importResults}
</div>
{/if}

<form class="z-form" action="{modurl modname='Cataleg' type='admin' func='importGtafEntities'}" method="post" enctype="multipart/form-data">
    <div>
        <input type="hidden" name="confirmed" value="2" />
        <input type="hidden" name="case" value="{$case}" />
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <fieldset>
            <legend>{gt text="Selecciona l'arxiu CSV"}</legend>
            <div class="z-formrow">
                <label for="users_import">{gt text="Arxiu CSV (màx. %sB)" tag1=$post_max_size}</label>
                <input id="users_import" type="file" name="importFile" size="30" />
                <em class="z-formnote z-sub">{gt text="La codificació de l'arxiu ha de ser utf-8."}</em>
            </div>
        </fieldset>
        <div class="z-formbuttons z-buttons">
            {button src='button_ok.png' set='icons/extrasmall' __alt='Importa' __title='Importa' __text='Importa'}
            <a href="{modurl modname='Cataleg' type='admin' func='gtafEntitiesGest'}" title="{gt text='Cancel·la'}">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel·la' __title='Cancel·la'} {gt text='Cancel·la'}</a>
        </div>
    </div>
</form>

<div class="z-informationmsg">
    <h2>
        {if $case eq entities}
            {gt text="Importar una nova estructura de les entitats-gtaf de Sirius"}
        {else}
            {gt text="Importar una nova estructura dels grups d'entitats-gtaf de Sirius"}
        {/if}
    </h2>
    <h4>{gt text="!! La importació esborrarà prèviament el contingut de les taules de Sirius. Per tant, el resultat final serà igual a la informació de l'arxiu."}</h4>
    <h4>{gt text="Podeu fer servir la funció d'exportació per tenir prèviament el CSV amb la informació emmagatzemada."}</h4>
    <h4>{gt text="Notes sobre l'arxiu CSV"}</h4>    
    <dl>
        <dt>{gt text="-Ha de tenir codificació <span style='padding: 2px;background-color:white;color:green'>utf-8</span> i extensió <span style='padding: 2px;background-color:white;color:green'>csv</span>."}</dt>
    </dl>
    <dl>
        <dt>{gt text="-Els camps han d'estar separats per <span style='padding: 2px;background-color:white;color:green'>;</span> "}</dt>
    </dl>
    <dl>
        <dt>{gt text="-La primera línia indica els camps. Aquests són:"}</dt>
        {if $case eq entities}
            <dd>gtafEntityId;tipus;nom;gtafGroupId</dd>
        {else}
            <dd>gtafGroupId;nom;resp_uid</dd>
        {/if}
    </dl>
    <dl>
        {if $case eq entities}
            <dt>{gt text="-El camp <span style='padding: 2px;background-color:white;color:green'>gtafEntityId</span> és obligatori i ha d'ocupar el primer lloc (columna)."}</dt>
            <dt>{gt text="-La resta de camps (<i>tipus, nom i gtafGroupId</i>)també són obligatoris."}</dt>
        {else}
            <dt>{gt text="-El camp <span style='padding: 2px;background-color:white;color:green'>gtafGroupId</span> és obligatori i ha d'ocupar el primer lloc (columna)."}</dt>
            <dt>{gt text="-El camp <i>nom</i> també és obligatori"}</dt>
        {/if}
    </dl>
     <dl>
        <dt>{gt text="-Els registres (fileres) han de tenir informats tots els camps (columnes) de la capçalera."}</dt>
    </dl>       
    <dl>
        <dt>{gt text="-Pels camps de text complexos (puguin tenir signes de puntació) farem servir les <span style='padding: 2px;background-color:white;color:green'>\" \"</span>."}</dt>
    </dl>
</div>
{adminfooter}