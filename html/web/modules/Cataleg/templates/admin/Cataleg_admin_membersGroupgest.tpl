{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="user" size="small"}
    <h3>{gt text="Gestió dels membres del grup "}{$grup.name}</h3>
</div>
<form class='z-form' id="assignform" name="assignform" action="" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="gid" value="{$grup.gid}">
    <fieldset><legend>{gt text="Usuaris del catàleg"}</legend>
     <table  style="margin-left:auto;margin-right:auto" border="0" cellpadding="5" cellspacing="0"><tr>
      <td valign="top">
          <label for="removeselect">{$usuaris.1|@count} {gt text="Usuaris existents"}</label>
          <br />
          <select name="removeselect[]" size="20" id="removeselect" multiple="multiple"
                  onfocus="getElementById('dadd').style.display = 'none';
                      getElementById('ddadd').style.display = 'none';
                            getElementById('assignform').add.disabled=true;
                            getElementById('dremove').style.display = 'block';
                            getElementById('ddremove').style.display = 'block';
                           getElementById('assignform').remove.disabled=false;
                           getElementById('assignform').addselect.selectedIndex=-1;">
            {foreach from=$usuaris.1 item='user1'}
          <option value="{$user1.uid}">{$user1.uname} - {$user1.iw.nom} {$user1.iw.cognom1} {$user1.iw.cognom2}</option>
          {/foreach}
              </select></td>
              <td valign="middle" align="center" style="width:150px;">
        
           
            <div id="dadd" style="display:none;margin-top:5px;" class="z-buttons"><button id="add" type="button" class="z-bt-ok" title='{gt text="Assigna els usuaris seleccionats"}' onclick="javascript:afegeix();">{gt text="Assigna"}</button></div>
            <div id="dremove" style="display:none;margin-top:5px;" class="z-buttons"><button id="remove" type="button" class="z-bt-cancel" title='{gt text="Desassigna els usuaris seleccionats"}' onclick="javascript:treu();" >{gt text="Desassigna"}</button></div>
            <div id="ddadd">{img modname='cataleg' src='misc_21.png' __title= 'Assigna els usuaris seleccionats' __alt='Assigna els usuaris seleccionats'}</div>
            <div id="ddremove">{img modname='cataleg' src='misc_26.png' __title= 'Desassigna els usuaris seleccionats' __alt='Desassigna els usuaris seleccionats'}</div>
            
        
      </td>
      <td valign="top">
          <label for="addselect">{$usuaris.0|@count} {gt text="Usuaris potencials"}</label>
          <br />
          <select name="addselect[]" size="20" id="addselect" multiple="multiple"
                  onfocus="getElementById('dadd').style.display = 'block';
                      getElementById('ddadd').style.display = 'block';
                      getElementById('assignform').add.disabled=false;
                      getElementById('dremove').style.display = 'none';
                      getElementById('ddremove').style.display = 'none';
                      getElementById('assignform').remove.disabled=true;
                      getElementById('assignform').removeselect.selectedIndex=-1;">
              {foreach from=$usuaris.0 item='user0'}
          <option value="{$user0.uid}">{$user0.uname} - {$user0.iw.nom} {$user0.iw.cognom1} {$user0.iw.cognom2}</option>
          {/foreach}
            </select>
          </td>
    </tr>
  </table>
            </fieldset>
</form>
        {if isset($usuaris.2)}
            <form class='z-form'>
                <fieldset><legend>{gt text="Altres usuaris d'aquest grup"}</legend>
                 {foreach from=$usuaris.2 item='user2'}
          {$user2.uname} - {$user2.iw.nom} {$user2.iw.cognom1} {$user2.iw.cognom2}<br>
          {/foreach}    
                </fieldset>
            </form>
        {/if}
<script type="text/javascript"  language="javascript">
function afegeix(){
    document.assignform.action="index.php?module=Cataleg&type=admin&func=addMembers";
    document.assignform.submit();
}
function treu(){
    document.assignform.action="index.php?module=Cataleg&type=admin&func=removeMembers";
    document.assignform.submit();
    
}
</script>
            