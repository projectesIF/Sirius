   {* <div id="elemGestio"> *}
        <input type="hidden" id="ndef" name="ndef" value={$def}></input>
        {* Comprovem el valor de la variable opcDef - opció per defecte seleccionada (1,2 o 3) *}        
        <table id="tGestio" style="width:50%; margin-left: auto; margin-right: auto"> {* style="text-align:center" *}
            {foreach item="op" from=$opsGest name="i"}
            {assign var="opcions" value=$op.opcions}
            {if ($def)}
                {assign var="default" value=$op.$def.id} 
            {else}
                {assign var="default" value=0}
            {/if}
            <tr>
                <td>
                    {$op.text}
                    <input type=hidden name=gestio[{$smarty.foreach.i.iteration}][txt] value="{$op.gesId}"></input>
                    {*<input type=hidden name=gestio[{$smarty.foreach.i.iteration}][mtext] value="{$op.text}"></input>*}
                </td>
                <td><select id=gestio[{$smarty.foreach.i.iteration}][srv] name=gestio[{$smarty.foreach.i.iteration}][srv]>
                    <option style="width:150px"></option>
                    {assign var='idx' value=$smarty.foreach.i.iteration}
                    {foreach item="element" from=$opcions}
                        {if ($element.id == $default)||($element.id == $info.gestio.$idx.srv)} 
                            <option style="width:150px" value="{$element.id}" selected="selected">
                        {else}
                            <option style="width:150px" value="{$element.id}">
                        {/if}
                        {$element.nom}</option>
                    {/foreach}
                    </select>
                </td>
            </tr>
            {/foreach}
        </table>
       {* <pre>{$opsGest|@print_r} </pre> *}
    {* </div>  *} 
