{* TÍTOL 
<div><span style="font-weight:bold;" >{gt text="Títol: "}</span>*}
    <h3>
{if (strlen($activitat.tGTAF)>0)}{$activitat.tGTAF}&nbsp;-&nbsp;{/if}
{if ($activitat.activa eq 0)}{img modname='core' set='icons/extrasmall' src='button_cancel.png'}{/if}
{if ($activitat.prioritaria == 1)}{img modname='Cataleg' src='star.jpg'}   
    &nbsp;{/if}{$activitat.titol}</h3>
{*if ($activitat.prioritaria == 1)}{img modname='Cataleg' src='star.jpg'}{gt text='(activitat prioritzada)'}{/if*}
{* EIX *}
{if $activitat.eix.nom != ""}
    <span style="font-weight:bold;" >{gt text="Eix: "}</span>
    {$activitat.eix.ordre} - {$activitat.eix.nom}<br />
{/if}
<span style="font-weight:bold;">{gt text="Prioritat: "}</span>
{$activitat.pri.ordre} - {$activitat.pri.nom}<br />
{if isset($activitat.spr.nom)}
    <span style="font-weight:bold;">{gt text="Subprioritat: "}</span>
    {$activitat.spr.ordre} - {$activitat.spr.nom}<br>
{/if}
<hr></div>
{* DESTINATARIS DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++ *}
{if ($destinataris|@count > 0)}
<span style="font-weight:bold;" >{gt text="Persones a qui s'adreça:"}</span><br>
{foreach item="dest" from=$destinataris name='i'}
    {$dest}<br>
{/foreach}
{/if}
<br>
{if strlen($activitat.observacions)}
    <span style="font-weight:bold;" >{gt text="Observacions:"}</span><br>
    {$activitat.observacions|nl2br}
    <br><br>
{/if}
{* /DESTINATARIS DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++ *}
{* MODALITAT DE LA FORMACIÓ +++++++++++++++++++++++++++++++++++++++++++++++++ *}
{if ($modalitat|@count)}
    <span style="font-weight:bold;">{gt text="Modalitat"}</span><br>
    <span style="font-weight:bold;">{gt text="Tipus: "}</span>{$modalitat.0}&nbsp;
    {$modalitat.1|@lower}&nbsp;&nbsp;
    <span style="font-weight:bold;">{gt text="Àmbit: "}</span>{$modalitat.2}<br>
    <span style="font-weight:bold;">{gt text = "Durada: "}</span>{$activitat.hores}&nbsp;{gt text = "hores"}<br><hr>&nbsp;<br>
{/if}
{* /MODALITAT DE LA FORMACIÓ ++++++++++++++++++++++++++++++++++++++++++++++++ *}
{* OBJECTIUS i CONTINGUTS +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{if ((isset($activitat.objectius)) &&($activitat.objectius.1 != null))}
    <span style="font-weight:bold;">{gt text="Objectius"}</span>
    <ul style="text-align:justify">
    {if $activitat.objectius.1 != null}<li>{$activitat.objectius.1|nl2br}</li>{/if}
{if $activitat.objectius.2 != null}<li>{$activitat.objectius.2|nl2br}</li>{/if}
{if $activitat.objectius.3 != null}<li>{$activitat.objectius.3|nl2br}</li>{/if}
{if $activitat.objectius.4 != null}<li>{$activitat.objectius.4|nl2br}</li>{/if}
{if $activitat.objectius.5 != null}<li>{$activitat.objectius.5|nl2br}</li>{/if}
</ul>
{/if}
{if ((isset($activitat.continguts)) &&($activitat.continguts.1 != null))}
    <span style="font-weight:bold;">{gt text="Continguts"}</span>
    <ul style="text-align:justify">
    {if $activitat.continguts.1 != null} <li>{$activitat.continguts.1|nl2br}</li>{/if}
{if $activitat.continguts.2 != null} <li>{$activitat.continguts.2|nl2br}</li>{/if}
{if $activitat.continguts.3 != null} <li>{$activitat.continguts.3|nl2br}</li>{/if}
{if $activitat.continguts.4 != null} <li>{$activitat.continguts.4|nl2br}</li>{/if}
{if $activitat.continguts.5 != null} <li>{$activitat.continguts.5|nl2br}</li>{/if}
{if $activitat.continguts.6 != null} <li>{$activitat.continguts.6|nl2br}</li>{/if}
{if $activitat.continguts.7 != null} <li>{$activitat.continguts.7|nl2br}</li>{/if}
{if $activitat.continguts.8 != null} <li>{$activitat.continguts.8|nl2br}</li>{/if}
{if $activitat.continguts.9 != null} <li>{$activitat.continguts.9|nl2br}</li>{/if}
{if $activitat.continguts.10 != null} <li>{$activitat.continguts.10|nl2br}</li>{/if}
</ul>
<hr>&nbsp;<br />
{/if}
{* /OBJECTIUS i CONTINGUTS +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{if ($activitat.info != null)}
    <span style="font-weight:bold;">{gt text="Observacions generals"}</span><br />
    {$activitat.info}
    <br />&nbsp;<br />
{/if}
{* ACTIVITATS PREVISTES Nº, ZONA i  MES ++++++++++++++++++++++++++++++++++++++ *}
{if ($actsZona|@count)}
    <span style="font-weight:bold;">{gt text="Activitats previstes:"}</span><br><br>
    <table class="gridtable">
        <thead style="text-align:center;">   
            <tr>
                <th style="width:30px;">{gt text="Nº"}</th>
                <th style="width:200px">{gt text="Lloc"}</th>
                <th style="width:100px">{gt text="Inici"}</th>
            </tr>
        </thead>

        {foreach item="st" from=$actsZona name="i"}
            <tr class="{cycle values='z-odd,z-even'}">
                <td style="text-align:left;width:30px">{$st.qtty}</td>
                <td style="width:200px">{$st.lloc}</td>
                <td style="text-align:left;width:100px">{$st.mes}</td>
            </tr>
        {/foreach}
    </table>
    <br>
{/if}
{if ($centres|@count)}
    <span style="font-weight:bold;">{gt text="Relació de centres"}</span><br>
    {foreach item='centre' from=$centres}
        {$centre.CODI_ENTITAT} &nbsp; {$centre.NOM_TIPUS_ENTITAT}&nbsp;{$centre.NOM_ENTITAT}&nbsp; - {$centre.NOM_LOCALITAT}&nbsp; ({$centre.NOM_DT}) <br>
    {/foreach}
    {if (strlen($activitat.centres))}
        <br><span style="font-weight:bold;">{gt text="Observacions:"}</span><br>
        {$activitat.centres|nl2br}<br>
    {/if}
{/if}

{* /ACTIVITATS PREVISTES Nº, ZONA i  MES ++++++++++++++++++++++++++++++++++++ *}
{* GESTIÓ DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{if (isset($gestio.0.text))}
    <span style="font-weight:bold;"><br>{gt text="Gestió de l'activitat:"}</span><br>
    <table  class="gridtable noborder">
        {foreach item="op" from=$gestio}           
            {if strlen($op.text)>0}
                <tr>     
                    <td style="width:50%;">{$op.text}</td>
                    <td style="width:30px;">{img modname='core' set='icons/extrasmall' src='move_right.png' align="center" }</td>
                    <td>{$op.srv}</td>
                </tr>
            {/if}
        {/foreach}
    </table>
    <br>
{/if}
{* /GESTIÓ DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{* UNITAT RESPONSABLE DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++ *}

<span>{gt text="Unitat responsable:"}</span><br>
{if (strlen($unitat))}
    <span style="font-weight:bold;">
        {$unitat}</span><br><br>
    {else} 
        {gt text="No s'especifica"}<br><br>
{/if}
{if ($activitat.contactes|@count)}
    <TABLE class="gridtable">
        <thead>
            <tr>
                <th style="width:40%">{gt text="Persona de contacte"}</th>
                <th style="width:35%">{gt text="Correu"}</th>
                <th style="width:25%">{gt text="Telèfon/ext."}</th>
            </tr>
        </thead>
        <tbody>
            {if ($activitat.contactes|@count)}
                {foreach item='c' from=$activitat.contactes name="i"}
                    <tr>
                        <td>{$c.pContacte}</td>
                        <td>{$c.email}</td>
                        <td style="text-align: center;">{$c.telefon}</td>
                    </tr>
                {/foreach}                        
            {/if}
        </tbody>
    </TABLE>
{/if}

<style type="text/css">
    table.noborder td {
        border-style: none;
    }
    table.gridtable {	
        color:#333333;
        margin-left: auto;
        margin-right: auto;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable th {
        border-width: 1px;
        padding: 2px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        font-size:0.9em;
    }
    table.gridtable td {
        border-width: 1px;
        padding: 2px;
        border-style: dotted;
        border-color: #666666;
        background-color: #ffffff;
        text-align:baseline;
        font-size:0.9em;
    }
</style>