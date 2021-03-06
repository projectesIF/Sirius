<script type="text/javascript" src="modules/IWmain/js/ColorPicker2.js"></script>
<script type="text/javascript" src="modules/IWmain/js/AnchorPosition.js"></script>
<script type="text/javascript" src="modules/IWmain/js/PopupWindow.js"></script>
{include file="IWforms_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='configure.png' set='icons/large'}</div>
    <h2>{gt text="Configure the module"}</h2>
    <h3>{gt text="Categories"}</h3>
    <table class="z-datatable">
        <thead>
            <tr>
                <th>Id</th>
                <th>{gt text="Name"}</th>
                <th>{gt text="Description"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {foreach item=cat from=$cats}
            <tr class="{cycle values='z-odd,z-even'}" {if isset($topic.tid)}id="topic_{$topic.tid}"{/if}>
                <td align="left">
                    {$cat.cid}
                </td>
                <td align="left">
                    {$cat.catName}
                </td>
                <td align="left">
                    {$cat.description}
                </td>
                <td align="center">
                    <a href="{modurl modname='IWforms' type='admin' func='editCat' cid=$cat.cid}">
                        {img modname='core' src='xedit.png' set='icons/extrasmall'   __alt="Edit" __title="Edit"}
                    </a>
                    <a href="{modurl modname='IWforms' type='admin' func='deleteCat' cid=$cat.cid}">
                        {img modname='core' src='14_layer_deletelayer.png' set='icons/extrasmall'   __alt="Delete" __title="Delete"}
                    </a>
                </td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="4" align="left">
                    {gt text="There is no defined categories"}
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    <a href="index.php?module=IWforms&type=admin&func=addCat">
        {gt text="Creates a new category"}
    </a>
    <h3>{gt text="Settings"}</h3>
    <form  class="z-form" enctype="application/x-www-form-urlencoded" method="post" name="conf" id="conf" action="{modurl modname='IWforms' type='admin' func='updateConf'}">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        {if not $multizk}
        <div class="z-formrow">
            <label for="directoriroot">{gt text="Home directory of files"}</label>
            <input type="text" name="directoriroot" size="50" maxlength="50" value="{$directoriroot}" onFocus='blur()' />
        </div>
        {/if}
        <div class="z-formrow">
            <label for="attached">{gt text="Folder on the server where the files are stored in the notes attached"}</label>
            <input type="text" name="attached" size="30" maxlength="30" value="{$attached}" />
        </div>
        {if $noFolder}
        <div class="z-formnote z-errormsg">
            {gt text="Can not find the directory for attachments"}
        </div>
        {/if}
        {if $noWriteable}
        <div class="z-formnote z-errormsg">
            {gt text="You should give write permissions in this folder"}
        </div>
        {/if}
        <div class="z-formrow">
            <label for="publicFolder">{gt text="Directory for the public files attached to the notes"}</label>
            <input type="text" name="publicFolder" size="30" maxlength="30" value="{$publicFolder}" />
        </div>
        {if $noPublicFolder}
        <div class="z-formnote z-errormsg">
            {gt text="Can not find the directory for the files public."}
        </div>
        {/if}
        {if $noPublicFolderWriteable}
        <div class="z-formnote z-errormsg">
            {gt text="You should give write permissions in this folder or set it as public"}
        </div>
        {/if}
        <div class="z-formrow">
            <label for="newsColor">{gt text="Color for the new notes"}</label>
            <div class="z-formnote">
                <input type="text" name="newsColor" id="newsColor" size="7" maxlength="7" style="background-color:{$newsColor};" value="{$newsColor}" /> <a href="#" onClick="pick('pick','newsColor');return false;" name="pick" id="pick">{gt text="Change the color"}</a>
            </div>
        </div>
        <div class="z-formrow">
            <label for="viewedColor">{gt text="Color for the visit notes"}</label>
            <div class="z-formnote">
                <input type="text" name="viewedColor" id="viewedColor" size="7" maxlength="7" style="background-color:{$viewedColor};" value="{$viewedColor}" /> <a href="#" onClick="pick('pick','viewedColor');return false;" name="pick" id="pick">{gt text="Change the color"}</a>
            </div>
        </div>
        <div class="z-formrow">
            <label for="completedColor">{gt text="Color to the notes given as completed"}</label>
            <div class="z-formnote">
                <input type="text" name="completedColor" id="completedColor" size="7" maxlength="7" style="background-color:{$completedColor};" value="{$completedColor}" /> <a href="#" onClick="pick('pick','completedColor');return false;" name="pick" id="pick">{gt text="Change the color"}</a>
            </div>
        </div>
        <div class="z-formrow">
            <label for="validatedColor">{gt text="Color per a les notes pendents de validació"}</label>
            <div class="z-formnote">
                <input type="text" name="validatedColor" id="validatedColor" size="7" maxlength="7" style="background-color:{$validatedColor};" value="{$validatedColor}" /> <a href="#" onClick="pick('pick','validatedColor');return false;" name="pick" id="pick">{gt text="Change the color"}</a>
            </div>
        </div>
        <div class="z-formrow">
            <label for="fieldsColor">{gt text="Color for the names of the fields"}</label>
            <div class="z-formnote">
                <input type="text" name="fieldsColor" id="fieldsColor" size="7" maxlength="7" style="background-color:{$fieldsColor};" value="{$fieldsColor}" /> <a href="#" onClick="pick('pick','fieldsColor');return false;" name="pick" id="pick">{gt text="Change the color"}</a>
            </div>
        </div>
        <div class="z-formrow">
            <label for="contentColor">{gt text="Color for the contents of the fields"}</label>
            <div class="z-formnote">
                <input type="text" name="contentColor" id="contentColor" size="7" maxlength="7" style="background-color:{$contentColor};" value="{$contentColor}" /> <a href="#" onClick="pick('pick','contentColor');return false;" name="pick" id="pick">{gt text="Change the color"}</a>
            </div>
        </div>
        <div class="z-buttons z-center">
            <a title="Change the state" onClick="javascript:send()">
                {img modname='core' src='button_ok.png' set='icons/small'} {gt text="Change the configuration"}
            </a>
        </div>
    </form>
</div>

<script type="text/javascript">
    var cp = new ColorPicker('window');
    // Runs when a color is clicked
    function pickColor(color) {
        field.value = color;
        changeColor();
    }
    var field;
    function pick(anchorname,camp) {
        field = eval('document.forms.conf.'+camp);
        cp.show(anchorname);
    }
    function changeColor(){
        document.forms.conf.newsColor.style.backgroundColor=document.forms.conf.newsColor.value;
        document.forms.conf.viewedColor.style.backgroundColor=document.forms.conf.viewedColor.value;
        document.forms.conf.completedColor.style.backgroundColor=document.forms.conf.completedColor.value;
        document.forms.conf.validatedColor.style.backgroundColor=document.forms.conf.validatedColor.value;
        document.forms.conf.fieldsColor.style.backgroundColor=document.forms.conf.fieldsColor.value;
        document.forms.conf.contentColor.style.backgroundColor=document.forms.conf.contentColor.value;
    }
</script>
