<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

{* $noblock ha de ser true quan no es visualitza des de l'administració del bloc *}  
{if isset($noblock) && $noblock}    
    <div id="frmConf">
        <form class="z-form" enctype="application/x-www-form-urlencoded" id="blck_conf" name="blck_conf" action="">
            <fieldset>
                <legend>{gt text="Paràmetres de configuració generals"}</legend>
                <input type="hidden" name="noblock" value={$noblock} />    
                <table> 
{/if} 
                <tr><td>{gt text = "Mostar al bloc la llista de noves activitats"}</td><td><input type="checkbox" id="showNew" name="showNew" {if $showNew} checked = "checked" {/if} value=true></td></tr>
                <tr><td>{gt text = "Mostar al bloc la llista d'activitats modificades"}</td><td><input type="checkbox" id="showMod" name="showMod" {if $showMod} checked = "checked" {/if} value=true></td></tr>
                <tr><td>{gt text = 'Mostar novetats i modificacions'}</td><td><input type="text" id="dies" name="dies" size="3" maxlength="3" value={$dies}> &nbsp;{gt text ="dies"}</td></tr>
                <tr><td>{gt text = 'Data de publicació del catàleg'}</td><td><input type="text" id="datepicker" name="dp" size="10" maxlength="10"  value={$dp}> &nbsp;</td></tr>
                <input type="hidden" id= "dataOk" name="dataOk" value="1" />                    
{if isset($noblock) && $noblock}
                </table> 
                <div class="z-buttonrow z-buttons z-center">
                    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:updateConf();" title="{gt text="Desa els paràmetres de publicació del catàleg i de configuració del bloc."}">{gt text="Desa"}</button>
                </div>    
                <div id="msg" class="z-informationmsg" style="display:none">
                {gt text='S\'han actualitzat els paràmetres de configuració general.'}
            </div>
            </fieldset>
        </form>    
     </div>
{/if}

    
<script type="text/javascript"  language="javascript"> 
    
// Verificació de la correcció de la data
function isDate(txtDate){
    var currVal = txtDate;
    	  if(currVal == '')
	    return true;
    //Declare Regex 
    var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
    var dtArray = currVal.match(rxDatePattern); // is format OK?
    
    if (dtArray == null)
        return false;

    dtYear = dtArray[1];
    dtMonth = dtArray[3];
    dtDay= dtArray[5];
    
    if (dtMonth < 1 || dtMonth > 12)
        return false;
    else if (dtDay < 1 || dtDay> 31)
        return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
        return false;
    else if (dtMonth == 2)
	  {
                  var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
              if (dtDay> 29 || (dtDay ==29 && !isleap))
	          return false;
	  }
    return true;
}

// Verificació de data i selector de data
$(function() {    
    $('#datepicker').bind('change', function(){
	        var txtVal =  $('#datepicker').val();
	        if(!isDate(txtVal)) {
                    $('#dataOk').val('0');
                    alert('{{gt text= 'La data introduïda no és vàlida'}}');
                } else  $('#dataOk').val('1');
	    });
            
    $( "#datepicker" ).datepicker({ 
        appendText: "(aaaa-mm-dd)" ,
        dateFormat: "yy-mm-dd", 
        firstDay: 1, // dilluns
        dayNamesMin: [ "dg", "dl", "dt", "dc", "dj", "dv", "ds"],
        monthNames: [ "gener", "febrer", "març", "abril", "maig", "juny", "juliol", "agost", "setembre", "octubre", "novembre", "desembre" ],
        monthNamesShort: [ "Gen", "Feb", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Des" ]
    });
});
</script>