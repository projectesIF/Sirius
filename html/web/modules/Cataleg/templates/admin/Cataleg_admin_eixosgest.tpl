{adminheader}
<div class="z-admin-content-pagetitle">
   {icon type="view" size="small"}
   <h3>{$cat.nom}  -  {gt text="Gestió dels eixos"}</h3>
</div>
<div>
   <a href="{modurl modname='Cataleg' type='admin' func='addEix' catId=$cat.catId}">{gt text="Afegeix un eix"}</a>
</div>
{if $eixos}
   <table class="z-datatable">
       <thead>
           <tr>
               <th width='5%'>{gt text='Ordre'}</a></th>
               <th width='75%'>{gt text='Eix'}</th>
               <th width='5%' style="text-align:center">{gt text='Visibilitat'}</th>
               <th width='15%' style="text-align:center">{gt text='Accions'}</th>
          </tr>
       </thead>
       <tbody>
    {foreach from=$eixos item='eix' key='key'}
    <tr class="{cycle values='z-odd,z-even'}">
       <td>{$eix.ordre}</td>
       <td>{$eix.nomCurt}</td>
       <td style="text-align:center">{if $eix.visible eq 1}{img modname='core' set='icons/extrasmall' src='button_ok.png' __title='És visible' __alt='És visible'}{else}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='No és visible' __alt='No és visible'}{/if}</td>
       <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editEix' eixId=$eix.eixId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button style="border:0px;background-color:transparent;" title='Esborra' alt='Esborra' onclick="javascript:esborra({$eix.eixId},'{$eix.nomCurt|@escape:quotes}');">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span><span style="padding-left:3px"><a href="{modurl modname='Cataleg' type='admin' func='prioritatsgest' eixId=$eix.eixId}">{img modname='cataleg' src='gest_prioritats.png' __title='Gestiona les prioritats d\'aquest eix' __alt='Gestiona les prioritats d\'aquest eix' style='vertical-align:-2px'}</a></span></td>
    </tr>  
       
{/foreach}
</tbody>
</table>
{else}
    <h1>{gt text="Encara no s'ha creat cap eix"}</h1>
{/if}
<script>
    function esborra($eixId,$nom) {
        var $mess = '{{gt text="Heu triat l\'eix"}} \''+$nom+'\'\n\n{{gt text="Voleu esborrar-lo?"}}';
        if (confirm($mess)) {
            window.location = "index.php?module=Cataleg&type=admin&func=deleteEix&eixId="+$eixId;
        }
    }
</script>