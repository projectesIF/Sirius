<div>
   <a href="{modurl modname='Cataleg' type='admin' func='addImpunit' priId=$prior.priId}">{gt text="Afegeix una unitat implicada"}</a>
</div>
{if $impunits}
   <table class="z-datatable">
       <thead>
           <tr>
               <th with="15%">{gt text='Temàtica'}</a></th>
               <th with="20%">{gt text='Persona de contacte'}</a></th>
               <th>{gt text='Correu'}</a></th>
               <th>{gt text='Telèfon'}</a></th>
               <th>{gt text='Unitat'}</th>
               <th style="text-align:center">{gt text='Accions'}</th>
          </tr>
       </thead>
       <tbody>
    {foreach from=$impunits item='impunit' key='key'}
    <tr class="{cycle values='z-odd,z-even'}">
       <td>{$impunit.tematica}</td>
       <td>{$impunit.pContacte}</td>
       <td>{$impunit.email}</td>
       <td>{$impunit.telContacte}</td>
       <td>{$units[$impunit.uniId].nom}</td>
       <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editImpunit' impunitId=$impunit.impunitId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button type="button" style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick="javascript:esborra2({$impunit.impunitId},'{$impunit.pContacte}');">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span></td>
    </tr>  
       
{/foreach}
</tbody>
</table>
{else}
    <h1>{gt text="Encara no s'ha creat cap unitat implicada"}</h1>
{/if}
<script>
    function esborra2($impunitId,$pContacte) {
        var $mess = '{{gt text="Heu triat la persona de contacte"}} \''+$pContacte+'\'\n\n{{gt text="Voleu esborrar-la?"}}';
        if (confirm($mess)) {
            window.location = "index.php?module=Cataleg&type=admin&func=deleteImpunit&impunitId="+$impunitId;
        }
    }
</script>