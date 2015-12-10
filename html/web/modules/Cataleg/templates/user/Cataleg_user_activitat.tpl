{* Cataleg_user_activitat.tpl *}
{* Plantilla principal per a la consulta d'una fitxa d'activitat al catàleg *}
{pageaddvar name="title" value="Sirius :: Catàleg Unificat de Formació :: Fitxa d'activitat"}
{insert name='getstatusmsg'}
{*<a style="cursor:pointer" onclick="javascript:history.go(-1);" title="{gt text = "Tornar"}" alt="{gt text = "Tornar"}" >{img modname='cataleg' src='tornar.png'}</a> *}
{if $back eq 'view'}
    {modapifunc modname='cataleg' type="user" func="genUrl" o=$back catId=$activitat.cataleg.catId assign="goto"}
{/if}
{if $back eq 'acts'}
    {modapifunc modname='cataleg' type="user" func="genUrl" o=$back priId=$activitat.pri.priId assign="goto"}
{/if}
{if $back}
<div><a style="cursor:pointer" href='{$goto}' title="{gt text = "Tornar"}" alt="{gt text = "Tornar"}" ><span style="text-decoration:underline; font-size:10px">{gt text="Tornar"'}</span></a></div><br>
{/if}
<div class="usercontainer">
    <div class="userpageicon">        
        {if $canEdit}
            <a href="{modurl modname="Cataleg" type="user" func="edit" back=$back id=$activitat.actId show=true}" title="{gt text='Edita l\'activitat'}" alt="{gt text='Edita l\'activitat'}">{img modname='core' src='xedit.png' set='icons/medium' } </a>
        {/if}
        <a href="{modurl modname="Cataleg" type="user" func="show" pdf=true actId=$activitat.actId}" title="{gt text='Exporta a PDF'}" alt="{gt text='Exporta a PDF'}" target="_blank">{img modname='Cataleg' src='pdf.jpg' style="float:right; margin: 0 0 15px 15px;"} </a>
    </div>
    <h2 style="text-align:left">{gt text="Fitxa d'activitat"}:&nbsp;{$activitat.cataleg.nom}</h2><br />
    {* TÍTOL *}
    <form class='z-form'>
        <input type='hidden' name='back' value={$back}></input>
        <fieldset>
            <legend> {gt text="Activitat"}</legend>
            <h2 style="font-size:1.5em"><span style="font-weight:bold;" >{gt text="Títol"}</span>&nbsp;&nbsp;
            {if ($activitat.activa eq 0)}{img modname='core' set='icons/extrasmall' src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
            {if ($activitat.prioritaria == 1)}{img modname='cataleg' src='star.png' title='Activitat prioritzada' alt='Activitat prioritzada'}{/if}
            <span style='font-size:0.9em'>
            {if (strlen($activitat.tGTAF)>0)}{$activitat.tGTAF}&nbsp;-&nbsp;{/if}
            {$activitat.titol}</span></h2>                                    
            {* EIX *}
            {if $activitat.eix.nom != ""}   
        <h2 style="font-size:1.5em"><span style="font-weight:bold;" >{gt text="Eix"}</span>&nbsp;&nbsp;
            <span style='font-size:0.9em'>{$activitat.eix.ordre} - {$activitat.eix.nom}</span></h2>
        {/if}
    <h2 style="font-size:1.5em"><span style="font-weight:bold;" >{gt text="Prioritat"}</span>&nbsp;&nbsp;
        <span style='font-size:0.9em'>{$activitat.pri.ordre} - {$activitat.pri.nom}</span></h2>
        {if isset($activitat.spr.nom)}
        <h2 style="font-size:1.5em"><span style="font-weight:bold;" >{gt text="Subprioritat"}</span>&nbsp;&nbsp;
            <span style='font-size:0.9em'>{$activitat.spr.ordre} - {$activitat.spr.nom}</span></h2>
        {/if}
</fieldset>
{* DESTINATARIS DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++ *}
{if ($destinataris|@count)}
    <fieldset>
        <legend>{gt text="Persones a qui s'adreça"}</legend>

        {foreach item="dest" from=$destinataris name='i'}
        {$dest}{if (($destinataris|@count) > $smarty.foreach.i.iteration)}&nbsp;-&nbsp;{/if}
    {/foreach}
    {if strlen($activitat.observacions)}
        <fieldset>

            <legend style="font-size:12px">{gt text="Observacions"}</legend>
            <label id="obs" name="obs" maxlength="255" rows="3" cols="100" >{$activitat.observacions|nl2br}</label>
        </fieldset>    
    {/if}
</fieldset>
{/if}
{* /DESTINATARIS DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++ *}
{* MODALITAT DE LA FORMACIÓ +++++++++++++++++++++++++++++++++++++++++++++++++ *}
{if ($modalitat|@count)}
    <fieldset id="modalitat">    
        <legend>{gt text="Modalitat"}</legend>
        {*foreach item="m" from=$modalitat*}
        {$modalitat.0}&nbsp;&nbsp;{$modalitat.1|@lower}&nbsp;::&nbsp;{$modalitat.2}<br>
        {gt text = "Durada: "}{$activitat.hores}&nbsp;{gt text = "hores"}    
    </fieldset>
{/if}
{* /MODALITAT DE LA FORMACIÓ ++++++++++++++++++++++++++++++++++++++++++++++++ *}
{* OBJECTIUS i CONTINGUTS +++++++++++++++++++++++++++++++++++++++++++++++++++ *}

{if ((isset($activitat.objectius)) &&($activitat.objectius.1 != null))}
    <fieldset>
        <legend>{gt text="Objectius"}</legend>
        <ul style="text-align:justify">
            <li>{$activitat.objectius.1|nl2br}</li>
            <li>{$activitat.objectius.2|nl2br}</li>
            <div id="d_obj3" class="ob" name="d_obj3" {if $activitat.objectius.3 == null}style="display:none;"{/if}>
                <li><label id="obj3" name="obj[3]" maxlength="500" rows="3" cols="100" >{$activitat.objectius.3|nl2br}</label></li></div>
            <div id="d_obj4" class="ob" {if $activitat.objectius.4 == null}style="display:none;"{/if}>
                <li><label id="obj4" name="obj[4]" maxlength="500" rows="3" cols="100" >{$activitat.objectius.4|nl2br}</label></li></div>
            <div id="d_obj5" class="ob" {if $activitat.objectius.5 == null}style="display:none;"{/if}>
                <li><label id="obj5" name="obj[5]" maxlength="500" rows="3" cols="100" >{$activitat.objectius.5|nl2br}</label></li></div>
        </ul>
    </fieldset>
{/if}
{if ((isset($activitat.continguts)) &&($activitat.continguts.1 != null))}
    <fieldset id="con">
        <legend>{gt text="Continguts"}</legend>
        <ul style="text-align:justify">
            <li><label id="con1" name="con[1]" maxlength="500" rows="3" cols="100">{$activitat.continguts.1|nl2br}</label></li>
            <li><label id="con2" name="con[2]" maxlength="500" rows="3" cols="100">{$activitat.continguts.2|nl2br}</label></li>
            <div id="d_con3" class="co" name="d_con3" {if $activitat.continguts.3 == null} style="display:none;"{/if}><li>
                    <label id="con3" name="con[3]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.3|nl2br}</label></li></div>
            <div id="d_con4" class="co" name="d_con4" {if $activitat.continguts.4 == null} style="display:none;"{/if}><li>
                    <label id="con4" name="con[4]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.4|nl2br}</label></li></div>
            <div id="d_con5" class="co" name="d_con5" {if $activitat.continguts.5 == null} style="display:none;"{/if}><li>
                    <label id="con5" name="con[5]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.5|nl2br}</label></li></div>
            <div id="d_con6" class="co" name="d_con6" {if $activitat.continguts.6 == null} style="display:none;"{/if}><li>
                    <label id="con6" name="con[6]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.6|nl2br}</label></li></div>
            <div id="d_con7" class="co" name="d_con7" {if $activitat.continguts.7 == null} style="display:none;"{/if}><li>
                    <label id="con7" name="con[7]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.7|nl2br}</label></li></div>
            <div id="d_con8" class="co" name="d_con8" {if $activitat.continguts.8 == null} style="display:none;"{/if}><li>
                    <label id="con8" name="con[8]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.8|nl2br}</label></li></div>
            <div id="d_con9" class="co" name="d_con9" {if $activitat.continguts.9 == null} style="display:none;"{/if}><li>
                    <label id="con9" name="con[9]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.9|nl2br}</label></li></div>
            <div id="d_con10" class="co" name="d_con10" {if $activitat.continguts.10 == null} style="display:none;"{/if}><li>
                    <label id="con10" name="con[10]" maxlength="500" rows="3" cols="100" >{$activitat.continguts.10|nl2br}</label></li></div>
        </ul>
    </fieldset>
{/if}
{* /OBJECTIUS i CONTINGUTS +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{if (isset($activitat.info) && $activitat.info!=null)}
    <fieldset id="fsobs_generals">
        <legend>{gt text="Observacions generals"}</legend>
        <label id="info" name="info" rows="3" cols="100">{$activitat.info}</label>
    </fieldset>
{/if}
{* ACTIVITATS PREVISTES Nº, ZONA i  MES ++++++++++++++++++++++++++++++++++++++ *}

{if ($actsZona|@count)}

    <fieldset id="actprev">
        <legend>{gt text="Activitats previstes"}</legend>
        <table class="z-datatable" style="width:100%; border:0px">
            <thead style="text-align:center;">
                <tr>
                    <th style="width:30px">{gt text="Nº"}</th>
                    <th style="width:150px"></th>
                    <th style="width:25px">{gt text="Inici"}</th>
                    {if ($actsZona|@count)> 1}
                        <th style="width:30px">{gt text="Nº"}</th>
                        <th style="width:150px"></th>
                        <th style="width:25px">{gt text="Inici"}</th>
                    {/if}
                    {if ($actsZona|@count)> 2}
                        <th style="width:30px">{gt text="Nº"}</th>
                        <th style="width:150px"></th>
                        <th style="width:25px">{gt text="Inici"}</th>
                    {/if}
                </tr>
            </thead>
            <tr class="{cycle values='z-odd,z-even'}">
                {foreach item="st" from=$actsZona name="i"}
                    <td style="text-align:center;width:30px">{$st.qtty}</td>
                    <td style="width:150px">{$st.lloc}</td>
                    <td style="text-align:center;width:30px">{$st.mes}</td>
                    {if $smarty.foreach.i.iteration is div by 3}
                    </tr><tr class="{cycle values='z-odd,z-even'}">
                    {/if}
                {/foreach}
            </tr>
        </table>
    {/if}
    {if ($centres|@count)}
        <fieldset id=centres > <legend>{gt text="Relació de centres"}</legend>
            {foreach item='centre' from=$centres}
                {$centre.CODI_ENTITAT} &nbsp; {$centre.NOM_TIPUS_ENTITAT}&nbsp;{$centre.NOM_ENTITAT}&nbsp; - {$centre.NOM_LOCALITAT}&nbsp; ({$centre.NOM_DT}) <br>
            {/foreach}
            {if (strlen($activitat.centres))}
                <br>
                <legend style="font-size:12px">{gt text="Observacions"}</legend>
                <label id="obslloc" name="obslloc" rows="2" cols="100" style="font-size:12px">{$activitat.centres|nl2br}</label>
            {/if}
        </fieldset>
    {/if}
</fieldset>
{* /ACTIVITATS PREVISTES Nº, ZONA i  MES ++++++++++++++++++++++++++++++++++++ *}
{* GESTIÓ DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{if (isset($gestio.0.text))}
    <fieldset>
        <legend>{gt text="Gestió"}</legend>
        <div id="elemGestio">
            <table>
                {foreach item="op" from=$gestio}    
                    {if (isset($op.text))}
                        <tr>
                            <td>{$op.text}</td>
                            <td>{img modname='core' set='icons/extrasmall' src='tab_right.png' align="center" }</td>
                            <td>{$op.srv}</td>
                        </tr>
                    {/if}
                {/foreach}
            </table>
        </div>
    </fieldset>
{/if}
{* /GESTIÓ DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{* UNITAT RESPONSABLE DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++ *}

<fieldset>    
    <legend>{gt text="Unitat responsable"}</legend>
    {if (strlen($unitat))}
        <a class='inline' href="#inline_content-{$unitats.0.uniId}">{$unitat|safehtml}</a>
        {*<span style="font-weight:bold;">{$unitat}</span>*}
    {else} {gt text="No s'especifica"}
    {/if}
    {if ($activitat.contactes|@count)}
        <table width='90%'>
            <tr><td>                    
                    <TABLE class="z-datatable" ID="contactes" CELLPADDING=0 BORDER=0>
                        <thead>
                            <tr>
                                <th width=40%>{gt text="Persona de contacte"}</th>
                                <th width=30%>{gt text="Correu"}</th>
                                <th width=30%>{gt text="Telèfon/ext."}</th>
                            </tr>
                        </thead>
                        <tbody>

                            {foreach item='c' from=$activitat.contactes name="i"}
                                <tr>
                                    <td width=40%>{$c.pContacte}</td>
                                    <td width=30%>{$c.email}</td>
                                    <td width=30%>{$c.telefon}</td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </TABLE>
                </td>
            </tr>
        </table>
    {/if}
</fieldset>
{* /UNITAT RESPONSABLE DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++ *}      
</form>
{if $back}
    <div><a style="cursor:pointer" href='{$goto}' title="{gt text = "Tornar"}" alt="{gt text = "Tornar"}" ><span style="text-decoration:underline; font-size:10px">{gt text="Tornar"'}</span></a></div><br>
{/if}
<div style="text-align:right;font-style:italic">  
    <a href="{modurl modname="Cataleg" type="user" func="show" pdf="1" actId=$activitat.actId}" title="{gt text='Exporta a PDF'}" alt="{gt text='Exporta a PDF'}">{img modname='Cataleg' src='pdf.jpg' style="float:right; margin: 0 0 10px 10px;"} </a>
    <div id="container" style="text-align:right;">
        {if $activitat.estat ge 'Cataleg_Constant::VALIDADA'|constant}{gt text='Afegida al catàleg: '}{$activitat.dataVal}<br>{/if}
        {gt text='Darrera modificació: '}{$activitat.lu_date}
    </div>
</div>
</div>  
{include file="user/Cataleg_user_display_unitat.tpl"}