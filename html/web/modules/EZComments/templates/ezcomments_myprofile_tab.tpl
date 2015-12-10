<h3>{gt text="Pinboard for member"} {$uname}</h3>
{insert name="getstatusmsg"}

{if $viewer_uid gt 1}

    {if $settings.nocomments eq "1"}

        {gt text="The user has deactivated the pinboard for his account" domain="module_ezcomments"}

    {else}

        {gt text="Pinboard entries" domain="module_ezcomments"}: {modapifunc modname="EZComments" type="user" func="countitems" mod="MyProfile" objectid=$uid status="0"}<br /><br />
        {modurl modname="MyProfile" func="display" uid=$uid pluginname="EZComments" order="1" assign="viewUrl"}
        {* modcallhooks hookobject=item hookaction=display hookid=$uid module="MyProfile" returnurl=$viewUrl owneruid=$uid implode=false *}
        {* $hooks.EZComments *}

    {/if}

{else}

    {gt text="Pinboard entries are only visible for registered or logged in users." domain="module_ezcomments"}

{/if}
