{ajaxheader modname=Cataleg filename=Cataleg.js}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Exportació del catàleg a PDF"}</h3>
</div>
<form id="pdfForm" name="pdfForm" class="z-form" method="post" action={modurl modname='Cataleg' type='admin' func='document' catId=$cataleg.catId do=true}>
    {if isset($cataleg)}
        <input type="hidden" id='catId' name='catId' value='{$cataleg.catId}' />
        <fieldset>
            <div>
                <legend>{gt text="Portada del document"}</legend>
                <textarea rows=15 name="portada" id="portada"><h1>{$cataleg.nom}</h1></textarea>
            </div>
            <div id="botons" class="z-buttonrow z-buttons z-center">
                <button id='pdfPreview' class="z-bt-preview" type="button" onclick="javascript:previewPortada();" title="{gt text="Vista prèvia de la portada"}">{gt text="Veure la portada"}</button>
            </div>
            <fieldset>
                <label>
                    {gt text='En mode HTML, es pot definir una capçalera i un peu de pàgina per a la portada amb "&lt;pageheader&gt;", "&lt;setpageheader&gt;", "&lt;pagefooter&gt;" i "&lt;setpagefooter&gt;".'}<br />
                    {gt text="Exemple:"}<br /><span style="font-family:Courier;font-weight:bold ">
                        &lt;pageheader name="portada" content-right="Catàleg unificat" header-style="color: #880000; font-style:italic;" line="1" /&gt;<br />
                        &lt;setpageheader name="portada" value="on" show-this-page="1"/&gt;<br />
                        &lt;pagefooter name="portada" content-left="Text esquerra" content-center="Text central" content-right="Text dreta" footer-style="font-weight:bold" line="1" /&gt;<br />
                        &lt;setpagefooter name="portada"/&gt;<br /></span>
                    {gt text="Més informació a: "}<br />
                    <a href="http://mpdf1.com/manual/index.php?tid=173" target="_blank">&lt;pageheader&gt;</a>,&nbsp;
                    <a href="http://mpdf1.com/manual/index.php?tid=175" target="_blank">&lt;setpageheader&gt;</a>,&nbsp;
                    <a href="http://mpdf1.com/manual/index.php?tid=174" target="_blank">&lt;pagefooter&gt;</a>&nbsp;
                    {gt text="i"}&nbsp;<a href="http://mpdf1.com/manual/index.php?tid=176" target="_blank">&lt;setpagefooter&gt;</a>

                </label>
            </fieldset>
        </fieldset>
    {/if}
    <fieldset>
        <legend>{gt text="Elements configurables del document"}</legend>
        <div class="z-informationmsg">
            {gt text ='Per excloure una determinada secció del document pdf, deixeu en blanc l\'encapçalament de la secció corresponent.'}
            </div>
        <div class="z-formrow">
            <label for="nomSeccioOrientacions">{gt text='Encapçalement de la secció "Orientacions"'}</label>
            <input type="text" id="nomSeccioOrientacions" name="nomSeccioOrientacions" value='{gt text="Orientacions de les línies prioritàries"}' /><br />
            {*<textarea rows=15 id="nomSeccioOrientacions" name="nomSeccioOrientacions"> <h2>{gt text="Orientacions de les línies prioritàries"}</h2> </textarea>*}
            <label for="nomSeccioActivitats">{gt text='Encapçalement de la secció "Activitats"'}</label>
            {*<textarea rows=15 id="nomSeccioActivitats" name="nomSeccioActivitats"> <h2>{gt text="Activitats del catàleg"}</h2> </textarea>*}
            <input type="text" id="nomSeccioActivitats" name="nomSeccioActivitats" value='{gt text="Activitats"}' /><br />
            <label for="nomSeccioUnitats">{gt text='Encapçalement de la secció "Unitats"'}</label>
            {*<textarea rows=15 id="nomSeccioUnitats" name="nomSeccioUnitats"> <h2>{gt text="Unitats"}</h2> </textarea>*}
            <input type="text" id="nomSeccioUnitats" name="nomSeccioUnitats" value='{gt text="Unitats"}' />
            <div style="text-align:left">
            <label style="text-align:left" for="pdfStyle">{gt text='Utilitzar full d\'estils'}</label> 
            <input type="checkbox" id="pdfStyle" name="pdfStyle" value=1 />
            <label style="text-align:left" for="exUnits">{gt text='Excloure les unitats que no ofereixen cap activitat.'}</label> 
            <input type="checkbox" id="iau" name="exUnits" value=1 />
        </div>
        </div>            
    </fieldset>
</form>
<div id="botons" class="z-buttonrow z-buttons z-center">
    <button id='btn_pdf' class="z-bt-pdf" type="button" onclick="javascript:doPdf();" title="{gt text="Genera el document PDF del catàleg"}">{gt text="Exporta a PDF"}</button>
    <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancelPdf();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
</div>
{notifydisplayhooks eventname='Cataleg.ui_hooks.Cataleg.form_edit' id=null}
<script type="text/javascript">
    function doPdf() {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_pdf"))
            document.getElementById("btn_pdf").disabled = true;
        if (document.getElementById("btn_cancel"))
            document.getElementById("btn_cancel").disabled = true;
        document.pdfForm.action = "index.php?module=Cataleg&type=admin&func=document&catId={{$cataleg.catId}}&do=true";
        document.pdfForm.setAttribute("target", "_blank");
        document.pdfForm.submit();
        if (document.getElementById("btn_pdf"))
            document.getElementById("btn_pdf").disabled = false;
        if (document.getElementById("btn_cancel"))
            document.getElementById("btn_cancel").disabled = false;
    }
    function cancelPdf() {
        // Evitar el submit múltiple. Desactivar botó
        if (document.getElementById("btn_pdf"))
            document.getElementById("btn_pdf").disabled = true;
        if (document.getElementById("btn_cancel"))
            document.getElementById("btn_cancel").disabled = true;
        window.location = "index.php?module=Cataleg&type=admin&func=catalegsgest";
    }
    function previewPortada() {
        if (document.getElementById("pdfPreview"))
            document.getElementById("pdfPreview").disabled = true;
        document.pdfForm.action = "index.php?module=Cataleg&type=admin&func=previewPortada";
        document.pdfForm.setAttribute("target", "_blank");
        document.pdfForm.submit();
        if (document.getElementById("pdfPreview"))
            document.getElementById("pdfPreview").disabled = false;
    }
</script>