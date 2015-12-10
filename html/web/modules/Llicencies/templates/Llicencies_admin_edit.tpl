{*Plantilla per a l'edició de les dades d'una memòria*}

<form class="z-form" id="frmEdit" name="frmEdit" action="javascript:void(0)" method="post" >
    <input type='hidden' name='codi_treball' id="ecodi_treball" value={$detail.codi_treball}>
    <div style="text-align:right">
        <a href='#' onclick="javascript:updateDisplay('list', 'detail');">{img modname='llicencies'  style="padding-right: 5px;" src='back.png' __title="Torna enrera" __alt='Torna enrera'}{gt text="Torna enrera"}</a>

    </div>
    <fieldset>
        <legend style="font-weight: bold">{gt text = "Autoria"}</legend>
        <strong>{gt text="Nom"}&nbsp;</strong><input type="text" name="nom" id="enom" value="{$detail.nom}">
        <strong>{gt text="Cognoms"}&nbsp;</strong><input type="text" name="cognoms" id="ecognoms" value="{$detail.cognoms}">
        <strong>{gt text="Correu electrònic"}&nbsp;</strong><input type="text" id="ecorreuel" name="correuel" value="{$detail.correuel}">&nbsp;
    </fieldset>
    <fieldset  class="z-formrow">
        <legend style="font-weight: bold">{gt text = "Treball"}</legend>
        <p>
            <strong>{gt text="Títol"}&nbsp;</strong><input type="text" id="etitol" name="titol" value="{$detail.titol}" size="100%">
            <strong>{gt text="Modalitat"}</strong>&nbsp;
            <select id="emodalitat"  name="modalitat">
                <option label=""></option>
                {html_options options=$modalitats selected = $detail.modalitat}
            </select>     
        </p>
        <p><strong>{gt text="Nivell"}&nbsp;</strong><input type="text" id="enivell" name="nivell" value="{$detail.nivell}" style="width:450px">&nbsp;
            <strong>{gt text="Curs"}&nbsp;</strong>
            <select name="curs" id="ecurs">
                <option label="" value="{$detail.curs}"></option>
                {html_options options=$cursos selected = $detail.curs}
            </select>                
            &nbsp;
            <strong>{gt text="Estat"}&nbsp;</strong>
            <select name="estat" id="eestat">
                <option label="" value="{$detail.estat}"></option>
                {html_options options=$estats selected = $detail.estat}
            </select>                
            <strong>{gt text="Capsa"}&nbsp;</strong><input type="text" id="ecapsa" name="capsa" value="{$detail.capsa}" size="2">
        </p>
        <p><strong>{gt text="Tema"}&nbsp;</strong>
            <select name="tema" id="etema">
                {html_options options=$temes selected=$detail.tema}
            </select>
            <label for="subtema"><strong>{gt text="Subtema"}</strong></label>
            <select name="subtema" id="esubtema">
                {html_options options=$subtemes selected = $detail.subtema}
            </select>
            <label for="tipus" style="font-weight: bold">{gt text="Tipus"}</label>
            <select name="tipus" id="etipus">
                {html_options options=$tipus selected=$detail.tipus}
            </select>
        </p>
        <fieldset>
            <legend>{gt text="Característiques"}</legend>
            <textarea id="ecaracteristiques" name="caracteristiques">{$detail.caracteristiques|safehtml}</textarea>
        </fieldset>
        <fieldset>
            <legend>{gt text="Orientació"}</legend>
            <textarea name="orientacio" id="eorientacio">{$detail.orientacio|safehtml}</textarea>
        </fieldset>
        <fieldset>
            <legend>{gt text="Resum"}</legend>
            <textarea name="resum" id="eresum">{$detail.resum|safehtml}</textarea>
        </fieldset>
    </fieldset>
    <fieldset>
        <legend style="font-weight: bold">{gt text="Documentació"}</legend>
        <strong>{gt text="URL"}&nbsp;</strong><input type="text" name="url" id="eurl" value="{$detail.url|replace:'#':''}" size="50%">
        <strong>{gt text="WEB"}&nbsp;</strong><input type="text" name="web" id="eweb" value="{$detail.web}" size="50%">
        <strong>{gt text="Annexos"}&nbsp;</strong><input type="text" name="annexos" id="eannexos" value="{$detail.annexos}" size="10">
        <strong>{gt text="Altres"}&nbsp;</strong><input type="text" name="altres" id="ealtres" value="{$detail.altres}" size="10">
    </fieldset>
    <div class="z-buttonrow z-buttons z-center">                
        <button id="llicencies_button_update" class="z-buttons" type="button" onclick="javascript:lupdate();" name="desa" value="1" title="{gt text='Desa els canvis'}">{img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Desa' __title='Desa' } {gt text='Desa'}</button>                
        <button id="llicencies_button_cancel" class="z-buttons" type="button" onclick="javascript:updateDisplay('list', 'detail');" name="cancel" title="{gt text='Cancel·la els canvis'}">{img src='button_cancel.png' modname='core' set='icons/extrasmall' __alt='Cancel·la' __title='Cancel·la' } {gt text='Cancel·la'}</button>                
    </div>
</form>   
