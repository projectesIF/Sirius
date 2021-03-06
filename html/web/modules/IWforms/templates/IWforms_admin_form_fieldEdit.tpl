<div class="formContent">
    <div class="z-adminpageicon">{img modname='core' src='edit.png' set='icons/large'}</div>
    <h2>{gt text="Edit the field"} {if $itemField.fieldType neq 51}{$itemField.fieldName}{/if}</h2>
    <form class="z-form" id="edit" name="edit" action="{modurl modname='IWforms' type='admin' func='updateField'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="fid" value="{$item.fid}" />
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="fndid" value="{$itemField.fndid}" />
        <div class="z-formrow">
        <label for="fieldType">{gt text="Type field"}</label>
        <span><strong>{$fieldTypeText}</strong></span>
        </div>
        {if $itemField.fieldType eq 51}
        <div class="z-formrow">
            <label for="fieldName">{gt text="Informative text"}</label>
            <textarea id="fieldName" name="fieldName" rows="10" cols="50" style="width: 300px" id="intrawebFormsAdmin">{$itemField.fieldName}</textarea>
        </div>
        {else}
        <div class="z-formrow">
            <label for="fieldName">{gt text="Name field"}</label>
            <input id="fieldName" name="fieldName" type="text" size="50" maxlength="3000" value="{$itemField.fieldName}" onblur="if(this.value=='') this.value='{gt text="Name field"}';" onfocus="if(this.value=='{gt text="Name field"}') this.value='';" />
        </div>
        {/if}

        {if not "-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="description">{gt text="Description of the field"}</label>
            <input id="description" name="description" type="text" size="50" maxlength="255" value="{$itemField.description}"/>
        </div>
        {/if}
        {if not "-51-52-53-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="help">{gt text="Text of the help icon"}</label>
            <textarea id="help" name="help" rows="10" cols="50" id="intrawebFormsAdmin">{$itemField.help}</textarea>
        </div>
        {/if}
        {if "-7-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="publicFile">{gt text="Public File"}</label>
            <input id="publicFile" name="publicFile" type="checkbox" {if $itemField.publicFile}checked{/if} value="1" />
            <a onClick="return overlay(this, 'help1')">{img modname='core' src='info.png' set='icons/extrasmall'}</a>
        </div>
        <div class="z-formrow">
            <label for="extensions">{gt text="Restringed extensions (separated by commas)"}</label>
            <input id="extensions" name="extensions" type="text" size="50" maxlength="255" value="{$itemField.extensions}" />
        </div>
        <div class="z-formrow">
            <label for="imgWidth">{gt text="Thumbnail width (only images gif, jpg and png. 0 for original size)"}</label>
            <input id="imgWidth" name="imgWidth" type="text" size="7" maxlength="4" value="{$itemField.imgWidth}" />
        </div>
        <div class="z-formrow">
            <label for="imgHeight">{gt text="Thumbnail height (only images gif, jpg and png. 0 for original size)"}</label>
            <input id="imgHeight" name="imgHeight" type="text" size="7" maxlength="4" value="{$itemField.imgHeight}" />
        </div>
        {/if}
        {if "-1-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="size">{gt text="Size of the box"}</label>
            <input id="size" name="size" type="text" size="5" maxlength="3" value="{$itemField.size}"/>
        </div>
        {/if}
        {if "-2-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="cols">{gt text="No. Column"}</label>
            <input id="cols" name="cols" type="text" size="5" maxlength="3" value="{$itemField.cols}"/>
        </div>
        {/if}
        {if "-2-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="rows">{gt text="No. Row"}</label>
            <input id="rows" name="rows" type="text" size="5" maxlength="3" value="{$itemField.rows}"/>
        </div>
        {/if}
        {if "-2-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="editor">{gt text="Incorporates the HTML editor"}</label>
            <input id="editor" name="editor" type="checkbox" {if $itemField.editor}checked{/if} value="1" />
            <br />
            <label for="editor">&nbsp;</label>
            <span>({gt text="Available only if the editor is active and set on"})</span>
        </div>
        {/if}
        {if "-8-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="checked">{gt text="Default yes"}</label>
            <input id="checked" name="checked" type="checkbox" {if $itemField.checked}checked{/if} value="1" />
        </div>
        {/if}
        {if "-6-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="options">{gt text="List options"}</label>
            <input id="options" name="options" type="text" size="50" value="{$itemField.options}"/>
            <div class="z-informationmsg">{gt text="The options must be separated by the hyphen (-). To leave empty the first option, you must write a hyphen before the first one."}</div>
            <label for="options">{gt text="List of members into a groups"}</label>
            <span>
                <select name="gid">
                    {foreach item=group from=$groups}
                        <option {if $itemField.gid eq $group.id}selected{/if} value="{$group.id}">{$group.name}</option>
                    {/foreach}
                </select>
            </span>
            <div class="z-informationmsg">{gt text="The group's members are added to the list defined in the option above."}</div>
        </div>
        {/if}

        {if not "-51-52-53-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="searchable">{gt text="The validators can filter using this field"}</label>
            <input id="searchable" name="searchable" type="checkbox" {if $itemField.searchable}checked{/if} value="1" />
        </div>
        {/if}
	{if "-4-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="calendar">{gt text="Make your choice with a calendar in JavaScript"}</label>
            <input id="calendar" name="calendar" type="checkbox" {if $itemField.calendar}checked{/if} value="1" />
        </div>
        {/if}
	{if "-52-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="height">{gt text="Thickness of the line"}</label>
            <input id="height" name="height" type="text" size="5" maxlength="2" value="{$itemField.height}"/>
            px
        </div>
        {/if}
        {if "-52-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="color">{gt text="Color Line"}</label>
            <input id="color" name="color" type="text" size="10" maxlength="7" value="{$itemField.color}" style="background-color:{$itemField.color};"/>
            <a href="#" onClick="pick('pick','color');return false;" name="pick" id="pick">{gt text="Choose a color"}</a>
        </div>
        {/if}
        {if "-53-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="colorf">{gt text="Background color"}</label>
            <input id="colorf" name="colorf" type="text" size="10" maxlength="7" value="{$itemField.colorf}" style="background-color:{$itemField.colorf};"/>
            <a href="#" onClick="pick('pick','colorf');return false;" name="pick" id="pick">{gt text="Choose a color"}</a>
        </div>
        {/if}
        {if not "-8-51-52-53-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="required">{gt text="Required"}</label>
            <input id="required" name="required" type="checkbox" {if $itemField.required}checked{/if} value="1" />
        </div>
        {/if}
        {*}
        {if not "-8-51-52-53-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="feedback">{gt text="Supports comments"}</label>
            <input id="feedback" name="feedback" type="checkbox" {if $itemField.feedback}checked{/if} value="1" />
        </div>
        {/if}
        {*}
        {*}
        {if not "-8-51-52-53-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="validationNeeded">{gt text="Need validation"}</label>
            <input id="validationNeeded" name="validationNeeded" type="checkbox" {if $itemField.validationNeeded}checked{/if} value="1" />
        </div>
        {/if}
        {*}
        {*}
        {if not "-8-51-52-53-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="notify">{gt text="Send a private message to validators"}</label>
            <input id="notify" name="notify" type="checkbox" {if $itemField.notify}checked{/if} value="1" />
        </div>
        {/if}
        {*}
        {*}
        {if not "-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="accessType">{gt text="Type of acces"}</label>
            <select id="accessType" name="accessType">
                <option {if $itemField.accessType eq 0}selected{/if} value="0">{gt text="Users can read and write"}</option>
                <option {if $itemField.accessType eq 1}selected{/if} value="1">{gt text="Users only can read"}</option>
                <option {if $itemField.accessType eq 2}selected{/if} value="2">{gt text="Only validators"}</option>
            </select>
        </div>
        {/if}
        {*}
        {if not "-8-51-52-53-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="editable">{gt text="Editable"}</label>
            <input id="editable" name="editable" type="checkbox" {if $itemField.editable}checked{/if} value="1" />
        </div>
        {/if}
        {if not "-100-"|strstr:$fieldTypePlus}
        <div class="z-formrow">
            <label for="active">{gt text="Active/no active"}</label>
            <input id="active" name="active" type="checkbox" {if $itemField.active}checked="checked"{/if} value="1" />
        </div>
        {/if}
        <div class="z-center">
            <span class="z-buttons">
                <a onClick="javascript:forms['edit'].submit()">
                    {img modname='core' src='button_ok.png' set='icons/small' __alt="Edit" __title="Edit"} {gt text="Edit"}
                </a>
            </span>
            <span class="z-buttons">
                <a href="{modurl modname='IWforms' type='admin' func='form' action='field' fid=$item.fid}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"}
                    {gt text="Cancel"}
                </a>
            </span>
        </div>
    </form>
</div>
<div id="help1" class="helpBox">
    <div class="helpHeader"><div class="helpTitle">{gt text="Public File"}</div></div>
    <div class="helpContent">
        {gt text="If the file is public will be accessible to everyone from a URL like:"}<br />{$publicFileURL}
        <div class="helpBoxClose"><a href="#" onClick="overlayclose('help1'); return false">{gt text="Close the window"} {img modname='IWmain' src='postitcloseicon.png'}</a></div>
    </div>
</div>