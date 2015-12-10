{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="import" size="small"}
    <h3>{gt text='Importa centres'}</h3>
</div>

{if $importResults neq ''}
<div class="z-errormsg">
    {$importResults}
</div>
{/if}

<form class="z-form" action="{modurl modname='Cataleg' type='admin' func='importaCentres'}" method="post" enctype="multipart/form-data">
    <div>
        <input type="hidden" name="confirmed" value="1" />
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
            <a href="{modurl modname='Cataleg' type='admin' func='modulesettings'}" title="{gt text='Cancel·la'}">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel·la' __title='Cancel·la'} {gt text='Cancel·la'}</a>
        </div>
    </div>
</form>

<div class="z-informationmsg">
    <h4>{gt text="Notes sobre l'arxiu CSV"}</h4>
    <dl>
        <dt>{gt text="-Ha de tenir codificació <span style='padding: 2px;background-color:white;color:green'>utf-8</span> i extensió <span style='padding: 2px;background-color:white;color:green'>csv</span>."}</dt>
    </dl>
    <dl>
        <dt>{gt text="-Els camps han d'estar separats per <span style='padding: 2px;background-color:white;color:green'>;</span> "}</dt>
    </dl>
    <dl>
        <dt>{gt text="-La primera línia indica els camps. Aquests poden ser:"}</dt>
        <dd>CODI_ENTITAT;CODI_TIPUS_ENTITAT;NOM_ENTITAT;NOM_LOCALITAT;NOM_DT;CODI_DT;NOM_TIPUS_ENTITAT</dd>
    </dl>
    <dl>
        <dt>{gt text="-El camp <span style='padding: 2px;background-color:white;color:green'>CODI_ENTITAT</span> és obligatori i ha d'ocupar el primer lloc (columna)."}</dt>
    </dl>
     <dl>
        <dt>{gt text="-Els registres (fileres) han de tenir informats tots els camps (columnes) de la capçalera."}</dt>
    </dl>       
    <dl>
        <dt>{gt text="-Pels camps de text complexos (puguin tenir signes de puntació) farem servir les <span style='padding: 2px;background-color:white;color:green'>\" \"</span>."}</dt>
    </dl>
    <dl>
        <dt>{gt text="-Exemple: "}</dt>
        <dd>CODI_ENTITAT;CODI_TIPUS_ENTITAT;NOM_ENTITAT;NOM_LOCALITAT;NOM_DT;CODI_DT;NOM_TIPUS_ENTITAT</dd>
        <dd>43007518;EMM;Municipal;Salou;Tarragona;7;"Escola municipal de música"</dd>
    </dl>
</div>
{adminfooter}