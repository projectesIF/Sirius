{* Mostra el detall del treball seleccionat *}
{insert name='getstatusmsg'}

<div id="detail" class="z-left" > 
    <div style="text-align:right">
        <a href='#' onclick="javascript:updateDisplay('list', 'detail');">{img modname='llicencies'  style="padding-right: 5px;" src='back.png' __title="Torna enrera" __alt='Torna enrera'}{gt text="Torna enrera"}</a>
    </div>
        <a href="{$docRoot}{$curs_noSlash}/memories/{$detail.codi_treball}m.pdf" target="_blank">{gt text="Memòria en format pdf"}</a><br>
    {if $detail.web neq ""}
        <a href="{$detail.web}"  target="_blank">{gt text="Pàgina web"}</a><br>
    {/if}
    {if $detail.annexos neq ""}
        <a href="{$docRoot}{$curs_noSlash}/memories/{$detail.annexos}"  target="_blank">{gt text="Documentació annexa"}</a><br>
    {/if}
    {if $detail.altres neq ""}
        <a href="{$docRoot}{$curs_noSlash}/memories/{$detail.altres}"  target="_blank">{gt text="Altres documents"}</a><br>
    {/if}
    <table>
        <tr class="{cycle values='z-odd,z-even'}">
            <td width="16%"><strong>{gt text="Autor/a"}</strong></td>
            <td>{$detail.nom}&nbsp;{$detail.cognoms}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td><strong>{gt text="Títol"}</strong></td>
            <td>{$detail.titol}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td><strong>{gt text="Modalitat"}</strong></td>
            <td>{$detail.modalitat}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td style="vertical-align:top"><strong>{gt text="Característiques"}</strong></td>
            <td>{$detail.caracteristiques|safehtml}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td style="vertical-align:top" ><strong>{gt text="Orientació"}</strong></td>
            <td>{$detail.orientacio}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td><strong>{gt text="Nivell"}</strong></td>
            <td>{$detail.nivell}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td style="vertical-align:top" ><strong>{gt text="Resum"}</strong></td>
            <td>{$detail.resum}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td><strong>{gt text="Correu electrònic"}</strong></td>
            <td>{$detail.correuel}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td><strong>{gt text="Curs"}</strong></td>
            <td>{$detail.curs}</td>
        </tr>
        <tr class="{cycle values='z-odd,z-even'}">
            <td><strong>{gt text="Capsa"}</strong></td>
            <td>{$detail.capsa}</td>
        </tr>        
    </table>
</div>