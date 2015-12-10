{* Plantilla amb el formulari de cerca de llicències adaptat a la vista de l'admini*}
<div id="wait" class="z-center"></div>
<form id="frmAdminSearch" class="z-form" action="javascript:void(0)" method="post" >
    <input type="hidden" id="admin" name="admin" value=true>
    <div class="z-formrow" style="padding: 0em 0px !important;">
        <span><label for="autor">{gt text="Autor/a "}</label>&nbsp;<input type="text" id="autor" name="autor" size="30">
            &nbsp;<label for="titol">{gt text="Títol "}</label><input type="text" id="titol" name="titol" size="50">
        </span>        
        <span>
            <label for="temes">{gt text="Tema"}</label>&nbsp;            
            <select id="tema" name="tema" style="width:270px;" >
                {html_options options=$temes}
            </select>
            <label for="subtemes">{gt text="Subtema"}</label>

            <select id="subtema" name="subtema" style="width:220px;">
                {html_options options=$subtemes}
            </select>

            <label for="tipus">{gt text="Tipus"}</label>

            <select id="tipus" name="tipus">
                {html_options options=$tipus}
            </select>            
        </span>
        <span>
            &nbsp;<label for="estat">{gt text="Estat"}</label>
            <select id="estat"  name="estat">
                <option label="" value=""></option>
                {html_options options=$estats}
            </select>
            <label for="curs">{gt text="Curs"}</label>
            <select id="curs"  name="curs" style="width:65px;">
                <option label="" value=""></option>
                {html_options options=$cursos}
            </select>
        </span>
        <div class="z-formbuttons z-buttons">                
            <button id="llicencies_button_submit" type="button" onclick="javascript:search()" name="Envia" value="1" title="{gt text='Envia consulta'}">{img src='filter.png' modname='core' set='icons/extrasmall' __alt='Cerca' __title='Cerca' } {gt text='Cerca'}</button>                
            <button id="llicencies_button_neteja" type="reset"  name="reset" title="{gt text='Neteja el formulari'}">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Neteja el formulari' __title='Neteja el formulari' } {gt text='Neteja'}</button>                            
        </div>
    </div>
</form>