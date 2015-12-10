{if isset($acts) && $acts|@count > 0}
    <fieldset>
        <legend>{gt text="Activitats disponibles"}</legend>
            <div id=llista>
                <table class="z-datatable">
                    <thead>
                    <th><input type='checkbox' id='selectall' value="Select All" onclick="selectAll(this)" title={gt text="Seleccionar-les totes/cap"}></th>
                    <th style="width:40%">{gt text="Activitat"}</th>
                    <th></th>
                    <th>{gt text="Prioritat/Subprioritat"}</th>
                    </thead>
                    {foreach item='act' from=$acts}
                        <tr class="{cycle values='z-odd,z-even'}">
                            <td><input type="checkbox" class="cbAct" name="acts[{$act.actId}]" onchange="javascript:showHideImportButton(); " id="acts[{$act.actId}]" value={$act.equiv} /></td> 
                            <td>
                                <a href="{modurl modname="Cataleg" type="user" func="show" pdf=false actId=$act.actId}" title="{gt text='Detall de l\'activitat'}" alt="{gt text='Detall de l\'activitat'}" target="_blank">{$act.titol}</a>
                            </td>
                            <td>{$act.priOrdre}{$act.sprOrdre}</td>
                            <td>
                                {*<select id="item{$epOri.priId}o" name="item[{$epOri.priId}o]" style="width:100%; font-size:0.9em;background-color:#FAFAFA;" onchange="updateSelection(this.value, 'acts[{$act.actId}]');">*}
                                <select style="width:100%; font-size:0.9em;background-color:#FAFAFA;" onchange="updateSelection(this.value, 'acts[{$act.actId}]');">
                                    <option value=''>{gt text="Sense assignar"}</option>
                                    {foreach from=$eixosDest item='eixDest'}
                                        <optgroup label="{gt text='Eix:'}{$eixDest.ordre}. {$eixDest.nomCurt}">
                                            {foreach from=$eixDest.prioritats item='epDest'}
                                            <optgroup label="{$epDest.ordre}-{$epDest.nomCurt}">
                                                {assign var='index' value=$epOri.priId|cat:"o"}
                                                {assign var='result' value=$epDest.priId|cat:"d"}
                                                <option value="{$epDest.priId}d" {if $act.equiv == $result}selected{/if}>{$epDest.ordre}-{$epDest.nomCurt} ({gt text="Sense subprioritat"})</option>
                                                {foreach from=$epDest.subprioritats item='epsDest'}
                                                    {assign var='index' value=$epOri.priId|cat:"o"}
                                                    {assign var='result' value=$epsDest.priId|cat:"d"|cat:$epsDest.sprId}
                                                    <option value="{$epsDest.priId}d{$epsDest.sprId}" {if $act.equiv == $result}selected{/if}>{$epDest.ordre}{$epsDest.ordre}-{$epsDest.nomCurt}</option>
                                                {/foreach}
                                            </optgroup>
                                        {/foreach}
                                        </optgroup>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                    {/foreach}    
                </table>
            </div>
    </fieldset>             
{else}
    <div class="z-errormsg">{gt text="No hi ha activitats d'aquesta unitat en el catàleg origen."}</div>
{/if}


