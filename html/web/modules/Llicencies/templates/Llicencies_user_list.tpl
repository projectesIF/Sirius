{* Mostra la llista de treballs que compleixen els criteris de cerca *}
<style>
    .z-infomsg{
        background:rgb(255, 255, 255)!important; 
        border: 2px solid rgb(150, 30, 17) !important;
        color: rgb(173,33,20) !important;
        padding: 5px 5px 5px 5px !important;        
    }
</style>
{*rgb(173, 39, 19) *}
{insert name='getstatusmsg'}   
<div id="content" style="font-size:1.1em">
    <table class="z-left" style="width:100%;">
        <tr>
            <td style="padding-bottom:15px; padding-top:15px; width:75%;">
                {if $nc } {*Hi ha algun criteri de cerca*}
                    <div class="z-infomsg" style="float:left">
                    {gt text="Esteu buscant treballs que compleixin els següents criteris:"}&nbsp;
                    {if isset($autor) && $autor ne ""}{gt text = "El <strong>nom</strong> i/o <strong>cognoms</strong> de l'autor/a contenen el text "}<strong>{$autor}</strong>.&nbsp;{/if}
                    {if isset($titol) && $titol ne ""}{gt text = "El <strong>títol</strong> de la llicència conté el text "}<strong>{$titol}</strong>.&nbsp;{/if}
                    {if isset($tema) && $tema ne ""}{gt text="El tema és "}<strong>{$tema}</strong>.&nbsp;{/if}
                    {if isset($subtema) && $subtema ne ""}{gt text="El subtema és "}<strong>{$subtema}</strong>.&nbsp;{/if}
                    {if isset($tipus) && $tipus ne ""}{gt text="El tipus és "}<strong>{$tipus}</strong>.&nbsp;{/if}
                    {if isset($curs) && $curs ne ""}{gt text="Han estat realitzats el curs "}<strong>{$curs}</strong>.&nbsp;{/if}
                    {if $list|@count gt 1}
                        <br>{gt text='Hi ha <strong>%u</strong> llicències que compleixen els criteris de cerca.' tag1=$list|@count}
                    {else}
                        <br>{gt text="Hi ha <strong>%u</strong> llicència que compleix els criteris de cerca." tag1=$list|@count}
                    {/if}
                    </div>
                {else}
                    {*No hi ha criteris especificats: es mostren tots els treballs*}
                    <div class="z-infomsg" style="float:left">
                        {gt text="No s'ha especificat cap criteri. Es mostren els <b>%u</b> treballs existents." tag1=$list|@count}&nbsp;
                    </div>
                {/if}
            </td>
            <td class="z-right" style="padding-right:5px">
                <div id="showSearchLink" class="z-right">
                    <a href='#' onclick="javascript:updateSearchLink('hideSearchLink', 'showSearchLink', 1);">{img modname='llicencies'  style="vertical-align:middle; padding-right: 5px;" src='show.png' __title="Mostra el cercador" __alt='Mostra el cercador'}{gt text="Mostra el cercador"}</a>
                </div>
                <div style="display:none;" id="hideSearchLink" class="z-right">
                    <a href='#' onclick="javascript:updateSearchLink('showSearchLink', 'hideSearchLink', 0);">{img modname='llicencies'  style="vertical-align:middle; padding-right: 5px;" src='hide.png' __title="Amaga el cercador" __alt='Amaga el cercador'}{gt text="Amaga el cercador"}</a>
                </div>
                <div style="text-align:right">
                    <a href='#' onclick="javascript:updateDisplay('search', 'list', 'detail');">{img modname='llicencies'  style="padding-right: 5px;" src='back.png' __title="Torna enrera" __alt='Torna enrera'}{gt text="Torna enrera"}</a>
                </div>
            </td>
        </tr>
    </table>
 
    {if $list|@count}                 
        {* construim la taula amb les columnes autor (link a la fitxa del treball i títol de la llicència*}
        <table id='tlist' class="z-left"> 
            <thead>
                <tr>
                    <th width="220px">{gt text='Autor/a'}</th>
                    <th>{gt text='Títol del treball'}</th>
                        {if $admin eq "true"}
                        <th>{gt text='Accions'}</th>
                        {/if}
                </tr>
            </thead>
            <tbody>
                {foreach from=$list item='treball'}
                    <tr class="{cycle values='z-odd,z-even'}">                        
                        <td>                    
                            {switch expr=$treball.link}
                            {case expr='c'}
                                <a href='#' onclick="javascript:detail({$treball.codi_treball});">
                            {/case}
                            {case expr='u'}
                                <a href="{$treball.url}" target="_blank">
                            {/case}
                            {case expr='#'}
                                <a href="#" onclick="alert('Aquest és un treball de l\'any ' + '{$treball.curs}' + '. No el tenim en suport digital.\n\rSi us interessa, poseu-vos en contacte amb la biblioteca del Departament d\'Ensenyament \n\rTèl: 935 516 900 ext. 3861 i 3417');" >
                            {* No es disposa de suport digital -> biblioteca*}
                            {/case}
                            {case}
                                <a href='#' onclick='alert("La documentació corresponent a aquesta llicència no està disponible.");'>
                            {/case}
                            {/switch}
                            {$treball.nom|safehtml}&nbsp{$treball.cognoms}</a>
                        </td>
                        <td>{$treball.titol|safehtml}</td>
                        {if $admin eq 'true'}
                            <td>
                                <a href="#" onclick="javascript:edit({$treball.codi_treball});">{img class="tooltips" style="cursor:pointer;" modname="core" set="icons/extrasmall" src="xedit.png" __alt="Editar" __title='Editar'}</a>&nbsp;
                                <a href="#" onclick="javascript:lremove({$treball.codi_treball});">{img class="tooltips" style="cursor:pointer;" modname="core" set="icons/extrasmall" src="14_layer_deletelayer.png" __alt="Esborrar" __title='Esborrar'}</a>
                            </td>    
                        {/if}
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <br>{gt text="No s'ha trobat cap llicència que compleixi els criteris especificats."}<br>
    {/if}
</div>
