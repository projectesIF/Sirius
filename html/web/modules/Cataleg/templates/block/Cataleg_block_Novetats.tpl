{* Bloc Novetats del catàleg *}
<div id="Cataleg_block_novetats" style="padding-left: 6px">
    {if $novetats.showNew}
        {img style="float:right" modname='cataleg' src='nova.png'}<h4> {gt text="Novetats"}</h4><hr>
        {if (isset($novetats.novetats) && ($novetats.novetats|@count > 0)) }
            <ul>
                {foreach item=novetat from=$novetats.novetats}
                    <li>
                        {if ($novetat.activa eq 0)}{img modname='core' set='icons/extrasmall' height="8px" width="8px" src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
                        {if ($novetat.prioritaria == 1)}{img modname='cataleg' height="8px" width="8px" src='star.png' title='Activitat prioritzada' alt='Activitat prioritzada'}{/if}
                        <a href="{modurl modname='Cataleg' type='user' func='show' actId=$novetat.actId}">{if strlen($novetat.tGTAF) > 0}{$novetat.tGTAF}-{/if}{$novetat.titol|safehtml}</a>
                    </li>  
                {/foreach}
            </ul>
        {else}
            <ul><li style="font-weight:bold; color: #59110A;">{gt text="Sense novetats"}</li></ul>
        {/if}
    {/if}    
    {if $novetats.showMod}
        {if (isset($novetats.canvis) && ($novetats.canvis|@count > 0)) }
            {if (isset($novetats.novetats) && ($novetats.novetats|@count > 0)) && $novetats.showNew}<br /><br /> {/if}
            {img style="float:right" modname='cataleg' src='mod.png'}<h4> {gt text="Modificacions"}</h4><hr>
            <ul>
                {foreach item= novetat from=$novetats.canvis}
                    <li>
                        {if ($novetat.activa eq 0)}{img modname='core' set='icons/extrasmall' height="8px" width="8px" src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
                        {if ($novetat.prioritaria == 1)}{img modname='cataleg' height="8px" width="8px" src='star.png' title='Activitat prioritzada' alt='Activitat prioritzada'}{/if}
                        <a href="{modurl modname='Cataleg' type='user' func='show' actId=$novetat.actId}">{if strlen($novetat.tGTAF) > 0}{$novetat.tGTAF}-{/if}{$novetat.titol|safehtml}</a>
                    </li>
                {/foreach}
            </ul><br />
        {/if}
    {/if}
</div>

