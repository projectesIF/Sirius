{adminheader}
{pageaddvar name='javascript' value='jQuery'}
{pageaddvar name='javascript' value='vendor/bootstrap/js/bootstrap.js'}
{pageaddvar name='stylesheet' value='vendor/bootstrap/css/bootstrap.css'}
{pageaddvar name="stylesheet" value="vendor/datatables/media/css/jquery.dataTables.css"}
{pageaddvar name="javascript" value="vendor/datatables/media/js/jquery.dataTables.js"}
<div class="z-admin-content-pagetitle">
    {icon type="user" size="small"}
    <h3>{gt text="Gestió dels usuaris"}</h3>
</div>
<div>
    <a href="{modurl modname='Cataleg' type='admin' func='addUser'}">{gt text="Afegeix un usuari"}</a>
</div>
<form class="z-form" name='nouuser'>
    <div id="filter_info">
        <fieldset>
        <legend style="margin-bottom:0px"><a id="cataleg_users_filter" class="z-toggle-link" href="javascript:void(0)">{gt text="Filtre"}</a></legend>
            <div id="cataleg_users_filter_details" style="display:none">
                <b>{gt text="Rols especials"}:</b>
                <input type="checkbox" style="margin-left:15px;margin-top:0px;vertical-align:middle" id="Odissea" onchange="javascript:filtre();"> Odissea
                <input type="checkbox" style="margin-left:15px;margin-top:0px;vertical-align:middle" id="Cert" onchange="javascript:filtre();"> Certificació
                <input type="checkbox" style="margin-left:15px;margin-top:0px;vertical-align:middle" id="gA" onchange="javascript:filtre();"> gA
                <input type="checkbox" style="margin-left:15px;margin-top:0px;vertical-align:middle" id="gB" onchange="javascript:filtre();"> gB
                <br><br>
                <b>{gt text="Tipus d'entitat"}:</b>
                <input type="checkbox" style="margin-left:15px;margin-top:0px;vertical-align:middle" id="UNI" onchange="javascript:filtre();"> Unitats
                <input type="checkbox" style="margin-left:15px;margin-top:0px;vertical-align:middle" id="ST" onchange="javascript:filtre();"> Serveis Territorials
                <input type="checkbox" style="margin-left:15px;margin-top:0px;vertical-align:middle" id="SE" onchange="javascript:filtre();"> Serveis Educatius
                <br>
                <div class="z-formrow">
                    <label style="text-align:left;width:auto;" for="gtafGroup"><b>{gt text="Grup d'entitats GTAF"}</b></label>
                    <select id="gtafGroup" name="gtafGroup"onchange="javascript:dropdownlist(document.nouuser.gtafGroup.options[document.nouuser.gtafGroup.selectedIndex].value);"/>
                    <option value="">{gt text="Tria un grup"}</option>
                    {foreach from=$gtafInfo.groups item='group'}
                        <option value="{$group.gtafGroupId}">{$group.nom}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="z-formrow">   
                    <label style="text-align:left;width:auto;" for="gtafEntity"><b>{gt text="Entitat GTAF"}</b></label>
                        <select id="gtafEntity" name="gtafEntity" onchange="javascript:writecode()"/></select>
                </div>
                <input type='hidden' id='gtafg' onchange="javascript:filtre();">
                <input type='hidden' id='gtafe' onchange="javascript:filtre();">
            </div>
        </fieldset>
    </div>
