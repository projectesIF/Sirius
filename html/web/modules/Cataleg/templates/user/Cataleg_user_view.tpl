{pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació :: Les meves activitats"}
{insert name='getstatusmsg'}
<!-- Menú flotant -->
{ajaxheader ui=true}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
    Zikula.UI.Tooltips($$('.tooltips'));
});

</script>
{/pageaddvarblock}
<h1>{$titol}</h1>
<!--div id="cat{$cataleg.catId}"-->
<h3>{$cataleg.nom|safehtml}</h3>
{if $cataleg.editable || $isGestor}
    <div><a href="{modurl modname='Cataleg' type='user' func='addnew' catId= $cataleg.catId}"><span style="text-decoration:underline;">{gt text="Afegir nova activitat"}</span></a> | 
    <a href="{modurl modname='Cataleg' type='user' func='import_ui' catId= $cataleg.catId}"><span style="text-decoration:underline;">{gt text="Importar activitats"}</span></a></div><br>
{/if}         
<div id="contingut">
{modapifunc modname='cataleg' type="user" func="getFilterlinks" id= $cataleg.catId assign="modlinks"}
<table class="z-datatable">
    <thead>
        <tr>
            <th>{gt text='Títol'}</th>
            <th>{gt text='Estat'}{if $filter neq -1}&nbsp;({$txtfilter}){/if}
                {if $modlinks}
                    <span id="mcontext" class="z-pointericon" title='{gt text ='Filtrar activitats per estat'}'>&nbsp;</span>
                {/if}
                <script type="text/javascript">
                /* <![CDATA[ */
                    {{if $modlinks}}
                        var context_mcontext = new Control.ContextMenu('mcontext',{
                            leftClick: true,
                            animation: false                            
                        });

                        {{foreach from=$modlinks item=modlink}}
                            context_mcontext.addItem({
                                label: '{{$modlink.text|safetext}}',
                                callback: function(){ window.location = '{{$modlink.url}}';}
                            });
                        {{/foreach}}

                    {{/if}}
                </script>
            </th>
            <th style="text-align:center">{gt text='Accions'}</th>
        </tr>
    </thead>
    {if (isset($cataleg.unitats))}
        <tbody>
            {assign var='allUnits' value=$cataleg.unitats}
            {foreach from=$allUnits item='unitat'}
                {*foreach from=$unitat item='un'*}                    
                {if (isset($unitat.activitats))}{*|@count) > 0*}                
                        {*<thead>                *}
                        <tr><th colspan = '2'>
                            <a class='inline' href="#inline_content-{$unitat.uniId}"  title="{gt text='Més informació de la unitat'}"><h4 class="z-block-title" style="border-top-left-radius: 0px !important; border-top-right-radius: 0px"><span style="font-size:1.2em;color:whitesmoke">{$unitat.nom}</span></a>
                            {if $cataleg.editable || $isGestor}
                                <a href="{modurl modname='Cataleg' type='admin' func='editUnitat' uniId= $unitat.uniId}">{img style="padding-right: 5px; cursor:pointer; float:left" modname="core" set="icons/small" src="xedit.png" __alt="Editar la informació de la unitat" __title='Editar la informació de la unitat'}</a>                        
                            {/if}
                            </h4>
                            </th>
                            <th>
                                {if $cataleg.editable || $isGestor}
                                    <a href="{modurl modname='Cataleg' type='user' func='tematiques' uniId= $unitat.uniId}"><span style="text-align:center"><button class="z-button">{gt text="Temàtiques"}</button></span></a>
                                {/if}
                            </th>
                        </tr>
                        {*</thead>*}
                        {*/if*}
                        {assign var='activitats' value=$unitat.activitats}
                        {foreach from=$activitats item='activitat'}
                            {*if $activitat.catId eq $cataleg.catId*}                
                <tr class="{cycle values='z-odd,z-even'} estat{$activitat.n_estat}">
                    <td width='75%'>
                        {if ($activitat.activa eq 0)}{img modname='core' set='icons/extrasmall' height="8px" width="8px" src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
                        {if ($activitat.prioritaria == 1)}{img modname='cataleg' height="8px" width="8px" src='star.png' title='Activitat prioritzada' alt='Activitat prioritzada'}{/if}
                        {if (strlen($activitat.tGTAF)> 0)}{$activitat.tGTAF}-{/if}{$activitat.titol|safehtml}</td>
                    <td>
                        {switch expr=$activitat.n_estat}
                        {case expr='0'}
                        {img modname=Cataleg src='esborrany.png' __title='Esborrany' __alt='Esborrany' class='tooltips'}
                        {/case}
                        {case expr='1'}
                        {img modname=Cataleg src='enviada.png' __title='Enviada' __alt='Enviada' class='tooltips'}
                        {/case}
                        {case expr='2'}
                        {img modname=Cataleg src='revisar.png' __title="S'ha de revisar" __alt="S'ha de revisar" class='tooltips'}
                        {/case}
                        {case expr='3'}
                        {img modname=Cataleg src='validada.png' __title='Validada' __alt='Validada' class='tooltips'}
                        {/case}
                        {case expr='4'}
                        {img modname=Cataleg src='modificada.png' __title="Modificada" __alt="Modificada" class='tooltips'}
                        {/case}
                        {case expr='5'}
                        {img modname=Cataleg src='anullada.png' __title='Anul·lada' __alt='Anul·lada' class='tooltips'}
                        {/case}
                        {/switch}
                    </td>
                    <td style="text-align:center">
                        {assign var='options' value=$activitat.options}
                        {section name='options' loop=$options}
                            <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title}</a>
                        {/section}
                    </td>
                </tr>
                {*/if*}   
            {/foreach}
        {/if}
        {*/foreach*}
        {/foreach}
{else}
    <tr><td colspan=3 style="color:red; text-align:center"><h4>{if $txtfilter}{gt text='No hi ha activitats en estat: "%s"' tag1=$txtfilter}{else}{gt text='No hi ha activitats per mostrar'}{/if}</h4></td></tr>
{/if}
</tbody>
</table>
</div>
{include file="user/Cataleg_user_display_unitat.tpl"}

