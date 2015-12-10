<div>
   <a href="{modurl modname='Cataleg' type='admin' func='addResponsable' uniId=$unitat.uniId}">{gt text="Afegeix una persona responsable"}</a>
</div>
{if $responsables}
   <table class="z-datatable">
       <thead>
           <tr>
               <th width='70%'>{gt text='Nom'}</a></th>
               <th width='10%'>{gt text='email'}</th>
               <th width='5%'>{gt text='telèfon'}</th>
               <th width='15%' style="text-align:center">{gt text='Accions'}</th>
          </tr>
       </thead>
       <tbody>
    {foreach from=$responsables item='responsable' key='key'}
    <tr class="{cycle values='z-odd,z-even'}">
       <td>{$responsable.responsable}</td>
       <td>{$responsable.email}</td>
       <td>{$responsable.telefon}</td>
       <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editResponsable' respunitId=$responsable.respunitId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button type="button" style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick="javascript:esborra({$responsable.respunitId},'{$responsable.responsable|@escape:quotes}');">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span></td>
    </tr>  
       
{/foreach}
</tbody>
</table>
{else}
    <h1>{gt text="Encara no s'ha definit cap persona responsable"}</h1>
{/if}
<script type="text/javascript"  language="javascript">
    function esborra($respunitId,$nom) {
    var $mess2 = '{{gt text="Heu triat la persona responsable"}} \''+$nom+'\'\n\n{{gt text="Voleu esborrar-la?"}}';
        if (confirm($mess2)) {
        window.location = "index.php?module=Cataleg&type=admin&func=deleteResponsable&respunitId="+$respunitId;
       
        }
    }
</script>