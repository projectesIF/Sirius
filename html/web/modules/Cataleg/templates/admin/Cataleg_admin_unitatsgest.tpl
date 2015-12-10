{adminheader}
<div class="z-admin-content-pagetitle">
   {icon type="view" size="small"}
   <h3>{$cat.nom}  -  {gt text="Gestió de les unitats"}</h3>
</div>
<div>
   <a href="{modurl modname='Cataleg' type='admin' func='addUnitat' catId=$cat.catId}">{gt text="Afegeix una unitat"}</a>
</div>
{if $unitats}
   <table class="z-datatable">
       <thead>
           <tr>
               <th width='40%'>{gt text='Nom'}</a></th>
               <th width='40%'>{gt text='Grup d\'usuaris'}</th>
               <th width='5%' style="text-align:center">{gt text='Actiu'}</th>
               <th width='15%' style="text-align:center">{gt text='Accions'}</th>
          </tr>
       </thead>
       <tbody>
    {foreach from=$unitats item='unitat' key='key'}
    <tr class="{cycle values='z-odd,z-even'}">
       <td>{$unitat.nom}</td>
       <td>{assign var='gz' value=$unitat.gzId}{$groups.$gz.name}</td>
       <td style="text-align:center">{if $unitat.activa eq 1}{img modname='core' set='icons/extrasmall' src='button_ok.png' __title='És activa' __alt='És activa'}{else}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='No és activa' __alt='No és activa'}{/if}</td>
       <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editUnitat' uniId=$unitat.uniId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a></td>
    </tr>  
       
{/foreach}
</tbody>
</table>
{else}
    <h1>{gt text="Encara no s'ha creat cap unitat"}</h1>
{/if}