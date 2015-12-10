{*Llicencies_admin_eiTables.tpl*}
{pageaddvar name='javascript' value='jquery-ui'}
{pageaddvar name='stylesheet' value='vendor/jquery-ui/themes/overcast/jquery-ui.css'}

{ajaxheader modname=llicencies filename=Llicencies.js}
<div id="msg">
    {insert name='getstatusmsg'}
</div>
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="import" size="small"}
    <h3>{gt text="Importació/exportació CSV"}</h3>
</div>
<div id="tabs" style="width:75%">
    <ul style="margin:0;">
        <li><a href="#tabs-1">{gt text="Importa"}</a></li>
        <li><a href="#tabs-2">{gt text="Exporta"}</a></li>
    </ul>

    <div id="tabs-1">
        <form class="z-form" id="frmImport" name="frmImport" action="{modurl modname='Llicencies' type='admin' func='importaTaula'}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <div class="z-formrow">
                <span><label for="users_import">{gt text="Selecciona l'arxiu CSV a importar (màx. %sB)" tag1=$post_max_size}</label>
                    <input id="importFile" type="file" name="importFile" style="width:250px;" />
                </span>
                <em class="z-formnote z-sub">{gt text="La codificació de l'arxiu ha de ser utf-8."}</em>
                <div id="selTableImp" style="display:none;">
                    <span><label for="table">{gt text="Selecciona la taula destí de la importació"}</label>
                        <select id="taula_imp" name="taula_imp" style="width:250px;">
                            {html_options values=$tables output=$tables}
                        </select>  
                    </span>
                    </p>
                    <div id="btn_import" class="z-formbuttons z-buttons">
                        {button src='button_ok.png' set='icons/extrasmall' __alt='Importa' __title='Importa' __text='Importa'}
                        </a>
                    </div>
                </div>
            </div>            
        </form>
    </div>
    <form class="z-form" id="frmExport" name="frmExport" action="{modurl modname='Llicencies' type='admin' func='exportaTaula'}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <div id="tabs-2">
            <div class="z-formrow">
                <span>
                    <label for="table">{gt text="Selecciona la taula a exportar"}</label>
                    <select id="taula_exp" name="taula_exp" style="width:270px;">
                        {html_options options=$tables}
                    </select>    
                </span>
                <div id="btn_export" class="z-formbuttons z-buttons" style="padding-top:20px">
                    {button src='button_ok.png' set='icons/extrasmall' __alt='Exporta' __title='Exporta' __text='Exporta'}                       
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    jQuery(function() {
        jQuery("#tabs").tabs();
    });

    (function(jQuery) {
    jQuery.fn.checkFileType = function(options) {
    var defaults = {
    allowedExtensions: [],
            success: function() {},
            error: function() {}
    };
            options = jQuery.extend(defaults, options);
            return this.each(function() {

            jQuery(this).on('change', function() {
            var value = jQuery(this).val(),
                    file = value.toLowerCase(),
                    extension = file.substring(file.lastIndexOf('.') + 1);
                    if (jQuery.inArray(extension, options.allowedExtensions) == - 1) {
            options.error();
                    jQuery(this).focus();
            } else {
            options.success();
            }

            });
            });
    };
    })(jQuery);
            jQuery(function() {
            jQuery('#importFile').checkFileType({
            allowedExtensions: ['csv'],
                    success: function() {
                    //alert('Success');
                    jQuery('#selTableImp').show();
                    },
                    error: function() {
                    //alert('Error');
                    jQuery('#selTableImp').hide();
                    }
            });
            });
</script>