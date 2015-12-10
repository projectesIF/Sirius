{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Edició de la taula d'importació d'activitats entre catàlegs"}</h3>
</div>
<h2>{$catOri.nom} --> {$catDest.nom} </h2>
<form name="editImportTaula" id="importTaula" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="item[importId]" value="{$iTaula.importId}">
    <table class="z-datatable">
{foreach from=$eixosOri item='eixOri'}
    <thead>
        <tr>
            <td><h4 class="z-block-title" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">{$eixOri.ordre}. {$eixOri.nomCurt}</h4></td>
        </tr>
    </thead>
    {foreach from=$eixOri.prioritats item='epOri'}
        <thead>
        <tr><th style="background: linear-gradient(#FAFAFA, #999999) repeat scroll 0 0">{$epOri.ordre}- {$epOri.nomCurt}</th></tr>
        </thead>
        <tbody>
        <tr class="{cycle values='z-odd,z-even'}"><td>
            <label for"item{$epOri.priId}o">{$epOri.ordre}-{gt text="Sense subprioritat"}</label>
            <br>
            <span style="margin-left:20px;"><select id="item{$epOri.priId}o" name="item[{$epOri.priId}o]" style="width:75%;font-size:0.9em;background-color:#FAFAFA;">
                    <option value=''>{gt text="Sense assignar"}</option>
                    {foreach from=$eixosDest item='eixDest'}
                        <optgroup label="{gt text='Eix:'}{$eixDest.ordre}. {$eixDest.nomCurt}">
                        {foreach from=$eixDest.prioritats item='epDest'}
                            <optgroup label="{$epDest.ordre}-{$epDest.nomCurt}">
                            {assign var='index' value=$epOri.priId|cat:"o"}
                            {assign var='result' value=$epDest.priId|cat:"d"}
                            <option value="{$epDest.priId}d" {if $assigna[$index] == $result}selected{/if}>{$epDest.ordre}-{gt text="Sense subprioritat"}</option>
                            {foreach from=$epDest.subprioritats item='epsDest'}
                                {assign var='index' value=$epOri.priId|cat:"o"}
                                {assign var='result' value=$epsDest.priId|cat:"d"|cat:$epsDest.sprId}
                                <option value="{$epsDest.priId}d{$epsDest.sprId}" {if $assigna[$index] == $result}selected{/if}>{$epDest.ordre}{$epsDest.ordre}-{$epsDest.nomCurt}</option>
                            {/foreach}
                            </optgroup>
                        {/foreach}
                        </optgroup>
                    {/foreach}
            </select></span>
        </td></tr>
        {foreach from=$epOri.subprioritats item='epsOri'}
            <tr class="{cycle values='z-odd,z-even'}"><td>
               <label for="item{$epOri.priId}o{$epsOri.sprId}">{$epOri.ordre}{$epsOri.ordre}-{$epsOri.nomCurt}</label>
               <br>
               <span style="margin-left:20px;"><select id="item{$epOri.priId}o{$epsOri.sprId}" name="item[{$epOri.priId}o{$epsOri.sprId}]" style="width:75%;font-size:0.9em;background-color:#FAFAFA;">
                       <option value=''>{gt text="Sense assignar"}</option>
                    {foreach from=$eixosDest item='eixDest'}
                        <optgroup label="{gt text='Eix:'}{$eixDest.ordre}. {$eixDest.nomCurt}">
                        {foreach from=$eixDest.prioritats item='epDest'}
                            <optgroup label="{$epDest.ordre}-{$epDest.nomCurt}">
                            {assign var='index' value=$epOri.priId|cat:"o"|cat:$epsOri.sprId}
                            {assign var='result' value=$epDest.priId|cat:"d"}
                            <option value="{$epDest.priId}d" {if $assigna[$index] == $result}selected{/if}>{$epDest.ordre}-{gt text="Sense subprioritat"}</option>
                            {foreach from=$epDest.subprioritats item='epsDest'}
                                {assign var='index' value=$epOri.priId|cat:"o"|cat:$epsOri.sprId}
                                {assign var='result' value=$epDest.priId|cat:"d"|cat:$epsDest.sprId}
                                <option value="{$epsDest.priId}d{$epsDest.sprId}" {if $assigna[$index] == $result}selected{/if}>{$epDest.ordre}{$epsDest.ordre}-{$epsDest.nomCurt}</option>
                            {/foreach}
                            </optgroup>
                        {/foreach}
                        </optgroup>
                    {/foreach}
               </select></span>
            </td></tr>
        {/foreach}
        </tbody>
    {/foreach}
{/foreach}
</table>
</form>
<div id="botons" class="z-buttonrow z-buttons z-center">
    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:desa();" title="{gt text="Desa"}">{gt text="Desa"}</button>
    <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
</div>
<script>
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=importgest";
}
function desa(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    document.editImportTaula.action="index.php?module=Cataleg&type=admin&func=importeditsaveTaula";
    document.editImportTaula.submit();
}
</script>