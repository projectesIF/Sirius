{adminheader}
<div class="z-admin-content-pagetitle">
   {icon type="view" size="small"}
   <h3><a href="{modurl modname='Cataleg' type='admin' func='eixosgest' catId=$cat.catId}">{$cat.nom}</a>  -  {gt text="Gestió de les prioritats"}<br><br>Eix: {$eix.ordre}.{$eix.nomCurt}</h3>
</div>
<div>
   <a href="{modurl modname='Cataleg' type='admin' func='addPrior' eixId=$eix.eixId}">{gt text="Afegeix una prioritat"}</a>
</div>
{if $prioritats}
   <table class="z-datatable">
       <thead>
           <tr>
               <th width='5%'>{gt text='Ordre'}</a></th>
               <th width='75%'>{gt text='Prioritat'}</th>
               <th width='5%' style="text-align:center">{gt text='Visibilitat'}</th>
               <th width='15%' style="text-align:center">{gt text='Accions'}</th>
          </tr>
       </thead>
       <tbody>
    {foreach from=$prioritats item='prioritat' key='key'}
    <tr class="{cycle values='z-odd,z-even'}">
       <td>{$prioritat.ordre}</td>
       <td>{$prioritat.nomCurt}</td>
       <td style="text-align:center">{if $prioritat.visible eq 1}{img modname='core' set='icons/extrasmall' src='button_ok.png' __title='És visible' __alt='És visible'}{else}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='No és visible' __alt='No és visible'}{/if}</td>
       <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editPrior' priId=$prioritat.priId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a></td>
    </tr>  
       
{/foreach}
</tbody>
</table>
{else}
    <h1>{gt text="Encara no s'ha creat cap prioritat"}</h1>
{/if}