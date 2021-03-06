<div class="z-formrow">
    <label for="feeds_feedid">{gt text='Feed' domain='module_feeds'}</label>
    <select id="feeds_feedid" name="feedid">
        {html_options options=$allfeeds selected=$feedid}
    </select>
</div>

<div class="z-formrow">
    <label for="feeds_displayimage">{gt text='Display feed image' domain='module_feeds'}</label>
    <input id="feeds_displayimage" name="displayimage" type="checkbox" value="1"{if $displayimage eq 1}checked="checked"{/if} />
    <em class="z-formnote">{gt text='(if available in feed)' domain='module_feeds'}</em>
</div>

<div class="z-formrow">
    <label for="feeds_displaydescription">{gt text='Display feed description' domain='module_feeds'}</label>
    <input id="feeds_displaydescription" name="displaydescription" type="checkbox" value="1"{if $displaydescription eq 1}checked="checked"{/if} />
    <em class="z-formnote">{gt text='(if available in feed)' domain='module_feeds'}</em>
</div>

<div class="z-formrow">
    <label for="feeds_alternatelayout">{gt text='Use alternate layout' domain='module_feeds'}</label>
    <input id="feeds_alternatelayout" name="alternatelayout" type="checkbox" value="1"{if $alternatelayout eq 1}checked="checked"{/if} />
</div>

<div class="z-formrow">
    <label for="feeds_numitems">{gt text='Number of items to display' domain='module_feeds'}</label>
    <input id="feeds_numitems" type="text" name="numitems" value="{$numitems|safetext}" />
    <em class="z-formnote">{gt text='(upto the maximum supplied by the feed or -1 for all items)' domain='module_feeds'}</em>
</div>
