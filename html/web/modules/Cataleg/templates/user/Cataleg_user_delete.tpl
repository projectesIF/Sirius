{* user/Cataleg_user_delete.tpl *}
    <div class="usercontainer">
        <div class="userpageicon">{img modname='core' src='14_layer_deletelayer.png' set='icons/large'}</div>
        <h2 style="text-align:left">{gt text="Esborrar fitxa d'activitat "}-&nbsp;{$info.cataleg.nom}</h2>
        <br>
        <form id="frmDelete" class="z-form" name="frmDelete" action="" method="post" enctype="application/x-www-form-urlencoded">
        <fieldset class="z-block-content" style="width:500px; background:#FF9900">
            <span style="font-size:1.2em;  font-weight:bold">{gt text='Títol: '}</span>{$info.titol}<br>
            <span style="font-weight:bold">{gt text='Eix: '}</span>{$info.eix.nom}<br>
            <span style="font-weight:bold">{gt text='Prioritat: '}</span>{$info.pri.nom}<br>
            <span style="font-weight:bold">{gt text='Data de creació: '}</span>{$info.cr_date}<br>
        </fieldset>            
         <div id="botons" class="z-buttonrow z-buttons z-center">   
            <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·lar"}">{gt text="Cancel·lar"}</button> 
            <button id="btn_delete" class="z-bt-delete"  type="button"  onclick="javascript:esborra({$info.actId});" title="{gt text="Confirmar l'eliminació de la fitxa"}">{gt text="Confirmar"}</button> 
         </div>
    </form>
</div> 

{* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ *}
<script type="text/javascript"  language="javascript">
function cancel(){
    window.location = "index.php?module=Cataleg&type=user&func=view";
}

function esborra(id){
    document.getElementById("btn_delete").disabled = true;
    document.frmDelete.action="index.php?module=Cataleg&type=user&func=delete&c=1&actId="+id;
    document.frmDelete.submit();
} 
</script>