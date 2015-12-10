{* Cataleg_user_selectSubPrioritat.tpl 
Plantilla per triar una Subprioritat després d'haver seleccionat una prioritat
*}

{if ($subpr|@count) > 0}
    {if ($subprinfo.sprId != null)}
        {assign var="spri" value=$subprinfo.sprId}   
    {else}
        {assign var="spri" value=0}
    {/if}

      {if isset($cerca) && $cerca}
  
        <span id="spriTitol" style="width:120px; font-weight:bold; cursor:pointer" onclick="javascript:mostra('spri', 'pri')">{img modname='core' title='Mostrar la llista de subprioritats' src='search.png' set='icons/extrasmall'}&nbsp;{gt text="Subprioritat"} </span>&nbsp;&nbsp;
            <label id='lbl_spri' onclick="javascript:mostra('spri', 'pri')" style="cursor:pointer">
            {if ($info.sprId>0)}{$subprinfo.ordre} - {$subprinfo.nomCurt}{/if}
            </label>
    
    <div class='z-block flotant popupwin' id='spri'>
        <h4 class="z-block-title " >{gt text="Subprioritats"}</h4>
        <div class='z-block-content'  >        
            <input type ='radio' id="subprioritat" name="subprioritat"  value=0 onclick ='javascript:setSubPrioritat("lbl_spri" ,"spri", "");'>&nbsp;&nbsp;{gt text="Per determinar"}</input><br><hr>
            {foreach item="sp" from=$subpr}           
                <input type ='radio' id='subprioritat' name='subprioritat' value='{$sp.sprId}' 
                       onclick ='javascript:setSubPrioritat("lbl_spri" ,"spri", "{$sp.ordre} - {$sp.nomCurt|escape:html}");' 
                      {if ($subprinfo.sprId == $sp.sprId)} checked='checked'{/if} > 
                &nbsp;{$sp.ordre} - {$sp.nomCurt|escape:html}
                </input><br><hr>
            {/foreach}
        </div>
    </div>
        
        
    {else}
        
        
    <legend><span id="spriTitol" style="width:120px; font-weight:bold; cursor:pointer" onclick="javascript:mostra('spri', 'pri')">{img modname='core' title='Mostrar la llista de subprioritats' src='search.png' set='icons/extrasmall'}&nbsp;{gt text="Subprioritat"} </span>&nbsp;&nbsp;
            <label id='lbl_spri' onclick="javascript:mostra('spri', 'pri')" style="cursor:pointer">
            {if ($info.sprId>0)}{$subprinfo.ordre} - {$subprinfo.nomCurt}{/if}
            </label>
    </legend>
    <div class='z-block flotant popupwin' id='spri'>
        <h4 class="z-block-title " >{gt text="Subprioritats"}</h4>
        <div class='z-block-content'  >        
            <input type ='radio' id="subprioritat" name="subprioritat"  value=0 onclick ='javascript:setSubPrioritat("lbl_spri" ,"spri", "");'>&nbsp;&nbsp;{gt text="Per determinar"}</input><br><hr>
            {foreach item="sp" from=$subpr}           
                <input type ='radio' id='subprioritat' name='subprioritat' value='{$sp.sprId}' 
                       onclick ='javascript:setSubPrioritat("lbl_spri" ,"spri", "{$sp.ordre} - {$sp.nomCurt|escape:html}");' 
                      {if ($subprinfo.sprId == $sp.sprId)} checked='checked'{/if} > 
                &nbsp;{$sp.ordre} - {$sp.nomCurt|escape:html}
                </input><br><hr>
            {/foreach}
        </div>
    </div>
    <br />
    
    {/if}
    
{/if}

<script type="text/javascript"  language="javascript">
function mostra(id, amaga){
    jQuery('#'+amaga).hide();
    if (jQuery('#'+id).is(':hidden')){
        jQuery('#'+id).css(
            {
            left:jQuery('#theme_content_center').offset().left+ 20 +"px",
            top: jQuery('#'+id+'Titol').position().top + 25 + "px"
            }).show('slow');
    }else{
        jQuery('#'+id).hide('slow');
    }
}
function amaga(id){
jQuery('#'+id).hide('slow');
}

function setSubPrioritat(update, id, txt){
// Canviem les subprioritats en funció de la prioritat triada
jQuery('#'+update).text(txt);
jQuery('#'+id).hide('slow');
}


 //-(width/2)+ z-block flotant popupwin
</script>

