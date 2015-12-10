{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Gestió dels catàlegs"}</h3>
</div>
<div>
    <a href="{modurl modname='Cataleg' type='admin' func='addCat'}">{gt text="Afegeix un catàleg"}</a>
</div>
{if $cats}
    <table  id="tblCatalegs" class="z-datatable">
        <thead>
            <tr>
                <th width="5%" style="text-align:center">{gt text='Actiu'}</th>
                <th width="5%" style="text-align:center">{gt text='Treball'}</th>
                <th width="15%">{gt text='Curs'}</th>
                <th width="50%">{gt text='Catàleg'}</th>
                <th width="5%" style="text-align:center">{gt text='Estat'}</th>
                <th width="5%" style="text-align:center">{gt text='Edició'}</th>
                <th width="15%" style="text-align:center">{gt text='Accions'}</th> 
            </tr>
        </thead>
        <tbody>
            {foreach from=$cats item='cat' key='key'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td style="text-align:center">{if $cat.catId eq $catIdAc}{assign var='keyAc' value=$key}{img modname='cataleg' src='star.png' __title='Aquest és el catàleg actiu' __alt='Aquest és el catàleg actiu'}{else}<a class='inline' href="#inline_content-{$cat.catId}">{img modname='core' set='icons/extrasmall' src='empty.png' __title='Activa aquest catàleg' __alt='Activa aquest catàleg'}</a>{/if}</td>
                    <td style="text-align:center">{if $cat.catId eq $catIdTr}{assign var='keyTr' value=$key}{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Aquest és el catàleg de treball' __alt='Aquest és el catàleg de treball'}{else}<a class='inline' href="#inline_content2-{$cat.catId}">{img modname='core' set='icons/extrasmall' src='empty.png' __title='Fes-lo catàleg de treball' __alt='Fes-lo catàleg de treball'}</a>{/if}</td>
                    <td>{$cat.anyAcad}</td>
                    <td><a href="{modurl modname='Cataleg' type='user' func='view' catId=$cat.catId}">{img modname='core' set='icons/extrasmall' src='package_editors.png' __title='Les meves activitats' __alt='Les meves activitats'}</a><a href="{modurl modname='Cataleg' type='user' func='cataleg' catId=$cat.catId}" title="{gt text="Veure el catàleg"}"><span style="padding-left:10px">{$cat.nom}</span></a></td>
                    <td style="text-align:center">{if $cat.estat eq 'Cataleg_Constant::TANCAT'|constant}{img modname='cataleg' src='anullada.png' __title='Tancat' __alt='Tancat'}{elseif $cat.estat eq 'Cataleg_Constant::LES_MEVES'|constant}{img modname='cataleg' src='esborrany.png' __title='Les meves activitats' __alt='Les meves activitats'}{elseif $cat.estat eq 'Cataleg_Constant::ORIENTACIONS'|constant}{img modname='cataleg' src='enviada.png' __title='Orientacions' __alt='Orientacions'}{elseif $cat.estat eq 'Cataleg_Constant::ACTIVITATS'|constant}{img modname='cataleg' src='cat_activitats.png' __title='Activitats' __alt='Activitats'}{elseif $cat.estat eq 'Cataleg_Constant::OBERT'|constant}{img modname='cataleg' src='validada.png' __title='Obert' __alt='Obert'}{/if}</td>
                    <td style="text-align:center">{if $cat.editable eq 1}{img modname='core' set='icons/extrasmall' src='button_ok.png' __title='Es pot editar' __alt='Es pot editar'}{else}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='No es pot editar' __alt='No es pot editar'}{/if}</td>
                    <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editCat' catId=$cat.catId}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button style="border:0px;background-color:transparent;" title='{gt text="Esborra"}' alt='{gt text="Esborra"}' onclick="javascript:esborra({$cat.catId},'{$cat.nom|@escape:quotes}');">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png'}</button></span><span style="padding-left:3px"><a href="{modurl modname='Cataleg' type='admin' func='eixosgest' catId=$cat.catId}">{img modname='cataleg' src='gest_prioritats.png' __title='Gestiona les línies prioritàries' __alt='Gestiona les línies prioritàries' style='vertical-align:-2px'}</a></span><span style="padding-left:3px"><a href="{modurl modname='Cataleg' type='admin' func='unitatsgest' catId=$cat.catId}">{img modname='cataleg' src='gest_unitats.png' __title='Gestiona les unitats' __alt='Gestiona les unitats' style='vertical-align:-2px'}</a></span>                        
                        {if $CatalegAdmin}
                        <a href="{modurl modname='Cataleg' type='admin' func='document' catId=$cat.catId}">{img modname='Cataleg' src='filepdf.png' __title='Exporta a PDF' __alt='Exporta a PDF'}</a>
                        {/if}
                    </td>
                </tr> 
            {/foreach}
        </tbody>
    </table>
<script src="javascript/jquery/jquery-1.7.0.js"></script>
<script src="javascript/colorbox/jquery.colorbox.js"></script>
<script type="text/javascript"> var $jq=jQuery.noConflict(true);</script>
<link rel="stylesheet" type="text/css" href="style/colorbox.css" />

<script>
    function esborra($catId,$nom) {
       var $mess = '{{gt text="Heu triat el catàleg"}} \''+$nom+'\'\n\n{{gt text="Voleu esborrar-lo?"}}';
        if (confirm($mess)) {
            window.location = "index.php?module=Cataleg&type=admin&func=deleteCat&catId="+$catId;
        }
    }
    $jq(document).ready(function(){
    //Examples of how to assign the ColorBox event to elements
    $jq(".iframe").colorbox({iframe:true, width:"60%", height:"50%"});
    $jq(".inline").colorbox({inline:true, width:"70%", height:"80%"});
    $jq(".callbacks").colorbox({
    onOpen:function(){ alert('onOpen: colorbox is about to open'); },
onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
});
				
//Example of preserving a JavaScript event for inline calls.
$("#click").click(function(){ 
$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
return false;
});
});
</script>

{foreach from=$cats item='cat'}
    <div style='display:none'>
        <div class="news_body" id='inline_content-{$cat.catId}' style='padding:10px; background:#fff; text-align: left;'>
            <h3>{gt text="Voleu que"} '<b>{$cat.nom}</b>' {gt text="sigui el catàleg actiu?"}</h3>
            <div style="margin-left:25px"><br>{gt text="Heu de tenir present que:"}<br><br><ul>
                    <li>{if $cat.estat eq 'Cataleg_Constant::OBERT'|constant}{gt text="El catàleg"} '<b>{$cat.nom}</b>' {gt text="continuarà en estat 'Obert'"}.{else}{gt text="El catàleg"} '<b>{$cat.nom}</b>' {gt text="passarà a estat 'Obert'"}.{/if}</li><br>
                    <li>{gt text="Com a catàleg actiu, serà el que es podrà consultar per defecte."}</li><br>
                    {if $catIdAc gt -1}
                        <li>{gt text="El catàleg que estava actiu"} ('<b>{$cats.$catIdAc.nom}</b>') {gt text="seguirà estant en estat 'Obert' fins que no es canviï en la gestió de catàlegs"}.</li><br>
                    {else}
                        <li>{gt text="No hi havia cap catàleg actiu"}</li><br>
                    {/if}
                </ul>
            </div>
            <div class="z-buttonrow z-buttons z-center">
                <a href="{modurl modname='Cataleg' type='admin' func='setActiveCataleg' catIdNouAc=$cat.catId}" title="{gt text="Activa el catàleg"}">Activa el catàleg '<b>{$cat.nom}</b>'</a>
            </div>
        </div></div>
     <div style='display:none'>
        <div class="news_body" id='inline_content2-{$cat.catId}' style='padding:10px; background:#fff; text-align: left;'>
            <h3>{gt text="Voleu que"} '<b>{$cat.nom}</b>' {gt text="sigui el catàleg de treball?"}</h3>
            <div style="margin-left:25px"><br>{gt text="Heu de tenir present que:"}<br><br><ul>
                    <li>{if $cat.estat neq 'Cataleg_Constant::TANCAT'|constant}{gt text="El catàleg"} '<b>{$cat.nom}</b>' {gt text="continuarà en l'estat de visualització que tenia, que ja era visible per als editors"}.{else}{gt text="El catàleg"} '<b>{$cat.nom}</b>' {gt text="passarà a estat de visualització 'Les_meves' per a ser visible per les persones editores"}.{/if}</li><br>
                    <li>{gt text="Com a catàleg de treball, serà el que veuran les persones editores per defecte"}.</li><br>
                    {if $catIdTr gt -1}
                        <li>{gt text="El catàleg de treball fins ara"} ('<b>{$cats.$catIdTr.nom}</b>') {gt text="seguirà estant en l'estat de visualització que tenia fins que no es canviï en la gestió de catàlegs"}.</li><br>
                    {else}
                        <li>{gt text="No hi havia cap catàleg de treball fins ara"}.</li><br>
                    {/if}
                    <li>{gt text="L'editabilitat del catàleg no canviarà amb aquesta acció"}.</li>
                </ul>
            </div>
            <div class="z-buttonrow z-buttons z-center">
                <a href="{modurl modname='Cataleg' type='admin' func='setTreballCataleg' catIdNouTr=$cat.catId}" title="{gt text="Canvia el catàleg de treball"}">Fes de '<b>{$cat.nom}</b>' el catàleg de treball</a>
            </div>
        </div></div>     
{/foreach}
{else}
    <h1>{gt text = "Encara no s'ha creat cap catàleg"}</h1>
{/if}
