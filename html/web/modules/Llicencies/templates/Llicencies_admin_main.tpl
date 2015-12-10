{ajaxheader modname=llicencies filename=Llicencies.js}
{pageaddvar name='javascript' value='jQuery'}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Llicències d'estudi retribuïdes"}</h3>
</div>
<div id="search">
   {include file="Llicencies_admin_search.tpl"}
</div>
<div id="list" name="list"></div>
<div id="detail" name="detail"></div>

