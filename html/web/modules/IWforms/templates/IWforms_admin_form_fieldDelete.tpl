<div class="formContent">
    <div class="z-adminpageicon">{img modname='core' src='editdelete.png' set='icons/medium'}</div>
    <h2>{gt text="Delete the field"}</h2>

    <form class="z-form" action="{modurl modname='IWforms' type='admin' func='deleteField'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="fid" value="{$item.fid}" />
        <input type="hidden" name="confirm" value="1" />
        <input type="hidden" name="fndid" value="{$itemField.fndid}" />
        {if $dependancesTo|@count eq 0}
        {gt text="Confirms delete the field"} <strong>{$itemField.fieldName}</strong> {gt text="type"} <strong>{$fieldTypeText}</strong>
        {else}
        {gt text="Unable to delete the field as there are other areas that are depending on. Clear dependencies before deleting the field. Here is the list of areas affected:"}
        <ul>
            {foreach item=dep from=$dependancesTo}
            <li>{$dep.fndid} - {$dep.fieldName}</li>
            {/foreach}
        </ul>
        {/if}
        <div class="z-center">
            {if $dependancesTo|@count eq 0}
            <span class="z-buttons">
                {button src='button_ok.png' set='icons/small' __alt="Delete" __title="Delete"}
            </span>
            {/if}
            <span class="z-buttons">
                <a href="{modurl modname='IWforms' type='admin' func='form' action='field' fid=$item.fid}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"}
                </a>
            </span>
        </div>
    </form>
</div>