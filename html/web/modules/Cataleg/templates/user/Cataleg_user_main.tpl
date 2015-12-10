{*ajaxheader modname=Cataleg filename=Cataleg.js*}
{*pageaddvar name='javascript' value='jQuery'*} 
{pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació"}
{nocache}{*include file='user/Cataleg_user_menu.tpl'*}{/nocache}
{insert name='getstatusmsg'}
<div id="botons" class="z-buttonrow z-buttons">   
<h1>{$titol}
    <button style="float: right" id='btn_filter' class="z-bt-filter" type="button" onclick="javascript:cerca();"><span style="font-size:0.7em">{gt text="Cercar"}</span></button></h1>
</div>
{foreach from=$eixos item='eix'}
    <table class="z-datatable">
        <thead>
            <th colspan="2">
                <h2> Eix {$eix.ordre|safehtml}: {$eix.nom|safehtml}</h2>
            </th>
        </thead>
        <tbody>
    
        {foreach from=$prioritats item='prioritat'}
            {if $prioritat.eixId eq $eix.eixId}
            <tr class="{cycle values='z-odd,z-even'}">
                <td>{if (strlen($prioritat.prioritatsUrl.title) > 0)} <a href="{$prioritat.prioritatsUrl.url|safehtml}" title="{$prioritat.prioritatsUrl.title}"  >{/if}{$prioritat.ordre|safehtml}. {$prioritat.nom|safehtml}</a></td>
                <td width="40px">
                    {assign var='options' value=$prioritat.options}
                    {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}" >{img modname='Cataleg' src=$options[options].image title=$options[options].title alt=$options[options].title}</a>
                    {/section}
                </td>
            </tr>
            {/if}
        {/foreach}
        </tbody>
    </table>
{/foreach}

<script type="text/javascript">
function cerca(){
    window.location = "index.php?module=Cataleg&type=user&func=cerca&catId={{$catId}}";
}
</script>


