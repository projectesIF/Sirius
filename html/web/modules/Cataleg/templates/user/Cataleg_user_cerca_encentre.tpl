
{include file="user/Cataleg_user_menu_cerca.tpl"}

<h1>{gt text="Cerca d'activitats en centre"} - <a href="{modurl modname='Cataleg' type='user' func='cataleg' catId=$catId}">{$cataleg}</a></h1>

<div class="z-buttonrow z-buttons z-left">  
    {if $opcio eq "codi"}
        <a id="cataleg_button_cercacodi" href="#"  style="cursor: not-allowed; color: #CCCCCC;" >{img modname='core' src='filter.png' set='icons/extrasmall' alt='Per codi' title='Per codi'} {gt text='Per codi'}</a>
    {else}
        <a id="cataleg_button_cercacodi" href="{modurl modname='Cataleg' type='user' func='cercacentre' catId=$catId}" >{img modname='core' src='filter.png' set='icons/extrasmall' __alt='Per codi' __title='Per codi'} {gt text='Per codi'}</a>
    {/if}
    {if $opcio eq "zona"}
        <a id="cataleg_button_cercazona" href="#" style="cursor: not-allowed; color: #CCCCCC;" >{img modname='core' src='filter.png' set='icons/extrasmall' __alt='Per zona' __title='Per zona'} {gt text='Per zona'}</a>
    {else}
        <a id="cataleg_button_cercazona" href="{modurl modname='Cataleg' type='user' func='cercazona' catId=$catId}" >{img modname='core' src='filter.png' set='icons/extrasmall' __alt='Per zona' __title='Per zona'} {gt text='Per zona'}</a>
    {/if}
</div>

{if $opcio eq "codi"}
    {include file="user/Cataleg_user_cerca_codi.tpl"}
{/if}

{if $opcio eq "zona"}
    {include file="user/Cataleg_user_cerca_zona.tpl"}
{/if}

