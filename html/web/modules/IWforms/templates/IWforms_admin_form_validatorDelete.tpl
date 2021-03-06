<div class="formContent">
    <div class="z-adminpageicon">{img modname='core' src='editdelete.png' set='icons/large'}</div>
    <h2>{gt text="Delete the validator"}</h2>
    <form id="deleteValidator" class="z-form" action="{modurl modname='IWforms' type='admin' func='deleteValidator'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="fid" value="{$item.fid}" />
        <input type="hidden" name="confirm" value="1" />
        <input type="hidden" name="rfid" value="{$validatorId}" />
        <input type="hidden" name="aio" value="{$aio}" />
        {gt text="Confirms delete the validator"} <strong>{$userName}</strong>
        <div class="z-center">
            <span class="z-buttons">
                <a onClick="javascript:forms['deleteValidator'].submit()">{img modname='core' src='button_ok.png' set='icons/small' __alt="Delete" __title="Delete"} {gt text="Delete"}</a>
            </span>
            <span class="z-buttons">
                {if isset($aio)}
                <a href="{modurl modname='IWforms' type='admin' func='infoForm' fid=$item.fid}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
                </a>
                {else}
                <a href="{modurl modname='IWforms' type='admin' func='form' action='validators' fid=$item.fid}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
                </a>
                {/if}
            </span>
        </div>
    </form>
</div>