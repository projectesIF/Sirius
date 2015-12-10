{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Gestió de la taula auxiliar i dels llistats del Catàleg"}</h3>
</div>
<div>
    <a href="{modurl modname='Cataleg' type='admin' func='addAuxElement'}">{gt text="Afegeix un element auxiliar"}</a>
</div>
<table class="z-datatable">
        <thead>
            <tr>
                <th width="15%" style="text-align:center">{gt text='Tipus'}</th>
                <th width="10%" style="text-align:center">{gt text='Ordre'}</th>
                <th width="10%" style="text-align:center">{gt text='Visibilitat'}</th>
                <th width="20%" style="text-align:center">{gt text='Nom Curt'}</th>
                <th width="30%" style="text-align:center">{gt text='Nom'}</th>
                <th width="15%" style="text-align:center">{gt text='Accions'}</th>
             </tr>
        </thead>
        <tbody>
            {foreach from=$auxElements item='auxElement'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td style="text-align:center">{$auxElement.tipus}</td>
                    <td style="text-align:center">{$auxElement.ordre}</td>
                    <td style="text-align:center">{if $auxElement.visible eq 1}{img modname='core' set='icons/extrasmall' src='button_ok.png' __title='És visible' __alt='És visible'}{else}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='No és visible' __alt='No és visible'}{/if}</td>
                    <td style="text-align:center">{$auxElement.nomCurt}</td>
                    <td style="text-align:center">{$auxElement.nom}</td>
                    <td><a href="{modurl modname='Cataleg' type='admin' func='editAuxElement' auxId=$auxElement.auxId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a></td>
                </tr>
            {/foreach}
            
        </tbody>
    </table>
                