{* Cataleg_user_selectPrioritat.tpl 
Plantilla per triar prioritat i subprioritat 
Selecció automàtica de l'eix corresponent
*}
{*<div style="width:100px; cursor:pointer" onclick="javascript:mostra()">*}
{if ($priinfo.priId != null)}
    {assign var="pri" value=$priinfo.priId}   
{else}
    {assign var="pri" value=0}
{/if}

{if !isset($cerca)} {assign var="cerca" value=0}{/if}
<input type="hidden" id="cerca" name="cerca" value={$cerca} />
{if isset($cerca) && $cerca}

    <span id='priTitol' style="width:120px; font-weight:bold; cursor:pointer" onclick="javascript:mostra('pri','spri')">
        {img modname='core' title='Mostrar la llista de prioritats' src='search.png' set='icons/extrasmall'}&nbsp;{gt text="Prioritat"} </span>&nbsp;&nbsp;
    <label id='gral' onclick="javascript:mostra('pri','spri')" style="cursor:pointer">
    {if (!is_null($info.pri.priId))}{$info.pri.ordre} - {$info.pri.nom}{/if}</label>
<legend><span id='priTitol' style="font-weight:bold; cursor:pointer" onclick="javascript:mostra('pri','spri')">{img modname='core' title='Mostrar la llista de prioritats' src='search.png' set='icons/extrasmall'}&nbsp;{gt text="Prioritat"} </span>&nbsp;&nbsp;
    <label id='gral' onclick="javascript:mostra('pri','spri')" style="cursor:pointer">{if (!is_null($info.pri.priId))}{$info.pri.ordre} - {$info.pri.nom}{/if}</label></legend>

<div class='z-block flotant' id='pri'>
    <h4 class="z-block-title ">{gt text="Línies prioritàries"}</h4>
    <div class='z-block-content'>
        {if isset($cerca) && $cerca}
            <input type ='radio' id="prioritat" name="prioritat"  value=0 onclick ='javascript:amaga("pri");' onchange='javascript:setPrioritat(this, "{gt text="Qualsevol prioritat"}");'>&nbsp;&nbsp;{gt text="Qualsevol prioritat"}</input><br><hr>
        {/if}
        {foreach item="pr" from=$prioritats}
            <input type ='radio' id="prioritat" name="prioritat" value={$pr.priId} {if $pr.priId == $pri} checked="checked" {/if}  onclick ='javascript:amaga("pri")' onchange='javascript:setPrioritat(this, "{$pr.ordre} - {$pr.nom|escape:html}");'> &nbsp;{$pr.ordre} - {$pr.nom|escape:html}</input><br><hr>
        {/foreach}
    </div>
</div>


{else}

    <!--<h2 style="font-size:1.5em"><span id='priTitol' style="width:120px; font-weight:bold; cursor:pointer" onclick="javascript:mostra('pri','spri')">
    {img modname='core' title='Mostrar la llista de prioritats' src='search.png' set='icons/extrasmall'}&nbsp;{gt text="Prioritat"} </span>&nbsp;&nbsp;
    <span style='font-size:0.9em'><label id='gral' onclick="javascript:mostra('pri','spri')" style="cursor:pointer">
{if (!is_null($info.pri.priId))}{$info.pri.ordre} - {$info.pri.nom}{/if}</label></span></h2>-->
<legend><span id='priTitol' style="font-weight:bold; cursor:pointer" onclick="javascript:mostra('pri','spri')">{img modname='core' title='Mostrar la llista de prioritats' src='search.png' set='icons/extrasmall'}&nbsp;{gt text="Prioritat"} </span>&nbsp;&nbsp;
    <label id='gral' onclick="javascript:mostra('pri','spri')" style="cursor:pointer">{if (!is_null($info.pri.priId))}{$info.pri.ordre} - {$info.pri.nom}{/if}</label></legend>

<div class='z-block flotant' id='pri'>
    <h4 class="z-block-title ">{gt text="Línies prioritàries"}</h4>
    <div class='z-block-content'>
        {if isset($cerca) && $cerca}
            <input type ='radio' id="prioritat" name="prioritat"  value=0 onclick ='javascript:amaga("pri");' onchange='javascript:setPrioritat(this, "{gt text="Qualsevol prioritat"}", {$cerca});'>&nbsp;&nbsp;{gt text="Qualsevol prioritat"}</input><br><hr>
        {/if}
        {foreach item="pr" from=$prioritats}
            <input type ='radio' id="prioritat" name="prioritat" value={$pr.priId} {if $pr.priId == $pri} checked="checked" {/if}  onclick ='javascript:amaga("pri")' onchange='javascript:setPrioritat(this, "{$pr.ordre} - {$pr.nom|escape:html}");'> &nbsp;{$pr.ordre} - {$pr.nom|escape:html}</input><br><hr>
        {/foreach}
    </div>
</div>
{/if}
{* ----------------------------------------------------------------------------------*}

<script type="text/javascript"  language="javascript">
   
    function setPrioritat(obj, txt){
    // Canviem les subprioritats en funció de la prioritat triada
    getEixSubpri(obj);
    jQuery('#gral').text(txt);
    jQuery('#pri').hide('slow');
}

</script>