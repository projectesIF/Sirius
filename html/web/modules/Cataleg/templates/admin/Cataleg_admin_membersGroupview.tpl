{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="user" size="small"}
    <h3>{gt text="Informació dels membres del grup "}{$grup.name}</h3>
</div>
<form class='z-form' id="assignform" name="assignform" action="" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset><legend>{gt text="Usuaris del catàleg"}</legend>
     <table  style="margin-left:auto;margin-right:auto" border="0" cellpadding="5" cellspacing="0"><tr>
      <td valign="top">
          <h4>{$usuaris.1|@count} {gt text="Usuaris existents (membres del grup)"}</h4>
          <br />
          
            {foreach from=$usuaris.1 item='user1'}
          {$user1.uname} - {$user1.iw.nom} {$user1.iw.cognom1} {$user1.iw.cognom2}<br>
          {/foreach}
              </td>
              <td valign="middle" align="center" style="padding:20px;">
        
           
        
      </td>
      <td valign="top">
          <h4>{$usuaris.0|@count} {gt text="Usuaris potencials (no són membres del grup)"}</h4>
          <br />
          
              {foreach from=$usuaris.0 item='user0'}
          {$user0.uname} - {$user0.iw.nom} {$user0.iw.cognom1} {$user0.iw.cognom2}<br>
          {/foreach}
            
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

            