<table id="note_{$note.fmid}" class="noteContent" bgcolor="{$note.color}">
    <tr>
        <td width="10%">
            {if $note.photo neq ''}
            <div style="height:10px; text-align:center;"></div>
            <img src="{$baseurl}index.php?module=IWforms&type=user&func=getFile&fileName={$note.photo}" />
            {/if}
        </td>
        <td width="30%">
            {if $note.user neq 0}
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
        </td>
        <td width="60%">
            <div>
                {if $note.user neq 0}
                {$users[$note.user]}
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
            {if $content.fieldType eq 7 && $content.content neq ''}
            <img src="modules/IWmain/images/fileIcons/{IWformsfileicon fileName=$content.content}" />
            <a href="{modurl modname='IWforms' type='user' func='download' fid=$fid fndid=$content.fndid fileName=$content.content}">
                {$content.content|nl2br|safehtml}
            </a>
            {else}
            <div class="messageBody">
                {$content.content|nl2br|safehtml}
            </div>
            {/if}
        </td>
    </tr>
    {/foreach}
    {if $note.publicResponse eq 1}
    <tr>
        <td colspan="2" width="40%">{gt text="Answer"}</td>
        <td width="60%">
            {$note.renote|nl2br|safehtml}
        </td>
    </tr>
    {/if}
</table>
