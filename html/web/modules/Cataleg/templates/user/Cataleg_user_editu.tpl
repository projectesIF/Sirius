<fieldset>
    <legend>{gt text="Unitat responsable"}</legend>
    <select id="uniResp" name="uniResp">
        {foreach item="unitat" from=$unitats}
            <option value="{$unitat.uniId}" {if $unitat.uniId == $info.uniId}selected="selected"{/if}>{$unitat.nom}</option>
        {/foreach}
    </select>    
    <input type="hidden" name="nPersContacte" id="nPersContacte" value=1> </input>
    <table width='90%'>
        <tr><td width=95%>                    
                <TABLE class="z-datatable" ID="contactes" CELLPADDING=0 BORDER=0>
                    <thead>
                        <tr>
                            <th width=65%>{gt text="Persona de contacte"}</th>
                            <th width=25%>{gt text="Correu"}</th>
                            <th width=10%>{gt text="Telèfon/ext."}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {if ($info.contactes|@count)}
                            {foreach item='c' from=$info.contactes name="i"}
                                <tr>
                                    <td width=62%><input style="width:98%" maxlenght = "150" type='text' name="contacte[{$smarty.foreach.i.iteration}][pContacte]" id='pcontatcte1' value="{$c.pContacte}"></input></td>
                                    <td width=25%><input size="35" maxlenght = "50" type='text' name="contacte[{$smarty.foreach.i.iteration}][email]" id='email1' value="{$c.email}"></input></td>
                                    <td width=13%><input size="15" maxlenght = "20" type='text' name="contacte[{$smarty.foreach.i.iteration}][telefon]" id='telf1' value="{$c.telefon}"></input></td>
                                </tr>
                            {/foreach}
                        {else}
                            <tr>
                                <td width=62%><input style="width:98%" maxlenght = "150" type='text' name="contacte[1][pContacte]" id='pcontatcte1'></input></td>
                                <td width=25%><input size="35" maxlenght = "50" type='text' name="contacte[1][email]" id='email1'></input></td>
                                <td width=13%><input size="15" maxlenght = "20" type='text' name="contacte[1][telefon]" id='telf1'></input></td>
                            </tr>
                        {/if}
                    </tbody>
                </TABLE>
            </td>
            <td width=95% class="z-bottom z-left" >
                <a class="z-icon-es-add" id="mesp" onclick="javascript:appendRow(this.form);" title="{gt text="Afegir una fila"}" href="javascript:void(0);"></a>
                <a class="z-icon-es-remove" id="menysp" style="display:none;" onclick="javascript:removeRow(this.form);" title="{gt text="Eliminar la darrera fila"}" href="javascript:void(0);"></a>                    
            </td>
        </tr>
    </table>
</fieldset>