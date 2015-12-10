{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="group" size="small"}
    <h3>{gt text="Gestió d'entitats i grups d'entitats del Gtaf"}</h3>
</div>
<h4>{gt text="Grups d'entitats-Gtaf"}</h4>
<div>
    <a href="{modurl modname='Cataleg' type='admin' func='addGtafGroup'}">{gt text="Afegeix un grup"}</a>
</div>
{if $gtafInfo.groups}
    <table class="z-datatable">
        <thead>
            <tr>
                <th width="8%" style="text-align:center">{gt text='Codi'}</th>
                <th width="52%" style="text-align:left">{gt text='Nom'}</th>
                <th width="25%" style="text-align:left">{gt text='Responsable'}</th>
                <th width="15%" style="text-align:center">{gt text='Accions'}</th> 
            </tr>
        </thead>
        <tbody>
            {foreach from=$gtafInfo.groups item='group' key='key'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>{$group.gtafGroupId}</td>
                    <td>{$group.nom}</td>
                    <td>{$group.responsable.nom} {$group.responsable.cognom1}</td>
                    <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editGtafGroup' gtafgid=$group.gtafGroupId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick='javascript:esborra("{$group.gtafGroupId}","{$group.nom|htmlentities:3}","grup");'>{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span></td>
                </tr> 

            {/foreach}
        </tbody>
    </table>
{else}
    <h1>{gt text="Encara no hi ha cap grup d'entitats definit"}</h1>
{/if}

<h4>{gt text="Entitats-Gtaf"}</h4>
<div>
    <a href="{modurl modname='Cataleg' type='admin' func='addGtafEntity'}">{gt text="Afegeix una entitat"}</a>
</div>
{if $gtafInfo.entities}
    <table class="z-datatable">
        <thead>
            <tr>
                <th width="8%" style="text-align:center">{gt text='Codi'}</th>
                <th width="5%" style="text-align:center">{gt text='Tipus'}</th>
                <th width="67%" style="text-align:left">{gt text='Nom'}</th>
                <th width="5%" style="text-align:center">{gt text='Codi_grup'}</th>
                <th width="15%" style="text-align:center">{gt text='Accions'}</th> 
            </tr>
        </thead>
        <tbody>
            {foreach from=$gtafInfo.entities item='entity' key='key'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>{$entity.gtafEntityId}</td>
                    <td>{$entity.tipus}</td>
                    <td>{$entity.nom}</td>
                    <td>{$entity.gtafGroupId}</td>
                    <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editGtafEntity' gtafeid=$entity.gtafEntityId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button type="button" style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick='javascript:esborra("{$entity.gtafEntityId}","{$entity.nom|htmlentities:3}","entitat");'>{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' __title='Esborra' __alt='Esborra'}</button></span></td>
                </tr> 

            {/foreach}
        </tbody>
    </table>
{else}
    <h1>{gt text="Encara no hi ha cap entitat definida"}</h1>
{/if}
{if isset($gtafInfo.ent_orfe)}
<div class="z-warningmsg">
        <span>{gt text="Hi ha entitats que tenen informat un grup que inexistent. Caldria repassar les entitats:"}</span>
        <span>{foreach from=$gtafInfo.ent_orfe item='ent'}{$ent} - {/foreach}</span>
</div>
{/if}
<h4>{gt text="Importació/Exportació de dades amb CSV"}</h4>
<ul>
    <li><a href="{modurl modname='Cataleg' type='admin' func='exportGtafEntities' case='entities'}">{gt text="Exporta les entitats-gtaf"}</a></li>
    <li><a href="{modurl modname='Cataleg' type='admin' func='exportGtafEntities' case='groups'}">{gt text="Exporta els grups d'entitats-gtaf"}</a></li>
    <li><a href="{modurl modname='Cataleg' type='admin' func='importGtafEntities' case='entities'}">{gt text="Importa les entitats-gtaf"}</a></li>
    <li><a href="{modurl modname='Cataleg' type='admin' func='importGtafEntities' case='groups'}">{gt text="Importa els grups d'entitats-gtaf"}</a></li>
</ul>
<script type="text/javascript"  language="javascript">
    function esborra(gtafeid,nom,obj) {
        if (obj == 'entitat') { 
            var $mess = '{{gt text="Heu triat l\'entitat amb codi:"}} \''+gtafeid+'\'{{gt text=" i nom:"}} \''+nom+'\'\n\n{{gt text="Voleu esborrar-la?"}}';
        } else if (obj == 'grup') {
            var $mess = "{{gt text="Heu triat el grup amb codi:"}} '"+gtafeid+"'{{gt text=" i nom:"}} '"+nom+"'\n\n{{gt text="Voleu esborrar-lo?"}}";
        }
        if (confirm($mess)) {
            var gtafGroupsAc = {{$gtafInfo.groupsAc|@json_encode}};
            if ((obj == 'grup') && (jQuery.inArray(gtafeid,gtafGroupsAc) != -1)) {
                alert('{{gt text="No es pot esborrar el grup. \\n\\nTé entitats vinculades."}}');
            } else {
                window.location = "index.php?module=Cataleg&type=admin&func=deleteGtafEntity&gtafeid="+gtafeid+'&obj='+obj;
            }
       
        }
    }
</script>