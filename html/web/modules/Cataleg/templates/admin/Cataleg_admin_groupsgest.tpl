{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="group" size="small"}
    <h3>{gt text="Gestió dels grups"}</h3>
</div>
<h4>{gt text="Grups d'usuaris d'unitats editores del catàleg"}</h4>
<div>
    <a href="{modurl modname='Cataleg' type='admin' func='addGroup'}">{gt text="Afegeix un grup"}</a>
</div>
{if $GroupsUnits}
    <table class="z-datatable">
        <thead>
            <tr>
                <th width="35%" style="text-align:center">{gt text='Nom'}</th>
                <th width="50%" style="text-align:center">{gt text='Descripció'}</th>
                <th width="15%" style="text-align:center">{gt text='Accions'}</th> 
            </tr>
        </thead>
        <tbody>
            {foreach from=$GroupsUnits item='GroupUnit' key='key'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>{$GroupUnit.name}</td>
                    <td>{$GroupUnit.description}</td>
                    <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editGroup' gid=$GroupUnit.gid}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick="javascript:esborra('{$GroupUnit.name|@escape:quotes}',{$GroupUnit.gid});">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span><span style="padding-left:3px"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$GroupUnit.gid}">{img modname='core' set='icons/extrasmall' src='group.png' __title='Gestiona els membres' __alt='Gestiona els membres'}</a></span></td>
                </tr> 

            {/foreach}
        </tbody>
    </table>
{else}
    <h1>{gt text="Encara no hi ha cap grup d'usuaris d'unitats del catàleg"}</h1>
{/if}
<h4>{gt text="Grups d'usuaris generals de Sirius"}</h4>
        <table class="z-datatable">
        <thead>
            <tr>
                <th width="35%" style="text-align:center">{gt text='Nom'}</th>
                <th width="50%" style="text-align:center">{gt text='Descripció'}</th>
                <th width="15%" style="text-align:center">{gt text='Accions'}</th> 
            </tr>
        </thead>
        <tbody>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Odissea"}</td>
                <td>{gt text="Grup d'administradors d'Odissea"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.Odissea}">{img modname='core' set='icons/extrasmall' src='group.png' __title='Gestiona els membres' __alt='Gestiona els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Cert"}</td>
                <td>{gt text="Grup de Certificació"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.Cert}">{img modname='core' set='icons/extrasmall' src='group.png' __title='Gestiona els membres' __alt='Gestiona els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="gA"}</td>
                <td>{gt text="Grup d'usuaris del gA"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.gA}">{img modname='core' set='icons/extrasmall' src='group.png' __title='Gestiona els membres' __alt='Gestiona els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="gB"}</td>
                <td>{gt text="Grup d'usuaris del gB"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.gB}">{img modname='core' set='icons/extrasmall' src='group.png' __title='Gestiona els membres' __alt='Gestiona els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="UNI"}</td>
                <td>{gt text="Grup d'usuaris de les Unitats"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.UNI}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="ST"}</td>
                <td>{gt text="Grup d'usuaris de les Seccions Territorials"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.ST}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="SE"}</td>
                <td>{gt text="Grup d'usuaris dels Serveis Educatius"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.SE}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Gestform"}</td>
                <td>{gt text="Grup dels gestors de formació (Gestform)"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.Gestform}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="LectorsCat"}</td>
                <td>{gt text="Grup d'usuaris lectors del catàleg"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.LectorsCat}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="EditorsCat"}</td>
                <td>{gt text="Grup d'usuaris editors del catàleg"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.EditorsCat}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Usuaris personals"}</td>
                <td>{gt text="Grup d'usuaris personals de Sirius"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.Personals}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Usuaris genèrics"}</td>
                <td>{gt text="Grup d'usuaris genèrics de Sirius"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.Generics}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
           <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Tots els usuaris de Sirius"}</td>
                <td>{gt text="Grup general de tots els usuaris de Sirius"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.Sirius}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Ex-Usuaris de Sirius"}</td>
                <td>{gt text="Històric d'antics usuaris sense permisos"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.ExSirius}">{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}</a></td>
            </tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}"><td></td><td></td><td></td></tr>
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{gt text="Gestors"}</td>
                <td>{gt text="Grup d'usuaris gestors de Sirius"}</td>
                <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='membersGroupgest' gid=$grupsZikula.Gestors}">{if $gestor}{img modname='core' set='icons/extrasmall' src='group.png' __title='Gestiona els membres' __alt='Gestiona els membres'}{else}{img modname='core' set='icons/extrasmall' src='info.png' __title='Visualitza els membres' __alt='Visualitza els membres'}{/if}</a></td>
            </tr> 
        </tbody>
    </table>
<script>
    function esborra($name,$gid) {
        var $mess = '{{gt text="Heu triat el grup d\'usuaris de la unitat"}} \''+$name+'\'\n\n{{gt text="Voleu esborrar-lo?"}}';
        if (confirm($mess)) {
            window.location = "index.php?module=Cataleg&type=admin&func=deleteGroupUnit&gid="+$gid;
        }
    }
    
</script>