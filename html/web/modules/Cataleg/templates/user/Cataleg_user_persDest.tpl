{* DESTINATARIS DE L'ACTIVITAT ++++++++++++++++++++++++++++++++++++++++++++++ *}

<fieldset id="title">
    {assign var="n" value=$destinataris|@count}
    <input type="hidden" id="ndest" name="ndest" value={$n}>
    <legend>{gt text="Persones a qui s'adreça"}</legend>
    <table>
        <tr>
            {foreach item="dest" from=$destinataris name="idx"}
                {assign var="it" value=$smarty.foreach.idx.iteration}
                {assign var="sel" value = $dest.auxId}
                <td width="33%"><input type="checkbox" name="dest[{$dest.auxId}]" 
                    {if (isset($info.destinataris.$sel)&& ($dest.auxId == $info.destinataris.$sel))}checked = "checked"{/if}
                    value="{$dest.auxId}" /> {$dest.nom}</td>
                {if $smarty.foreach.idx.iteration is div by 3}
                    </tr><tr>
                {/if}
            {*<option value={$pr.priId} title="{$pr.nom}">{$pr.ordre} - {$pr.nomCurt}</option>*}
        {/foreach}
    </tr>
</table>
<fieldset id="obs">
    <legend style="font-size:12px">{gt text="Observacions"}</legend>
    <textarea id="obs" name="obs" maxlength="255" rows="3" cols="100" >{$info.observacions}</textarea>
</fieldset>    
</fieldset>
{* /DESTINATARIS DE L'ACTIVITAT +++++++++++++++++++++++++++++++++++++++++++++ *}
{* MODALITAT DE LA FORMACIÓ +++++++++++++++++++++++++++++++++++++++++++++++++ *}
<fieldset id="modalitat">    
    <legend>{gt text="Modalitat"}</legend>
    <table style="text-align:center;">
        <tr>
            <td width="25%">
                <select id="tcurs" name="tcurs">   
                    {gt text="Tipus"}{$info.curs}
                    {foreach item="c" from=$curs} 

                        <option value={$c.auxId} title="{$c.nom}" {if $info.curs == $c.auxId} selected="selected" {/if}>{$c.nom}</option>
                    {/foreach}
                </select>
            </td>
            <td width="25%">
                <select id="tpres" name="tpres">
                    {gt text="Presencialitat"}
                    {foreach item="p" from=$pres}
                        <option value={$p.auxId} title="{$p.nom}" {if $info.presencialitat == $p.auxId} selected="selected" {/if}>{$p.nom}</option>
                    {/foreach}
                </select>
            </td>
            <td width="25%">                
                <select id="tabast" name="tabast" onchange="javascript:toogleCentres()">
                    {gt text="Àmbit"}
                    {foreach item="a" from=$ambit}
                        <option value={$a.auxId} title="{$a.nom}" {if $info.abast == $a.auxId} selected="selected" {/if}>{$a.nom}</option>
                    {/foreach}
                </select>
            </td>
            <td width="25%">
                <div>{gt text="Hores totals: "}<input id="nhores" name="nhores" type="text" value = "{$info.hores}" maxlength="3" size="2" onkeypress="javascript:nomesDigits(event)"></input></div>
            </td>
        </tr>
    </table>
            <div style="padding: 15px" id="centres" class="z-block-content"><legend>{gt text="Relació de centres"}</legend>
    {*<fieldset id=acentres > <legend>{gt text="Relació de centres"}</legend>*}
        <input type='hidden' id="hihacentres" name="hihacentres" ></input>
        <input type='hidden' id="lcentres" name="lcentres" value="{$info.centresAct}"></input>
        <textarea id="lloc" name="lloc" title="{gt text='Introduir els codis de centre separats per comes (,). Premer la tecla <Intro> o sortiu del camp per verificar els codis.'}" rows="2" cols="100" style="font-size:12px"  onkeypress="handleEnter(this, event)" onchange="javascript:updateInfoCentres()">{$info.centresAct}</textarea>
        <div id="infoCentres"></div>
        <legend style="font-size:12px">{gt text="Observacions"}</legend>
        <textarea id="obslloc" name="obslloc" rows="2" cols="100" style="font-size:12px">{$info.centres}</textarea>                            
    {*</fieldset>*}</div>
</fieldset>

{* /MODALITAT DE LA FORMACIÓ ++++++++++++++++++++++++++++++++++++++++++++++++ *}
{* OBJECTIUS i CONTINGUTS +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
<fieldset id="obj">
    <input id='nobjs' type='hidden' value={$info.objectius|@count}></input>
    <legend>{gt text="Objectius (mínim 2)"}</legend>
    <ol>
        <li><textarea id="obj1" class="ob" name="obj[1]" rows="3" cols="100">{$info.objectius.1}</textarea></li>
        <li><textarea id="obj2" class="ob"  name="obj[2]" rows="3" cols="100">{$info.objectius.2}</textarea></li>
        <div id="d_obj3" class="ob" name="d_obj3" {if $info.objectius.3 == null}style="display:none;"{/if}>
            <li><textarea id="obj3" name="obj[3]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.objectius.3}</textarea></li></div>
        <div id="d_obj4" class="ob" {if $info.objectius.4 == null}style="display:none;"{/if}>
            <li><textarea id="obj4" name="obj[4]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.objectius.4}</textarea></li></div>
        <div id="d_obj5" class="ob" {if $info.objectius.5 == null}style="display:none;"{/if}>
            <li><textarea id="obj5" name="obj[5]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.objectius.5}</textarea></li></div>
    </ol>
    <div id="btns" style="text-align:right;">
        <a id="plus1" class="z-icon-es-add" onclick="javascript:showtxtarea('obj');" title="{gt text="Afegir àrea de text."}" href="javascript:void(0);"></a>
        <a id="minus1" class="z-icon-es-remove" style="display:none;" onclick="javascript:hidetxtarea('obj');" title="{gt text="Eliminar àrea de text"}" href="javascript:void(0);"></a>
    </div>
</fieldset>
<fieldset id="con">
    <legend>{gt text="Continguts (mínim 2)"}</legend>
    <ol>
        <li><textarea id="con1" name="con[1]" rows="3" cols="100">{$info.continguts.1}</textarea></li>
        <li><textarea id="con2" name="con[2]" rows="3" cols="100">{$info.continguts.2}</textarea></li>
        <div id="d_con3" class="co" name="d_con3" {if $info.continguts.3 == null} style="display:none;"{/if}><li>
                <textarea id="con3" name="con[3]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.3}</textarea></li></div>
        <div id="d_con4" class="co" name="d_con4" {if $info.continguts.4 == null} style="display:none;"{/if}><li>
                <textarea id="con4" name="con[4]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.4}</textarea></li></div>
        <div id="d_con5" class="co" name="d_con5" {if $info.continguts.5 == null} style="display:none;"{/if}><li>
                <textarea id="con5" name="con[5]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.5}</textarea></li></div>
        <div id="d_con6" class="co" name="d_con6" {if $info.continguts.6 == null} style="display:none;"{/if}><li>
                <textarea id="con6" name="con[6]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.6}</textarea></li></div>
        <div id="d_con7" class="co" name="d_con7" {if $info.continguts.7 == null} style="display:none;"{/if}><li>
                <textarea id="con7" name="con[7]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.7}</textarea></li></div>
        <div id="d_con8" class="co" name="d_con8" {if $info.continguts.8 == null} style="display:none;"{/if}><li>
                <textarea id="con8" name="con[8]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.8}</textarea></li></div>
        <div id="d_con9" class="co" name="d_con9" {if $info.continguts.9 == null} style="display:none;"{/if}><li>
                <textarea id="con9" name="con[9]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.9}</textarea></li></div>
        <div id="d_con10" class="co" name="d_con10" {if $info.continguts.10 == null} style="display:none;"{/if}><li>
                <textarea id="con10" name="con[10]" rows="3" cols="100" onkeyup="javascript:checkContent(this);">{$info.continguts.10}</textarea></li></div>
    </ol>
    <div id="btns2" style="text-align:right;">
        <a id="plus2" class="z-icon-es-add" onclick="javascript:showtxtarea('con');" title="{gt text="Afegir àrea de text."}" href="javascript:void(0);"></a>
        <a id="minus2" class="z-icon-es-remove" style="display:none;" onclick="javascript:hidetxtarea('con');" title="{gt text="Eliminar àrea de text"}" href="javascript:void(0);"></a>
    </div>
</fieldset>    
{* /OBJECTIUS i CONTINGUTS +++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{* OBSERVACIONS GENERALS +++++++++++++++++++++++++++++++++++++++++++++++++++++ *}
<fieldset id="fsobs_generals">
    <legend>{gt text="Observacions generals"}</legend>
    <textarea id="info" name="info" rows="3" cols="100">{$info.info}</textarea>
</fieldset>
{* /OBSERVACIONS GENERALS ++++++++++++++++++++++++++++++++++++++++++++++++++++ *}
{* ACTIVITATS PREVISTES Nº, ZONA i  MES ++++++++++++++++++++++++++++++++++++++ *}
<fieldset id="actprev">
    <legend>{gt text="Activitats previstes"}</legend>
    <table class="z-datatable" style="width:100%; border:0px">
        <thead style="text-align:center;">
            <tr>
                <th style="width:30px">{gt text="Nº"}</th>
                <th style="width:150px"></th>
                <th style="width:25px">{gt text="Inici"}</th>
                <th style="width:30px">{gt text="Nº"}</th>
                <th style="width:150px"></th>
                <th style="width:25px">{gt text="Inici"}</th>
                <th style="width:30px">{gt text="Nº"}</th>
                <th style="width:150px"></th>
                <th style="width:25px">{gt text="Inici"}</th>
            </tr>
        </thead>
        {* az -> activitats zona *}
            
        <tr class="{cycle values='z-odd,z-even'}">
            {foreach item="st" from=$sstt name="i"}
                {assign var='j' value=$st.auxId}
                {if ($st.nom)}
                    {* Id del Servei territorial *}
                    <input type="hidden" id="az[{$st.auxId}][lloc]" name="az[{$smarty.foreach.i.iteration}][lloc]" value={$st.auxId}></input>                                                  
                    {* Nombre d'activitats *}
                    <td style="width:30px"><input id="az[{$st.auxId}][qtty]" name="az[{$smarty.foreach.i.iteration}][qtty]" 
                    {if (isset($info.activitatsZona.$j))} value = "{$info.activitatsZona.$j.qtty}"{else}value=""{/if}
                    type="text" maxlength="3" style="width:25px;" onkeypress="javascript:nomesDigits(event)"></input></td>
                    <td style="width:150px">{$st.nom}</td>
                    <td style="width:30px" style="background-color: white;">
                    <select id="az[{$st.auxId}][mesInici]" name="az[{$smarty.foreach.i.iteration}][mesInici]" style="background-color: white;">
                        {*<select id="mes{$st.auxId}" name="mes{$st.auxId}"  style="background-color: white;"> *}
                        <option >{gt text=""}</option>
                        <option value=1 {if ($info.activitatsZona.$j.mesInici == '1')} selected="selected"{/if}>{gt text="gener"}</option>
                        <option value=2 {if ($info.activitatsZona.$j.mesInici == '2')} selected="selected"{/if}>{gt text="febrer"}</option>
                        <option value=3 {if ($info.activitatsZona.$j.mesInici == '3')} selected="selected"{/if}>{gt text="març"}</option>
                        <option value=4 {if ($info.activitatsZona.$j.mesInici == '4')} selected="selected"{/if}>{gt text="abril"}</option>
                        <option value=5 {if ($info.activitatsZona.$j.mesInici == '5')} selected="selected"{/if}>{gt text="maig"}</option>
                        <option value=6 {if ($info.activitatsZona.$j.mesInici == '6')} selected="selected"{/if}>{gt text="juny"}</option>
                        <option value=7 {if ($info.activitatsZona.$j.mesInici == '7')} selected="selected"{/if}>{gt text="juliol"}</option>
                        <option value=8 {if ($info.activitatsZona.$j.mesInici == '8')} selected="selected"{/if}>{gt text="agost"}</option>
                        <option value=9 {if ($info.activitatsZona.$j.mesInici == '9')} selected="selected"{/if}>{gt text="setembre"}</option>
                        <option value=10 {if ($info.activitatsZona.$j.mesInici == '10')} selected="selected"{/if}>{gt text="octubre"}</option>
                        <option value=11 {if ($info.activitatsZona.$j.mesInici == '11')} selected="selected"{/if}>{gt text="novembre"}</option>
                        <option value=12 {if ($info.activitatsZona.$j.mesInici == '12')} selected="selected"{/if}>{gt text="desembre"}</option>
                    </select>                        
                    </td>
                {else}
                    <td></td><td></td><td></td>
                {/if}
                {if $smarty.foreach.i.iteration is div by 3}</tr><tr class="{cycle values='z-odd,z-even'}">{/if}
            {/foreach}
        </tr>
    </table>
    {*
    <fieldset id=centres > <legend>{gt text="Relació de centres"}</legend>
        <input type='hidden' id="hihacentres" name="hihacentres" ></input>
        <input type='hidden' id="lcentres" name="lcentres" value="{$info.centresAct}"></input>
        <textarea id="lloc" name="lloc" title="{gt text='Introduir els codis de centre separats per comes (,). Premer la tecla <Intro> per verificar.'}" rows="2" cols="100" style="font-size:12px"  onkeypress="handleEnter(this, event)" onchange="javascript:updateInfoCentres()">{$info.centresAct}</textarea>
        <div id="infoCentres"></div>
        <legend style="font-size:12px">{gt text="Observacions"}</legend>
        <textarea id="obslloc" name="obslloc" rows="2" cols="100" style="font-size:12px">{$info.centres}</textarea>                            
    </fieldset>
    *}
</fieldset>
{* /ACTIVITATS PREVISTES Nº, ZONA i  MES ++++++++++++++++++++++++++++++++++++ *}
<script type="text/javascript">
    window.onload = inici();
    
    function inici(){
        updateInfoCentres(); //Obtenció de les dades dels codis de centre relacionats a l'activitat 
        toogleCentres();     //Establir la variable $hihacentres
    }
    
    // Si es prem la tecla "Enter" treu el focus de "lloc" i si hi ha hagut
    // canvis s'executa l'event "onchange" -> updateInfoCentres que actualitza 
    // la llista de centres.
    function handleEnter(inField, e) {
    var charCode;
    
    if(e && e.which){
        charCode = e.which;
    }else if(window.event){
        e = window.event;
        charCode = e.keyCode;
    }

    if(charCode == 13) {
       document.getElementById('obslloc').focus();
    }
}
</script>