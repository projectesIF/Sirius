{*Confirmar eliminació del registre corresponent a un treball*}
{include file="Llicencies_msg.tpl"}
<div class="z-buttonrow z-buttons z-center">                
        <button id="llicencies_button_update" class="z-buttons" type="button" onclick="javascript:confirmar();" name="desa" value="1" title="{gt text='Desa els canvis'}">{img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Desa' __title='Desa' } {gt text='Desa'}</button>                
        <button id="llicencies_button_cancel" class="z-buttons" type="button" onclick="javascript:updateDisplay('list', 'detail');" name="cancel" title="{gt text='Cancel·la els canvis'}">{img src='button_cancel.png' modname='core' set='icons/extrasmall' __alt='Cancel·la' __title='Cancel·la' } {gt text='Cancel·la'}</button>                
    </div>