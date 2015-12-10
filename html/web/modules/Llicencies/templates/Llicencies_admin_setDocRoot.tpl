{insert name='getstatusmsg'}
<form name="docForm" class="z-form"> 
<div class="z-left z-bold">
    <span class="z-formrow" style="font-size: 1.2em;">
        <label for="docRoot">{gt text="URL base de les memòries i els resums dels treballs: "}</label>
<input onkeypress="javascript:getElementById('btOk').style.display='inline'" name="docRoot" size="50" id="docRoot" value={$root}>&nbsp;
<a id='btOk' onclick="javascript:setDocRoot();" style="display:none" href="#" class="z-icon-es-ok"></a></span>
</div>
</form>