</form>
{if $catusers}
    <table id="catusers_table" class="z-datatable">
        <thead>
            <tr>
                <th width="10%">{gt text='Nom d\'usuari'}</th>
                <th width="12%">{gt text='Nom'}</th>
                <th width="21%">{gt text='Cognoms'}</th>
                <th width="24%">{gt text='email'}</th>
                <th width="5%" style="text-align:center">{gt text='Tipus'}</th>
                <th width="10%" style="text-align:center">{gt text='Grups'}</th>
                <th width="9%" style="text-align:center">{gt text='GTAF'}</th>
                <th width="9%" style="text-align:center">{gt text='Accions'}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$catusers item='catuser' key='key'}
                {assign var='iwcode' value=$catuser.iw.code}
                <tr class="filter {cycle values='z-odd,z-even'}{if isset($catuser.gr.Odissea)} rol_Odissea{/if}{if isset($catuser.gr.Cert)} rol_Cert{/if}{if isset($catuser.gr.gA)} rol_gA{/if}{if isset($catuser.gr.gB)} rol_gB{/if}{if $entities.$iwcode.tipus == 'UNI'} gtaft_UNI{/if}{if $entities.$iwcode.tipus == 'ST'} gtaft_ST{/if}{if $entities.$iwcode.tipus == 'SE'} gtaft_SE{/if}{if isset($entities.$iwcode.gtafGroupId)} gtafg_{$entities.$iwcode.gtafGroupId}{/if}{if isset($entities.$iwcode.gtafEntityId)} gtafe_{$entities.$iwcode.gtafEntityId}{/if}">
                    <td>{$catuser.zk.uname}</td>
                    <td>{$catuser.iw.nom}</td>
                    <td>{$catuser.iw.cognom1}</td>
                    <td>{$catuser.zk.email}</td>
                    <td style="text-align:center">{if isset($catuser.gr.Generics)}{img modname='core' set='icons/extrasmall' src='kdmconfig.png' __title='Usuari genèric' __alt='Usuari genèric'}{/if}{if isset($catuser.gr.Personals)}{img modname='core' set='icons/extrasmall' src='group.png' __title='Usuari personal' __alt='Usuari personal'}{/if}</td>
                    {if isset($catuser.uni)}
                            {capture name='html3_unis'}data-content="<div style='color:blue'><h3>Edita les unitats del catàleg:</h3><ul>{foreach from=$catuser.uni item='catuni'}<li>{$catuni.name}</li>{/foreach}</ul></div>"{/capture}
                    {/if}
                    <td style="text-align:center"><ul style="margin:2px;list-style:none;">
                        {if isset($catuser.gr.LectorsCat)}<li style="color:red">{gt text="Només llegeix el Catàleg"}</li>{/if}
                        {if !isset($catuser.gr.LectorsCat) && !isset($catuser.gr.Gestform)}<li style="color:red">{gt text="Cap rol general de formació"}{/if}    
                        {if isset($catuser.gr.EditorsCat)}<li>{gt text="<span style='color:blue' data-toggle='popover' %s>Catàleg</span>" tag1=$smarty.capture.html3_unis}</li>{/if} 
                        {if isset($catuser.gr.Gestors)}<li style="color:green">{gt text="Gestor de Sirius"}</li>{/if}
                        {if isset($catuser.gr.Odissea)}<li>{gt text="Odissea"}</li>{/if}
                        {if isset($catuser.gr.Cert)}<li>{gt text="Certificació"}</li>{/if}
                        {if isset($catuser.gr.gA)}<li>{gt text="gA"}</li>{/if}
                        {if isset($catuser.gr.gB)}<li>{gt text="gB"}</li>{/if}
                        </ul>
                    </td>
                    <td style="text-align:center">
                        <span data-toggle='tooltip' title="{$entities.$iwcode.tipus} {$entities.$iwcode.nom}">{$iwcode}</span>
                    </td>
                    <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='editUser' uid=$catuser.zk.uid}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edita' __alt='Edita'}</a><span style="padding-left:3px"><button style="border:0px;background-color:transparent;" title='{gt text="Treu"}' onclick="javascript:esborra('{$catuser.zk.uname|@escape:quotes}',{$catuser.zk.uid});">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' __title='Esborra' __alt='Esborra'}</button></span></td>
                </tr> 

            {/foreach}
        </tbody>
    </table>
{else}
    <h1>{gt text="Encara no hi ha cap usuari del catàleg"}</h1>
{/if}
{if !empty($excatusers)}
    <br>
    <h3>{gt text="Històric d'exusuaris del catàleg"}</h3>
    <table class="z-datatable">
        <thead>
            <tr>
                <th width="10%" style="text-align:center">{gt text='Nom d\'usuari'}</th>
                <th width="10%" style="text-align:center">{gt text='Nom'}</th>
                <th width="15%" style="text-align:center">{gt text='Cognoms'}</th>
                <th width="10%" style="text-align:center">{gt text='email'}</th>
                <th width="9%" style="text-align:center">{gt text='Accions'}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$excatusers item='excatuser' key='key'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>{$excatuser.zk.uname}</td>
                    <td>{$excatuser.iw.nom}</td>
                    <td>{$excatuser.iw.cognom1}</td>
                    <td>{$excatuser.zk.email}</td>
                    <td style="text-align:center"><a href="{modurl modname='Cataleg' type='admin' func='incorporaUser' uid=$excatuser.zk.uid}">{img modname='core' set='icons/extrasmall' src='edit_add.png' title='Incorpora' alt='Incorpora'}</a></td>
                </tr> 

            {/foreach}
        </tbody>
    </table>
{/if}
<script>
    function esborra($uname,$uid) {
        var $mess = '{{gt text="Heu triat l\'usuari"}} \''+$uname+'\'\n\n{{gt text="Voleu esborrar-lo?"}}';
        if (confirm($mess)) {
            window.location = "index.php?module=Cataleg&type=admin&func=deleteUser&uid="+$uid;
        }
    }
    jQuery('[data-toggle="popover"]').popover({'trigger': 'hover','placement':'top','html':'true'});
    jQuery('[data-toggle="tooltip"]').tooltip({'trigger': 'hover','placement':'bottom'});
    function filtre() {
        if (!jQuery('#Odissea,#Cert,#gA,#gB,#UNI,#ST,#SE').is(':checked') && !jQuery('#gtafg').val() && !jQuery('#gtafe').val()) {
            jQuery("tr.filter").show();
            //jQuery('#filter_info').removeClass('z-warningmsg');
            //jQuery('#filter_info_text').html('');
            jQuery('#filter_info').css({'border':''});
            jQuery('#catusers_table').css({'border':''});
        } else {
            jQuery("tr.filter").show();
            if (jQuery('#Odissea,#Cert,#gA,#gB').is(':checked')) {
                cl1='';
                if (jQuery('#Odissea').is(':checked')) cl1=cl1+':not(.rol_Odissea)';
                if (jQuery('#Cert').is(':checked')) cl1=cl1+':not(.rol_Cert)';
                if (jQuery('#gA').is(':checked')) cl1=cl1+':not(.rol_gA)';
                if (jQuery('#gB').is(':checked')) cl1=cl1+':not(.rol_gB)';
                jQuery("tr.filter"+cl1).hide();
            }
            if (jQuery('#UNI,#ST,#SE').is(':checked')) {
                cl2='';
                if (jQuery('#UNI').is(':checked')) cl2=cl2+':not(.gtaft_UNI)';
                if (jQuery('#ST').is(':checked')) cl2=cl2+':not(.gtaft_ST)';
                if (jQuery('#SE').is(':checked')) cl2=cl2+':not(.gtaft_SE)';
                jQuery("tr.filter"+cl2).hide();
            }
            if (jQuery('#gtafg').val()) {
                jQuery("tr.filter:not(.gtafg_"+jQuery('#gtafg').val()+")").hide();
            }
            if (jQuery('#gtafe').val()) {
                jQuery("tr.filter:not(.gtafe_"+jQuery('#gtafe').val()+")").hide();
            }
            //jQuery('#filter_info').addClass('z-warningmsg');
            //jQuery('#filter_info_text').html('<span style="margin-left:200px;font-weight:bold !important">{{gt text="Filtre actiu!"}}</span>');
            jQuery('#filter_info').css({'border':'8px solid #FEEFB3'});
    jQuery('#catusers_table').css({'border':'8px solid #FEEFB3'});
        }
    }
    function dropdownlist(listindex){
    jQuery('#gtafg').val(jQuery('#gtafGroup').val());
    jQuery('#gtafe').val('');
    document.nouuser.gtafEntity.options.length = 0;
    switch (listindex) {
        {{foreach from=$gtafInfo.groups item='group'}}
            case "{{$group.gtafGroupId}}":
                document.nouuser.gtafEntity.options[document.nouuser.gtafEntity.options.length]=new Option("{{gt text="Tria l'entitat GTAF"}}","");
                {{foreach from=$group.entities item='entity'}}
                    document.nouuser.gtafEntity.options[document.nouuser.gtafEntity.options.length]=new Option("{{$entity.gtafEntityId}} - {{$entity.nom}} - [{{$entity.tipus}}]","{{$entity.gtafEntityId}}");
                {{/foreach}}
                break;
        {{/foreach}}
    }
    filtre();
}
function writecode(){
    {{assign var=codigtaf value=document.nouuser.gtafEntity.value}}
    document.nouuser.gtafe.value = {{$codigtaf}};
    filtre();
}
jQuery( ".z-toggle-link" ).click(function() {
    var tId = jQuery(this).attr('id');
    jQuery( "#"+tId+"_details" ).toggle( "slow", function() {
        jQuery( "#"+tId ).toggleClass( "z-toggle-link-open" );
    });
});
jQuery('#catusers_table').DataTable({'paging': false,'columnDefs': [{ 'orderable': false, 'targets': [4,5,7] }],'info': false,'language': {'search': 'Cerca:'} });
</script>