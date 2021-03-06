<table id="note_{$note.fmid}" class="noteContent" bgcolor="{$note.color}">
    <tr>
        <td width="10%">
            {if isset($users[$note.photo].a) && $users[$note.photo].a neq ''}
            {assign var='user' value=$users[$note.photo].a}
            {assign var='photoPath' value="$usersPictureFolder/$user"}
            <img src="{$baseurl}index.php?module=IWforms&type=user&func=getFile&fileName={$photoPath}" />
            {/if}
        </td>
        <td width="30%">
            {if $note.user eq '-1' || $users[$note.user] neq ''}
            <div>
                {gt text="User who sent the note"}
            </div>
            {/if}
            <div>
                {gt text="Date of sending the note"}
            </div>
            <div>
                {gt text="Time of sending the note"}
            </div>
            <div>
                {if $note.marked eq 1}
                {img modname='core' src='flag.png' set='icons/small' id=$note.fmid}
                {else}
                {img modname='IWforms' src='none.gif' id=$note.fmid}
                {/if}
            </div>
        </td>
        <td width="60%">
            <div>
                {if $note.user neq 0 && $note.user neq '-1'}
                {$users[$note.user].ccn} ({$users[$note.userName].l})
                {/if}
                {if $note.user eq '-1'}
                {gt text="User not registered"}
                {/if}
            </div>
            <div>
                {$note.day}
            </div>
            <div>
                {$note.time}
            </div>
        </td>
    </tr>
    {foreach item="content" from=$note.content}
    <tr>
        <td bgcolor="{$fieldsColor}" colspan="2" width="40%">
            {$content.fieldName}
        </td>
        <td bgcolor="{$contentColor}" width="60%">
            <div id="note_content_{$content.fnid}">
                <div style="float:left;" class="messageBody">
                    {if $content.fieldType eq 7 && $content.content neq ''}
                    <img src="modules/IWmain/images/fileIcons/{IWformsfileicon fileName=$content.content}" style="vertical-align: middle;" />
                    <a href="{modurl modname='IWforms' type='user' func='download' fid=$fid fndid=$content.fndid fileName=$content.content}">{$content.content|nl2br|safehtml}</a>
                    {else}
                    {$content.content|nl2br|safehtml}
                    {/if}
                </div>
                {if $content.editable eq 1}
                <div style="float:right;">
                    <a href="javascript:editNoteManageContent({$content.fnid},'content')">
                        {img modname='core' src='xedit.png' set='icons/extrasmall' __alt="Edit content" __title="Edit content"}
                    </a>
                </div>
                {/if}
            </div>
        </td>
    </tr>
    {/foreach}
    <tr>
        <td colspan="2" width="40%">{gt text="Private notes of those responsible"}</td>
        <td width="60%">
            {include file="IWforms_user_manageNoteContentObs.tpl" do="print"}
        </td>
    </tr>
    <tr>
        <td colspan="2" width="40%">{gt text="Answer"}</td>
        <td width="60%">
            {include file="IWforms_user_manageNoteContentRenote.tpl" do="print"}
        </td>
    </tr>
    <tr>
        <td colspan="3" width="100%">
            {if $note.synchronize}
            <div style="float: right; margin-left: 5px;">
                | <a href="{modurl modname='IWforms' type='user' func='synchro' fid=$fid init=$init filterValue=$filterValue filter=$filter fmid=$note.fmid order=$order ipp=$ipp}">
                    {img modname='core' src='quick_restart.png' set='icons/extrasmall' __alt="Synchronize fields" __title="Synchronize fields"}
                </a>
            </div>
            {/if}
            <div style="float:right" id="note_options_{$note.fmid}">
                {include file="IWforms_user_manageNoteContentOptions.tpl"}
            </div>
        </td>
    </tr>
</table>
