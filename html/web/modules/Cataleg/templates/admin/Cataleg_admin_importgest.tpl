{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Gestió de la importació d'activitats entre catàlegs"}</h3>
</div>
<div>
    <a href="{modurl modname='Cataleg' type='admin' func='importaddTaula'}">{gt text="Afegeix una taula d'importació"}</a>
</div>
{if $iTaules}
    <table class="z-datatable">
        <thead>
            <tr>
                <th width="45%" style="text-align:center">{gt text='Catàleg Origen'}</th>
                <th width="45%" style="text-align:center">{gt text='Catàleg Destinació'}</th>
                <th width="10%" style="text-align:center">{gt text='Accions'}</th> 
            </tr>
        </thead>
        <tbody>
            {foreach from=$iTaules item='iTaula' key='key'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>{$cats[$iTaula.catIdOri].nom}</td>
                    <td>{$cats[$iTaula.catIdDest].nom}</td>
                    <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='importeditTaula' importId=$iTaula.importId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick="javascript:esborra({$iTaula.importId},'{$cats[$iTaula.catIdOri].nom|@escape:quotes}','{$cats[$iTaula.catIdDest].nom|@escape:quotes}');">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span></td>
                </tr> 

            {/foreach}
        </tbody>
    </table>
{else}
    <h1>{gt text="Encara no s'ha creat cap taula d'importació d'activitats"}</h1>
{/if}
<p class="z-informationmsg">{gt text="En importar activitats, només es podran triar els 'catàlegs d'origen' assignats mitjançant una taula d'importació amb aquell 'catàleg destinació'."}</p>
<script>
    function esborra($importId,$catOriNom,$catDestNom) {
       var $mess = '{{gt text="En esborrar la taula, no es podran importar activitats des de"}} \''+$catOriNom+'\' {{gt text="a"}} \''+$catDestNom+'\'.\n\n{{gt text="Voleu esborrar-la?"}}';
       if (confirm($mess)) {
            window.location = "index.php?module=Cataleg&type=admin&func=importdeleteTaula&importId="+$importId;
        }
    }
</script>