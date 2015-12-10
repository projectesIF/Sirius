{*
* Plantilla: Cataleg_user_import
* Selecció d'activitats per a importar 
*}
<script type="text/javascript"> var $$=jQuery.noConflict(true);</script>

{ajaxheader modname=Cataleg filename=Cataleg.js}
{pageaddvar name="title" __value="Sirius :: Catàleg Unificat de Formació :: Importació d'activitats"}
{pageaddvar name='javascript' value='jQuery'}
{insert name='getstatusmsg'}
<h2>{gt text="Importació d'activitats cap a: $catDestNom"}</h2>
<form id='importForm' name="importForm" class="z-form" method="post">
    <fieldset>
        <input id="catDest" name="catDest" value={$catDest} type="hidden" />
        <label>{gt text = 'Des del catàleg: '}</label>
        <select id="catalegs" name="catalegs" onchange ="catalegChange(this.value);">
            {foreach item="cat" from=$ccats}
                <option value="{$cat.catId}">{$cat.nom}</option>
            {/foreach}
        </select>    

        <div id="ulist">    
            {include file="user/Cataleg_user_importUnits.tpl"}
        </div>
    </fieldset>
</form>
<script type="text/javascript">
    window.onload = catalegChange(document.getElementById('catalegs').options[0].value);
    
    function actualitza(){                        
        catalegChange(document.getElementById('catalegs').options[0].value);        
    }
    
 </script>