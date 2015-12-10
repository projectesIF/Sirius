<script src="javascript/jquery/jquery-1.7.0.js"></script>
<script src="javascript/colorbox/jquery.colorbox.js"></script>
<script type="text/javascript"> var $jq=jQuery.noConflict(true);</script>
<link rel="stylesheet" type="text/css" href="style/colorbox.css" />

<script>
$jq(document).ready(function(){
    //Examples of how to assign the ColorBox event to elements
    $jq(".iframe").colorbox({iframe:true, width:"60%", height:"50%", title:"{{gt text='Informació de la unitat'}}"});
    $jq(".inline").colorbox({inline:true, width:"60%", height:"70%", title:"{{gt text='Informació de la unitat'}}"});
    $jq(".callbacks").colorbox({
        onOpen:function(){ },
        onLoad:function(){ },
        onComplete:function(){ },
        onCleanup:function(){ },
        onClosed:function(){ }
    });
				
    //Example of preserving a JavaScript event for inline calls.
    $("#click").click(function(){ 
        $('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
        return false;
    });
});
</script>
{if (!isset($doc))}
{foreach from=$unitats item='unitat' name='unitat'}
    <div style='display:none'>
        <div class="news_body" id='inline_content-{$unitat.uniId}' style='padding:15px; background:#fff; text-align: left; '>

            <h4>Unitat</h4>
            <h3 class="news_title">{$unitat.nom|safehtml}</h3>
            <hr>
            <br>

            {if $unitat.numresp > 0}

                {if $unitat.numresp > 1}
                    <h4>Responsables</h4>
                {else}
                    <h4>Responsable</h4>
                {/if}

                <table class="z-datatable" style='font-size:0.8em;'>
                    {assign var='responsables' value=$unitat.resp}
                    {foreach from=$responsables item='responsable' name='responsable'}

                        <tr>
                            <td>
                                {$responsable.responsable|safehtml}
                            </td>
                            <td>
                                <a href="mailto:{$responsable.email|safehtml}">{$responsable.email|safehtml}</a>
                            <td>
                                {$responsable.telefon|safehtml}
                            </td>
                        </tr>
                    {/foreach}
                </table>
                <br>
            {/if}

            {if !empty($unitat.descripcio)}
            {*<h4>Descripció de les actuacions de la unitat en matèria de formació</h4>*}
            <h4>{gt text="Actuacions i objectius de la unitat en matèria de formació"}</h4>
            <div class="news_hometext" style='font-size:0.8em; text-align:justify ' > 
                {$unitat.descripcio}                  
            </div>    
            {/if}

        </div>
    </div>
{/foreach}  
{else}
<div class="news_body" id='inline_content-{$unitat.uniId}' style='padding:15px; background:#fff; text-align: left; '>
            <h4>Unitat</h4>
            <h3 class="news_title">{$unitat.nom|safehtml}</h3>
            <hr>
            <br>

            {if $unitat.numresp > 0}

                {if $unitat.numresp > 1}
                    <h4>Responsables</h4>
                {else}
                    <h4>Responsable</h4>
                {/if}

                <table>
                    {assign var='responsables' value=$unitat.resp}
                    {foreach from=$responsables item='responsable' name='responsable'}

                        <tr>
                            <td>
                                {$responsable.responsable|safehtml}
                            </td>
                            <td>
                                <a href="mailto:{$responsable.email|safehtml}">{$responsable.email|safehtml}</a>
                            <td>
                                {$responsable.telefon|safehtml}
                            </td>
                        </tr>
                    {/foreach}
                </table>
                <br>
            {/if}

            {if !empty($unitat.descripcio)}
            {*<h4>Descripció de les actuacions de la unitat en matèria de formació</h4>*}
            <h4>{gt text="Actuacions i objectius de la unitat en matèria de formació"}</h4>
            <div style='text-align:justify ' > 
                {$unitat.descripcio}                  
            </div>    
            {/if}

        </div>
{/if}
