{* Mostra el formulari per especificar els criteris de cerca de llicències *}

<form id="Llicencies_user_cerca" class="z-form" action="javascript:void(0)" method="post" >
    <div>
        <input type="hidden" id="admin" name="admin" value='false'>
        <div class ="z-left">
            <p>{gt text="Escriviu en els diferents camps la informació que voleu buscar. Podeu escriure el terme sencer o només una part. <b>No</b> cal omplir tots els camps. 
                Si feu clic sobre les llistes desplegables podreu seleccionar <b>temes</b>, <b>subtemes</b> i/o <b>tipus</b> emprats per a classificar les llicències."}
            </p>
        </div>
        <div style="font-size:1.1em">
            <div class="z-left"><label for="autor">{gt text="Autor/a"}</label></div>
            <div class="z-formrow "><input type="text" id="autor" name="autor" style="width:280px;" size="40" ></div>   

            <div class="z-left"> <label for="titol">{gt text="Títol"}</label></div>
            <div class="z-formrow"><input type="text" id="titol" name="titol" style="width:350px;"size="40"></div>

            <div class="z-left"><label for="temes">{gt text="Tema"}</label></div>
            <div class="z-formrow z-left">
                <select id="tema" name="tema" style="width:280px;">
                    {html_options options=$temes}
                </select>
            </div>

            <div class="z-left"><label for="subtemes">{gt text="Subtema"}</label></div>
            <div class="z-formrow z-left">
                <select id="subtema" name="subtema" style="width:280px;">
                    {html_options options=$subtemes}
                </select>
            </div>    

            <div class="z-left"><label for="tipus">{gt text="Tipus"}</label></div>
            <div class="z-formrow z-left">
                <select id="tipus" name="tipus" style="width:280px;">
                    {html_options options=$tipus}
                </select>
            </div>

            <div class="z-left"><label for="curs">{gt text="Curs"}</label></div>
            <div class="z-formrow z-left">
                <select id="curs"  name="curs" style="width:70px;">
                    <option label="" value=""></option>
                    {html_options options=$cursos}
                </select>
            </div>
            <div class="z-buttons z-left" style="padding-left: 50px; padding-top: 10px;">                
                <button id="llicencies_button_submit" class="z-buttons" type="button" onclick="javascript:search()" name="Envia" value="1" title="{gt text='Fes la cerca'}">{img src='filter.png' modname='core' set='icons/extrasmall' __alt='Cerca' __title='Cerca' } {gt text='Cerca'}</button>                
                <button id="llicencies_button_neteja" class="z-buttons" type="reset"  name="reset" title="{gt text='Neteja el formulari'}">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Neteja el formulari' __title='Neteja el formulari' } {gt text='Neteja'}</button>                
                {*<a id="llicencies_button_neteja" href="{modurl modname='Llicencies' type='user' func='search'}" class="z-buttons">{img modname='core' src='14_layer_deletelayer.png' set='icons/extrasmall' __alt='Neteja' __title='Neteja'} {gt text='Neteja'}</a>*}
            </div>
        </div>
    </div>
</form>

