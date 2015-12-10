<div>
   <a href="{modurl modname='Cataleg' type='admin' func='addSubprior' priId=$prior.priId}">{gt text="Afegeix una subprioritat"}</a>
</div>
{if $subpriors}
   <table class="z-datatable">
       <thead>
           <tr>
               <th width='5%'>{gt text='Ordre'}</a></th>
               <th width='75%'>{gt text='Subprioritat'}</th>
               <th width='5%' style="text-align:center">{gt text='Visibilitat'}</th>
               <th width='15%' style="text-align:center">{gt text='Accions'}</th>
          </tr>
       </thead>
       <tbody>
    {foreach from=$subpriors item='subprior' key='key'}
    <tr class="{cycle values='z-odd,z-even'}">
       <td>{$subprior.ordre}</td>
       <td>{$subprior.nomCurt}</td>
       <td style="text-align:center">{if $subprior.visible eq 1}{img modname='core' set='icons/extrasmall' src='button_ok.png' __title='És visible' __alt='És visible'}{else}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='No és visible' __alt='No és visible'}{/if}</td>
       <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editSubprior' sprId=$subprior.sprId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button type="button" style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick="javascript:esborra({$subprior.sprId},'{$subprior.nomCurt}');">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span></td>
    </tr>  
       
{/foreach}
</tbody>
</table>
{else}
    <h1>{gt text="Encara no s'ha creat cap subprioritat"}</h1>
{/if}
<script type="text/javascript"  language="javascript">
    function esborra($sprId,$nom) {
        var $mess = '{{gt text="Heu triat la subprioritat"}} \''+$nom+'\'\n\n{{gt text="Voleu esborrar-la?"}}\n\n{{gt text="Nota: Abans d\'esborrar-la es comprovarà que no tingui activitats associades ni establertes equivalències per a la importació."}}';
        if (confirm($mess)) {
        window.location = "index.php?module=Cataleg&type=admin&func=deleteSubprior&sprId="+$sprId;
       
        }
    }
</script>