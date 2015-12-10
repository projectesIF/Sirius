<div class="z-form">
    <fieldset>
        <legend  style="font-weight: bold; color: #6A6A6A;"> Resultat </legend>

        {if $buida} 
            <div class="z-errormsg">
                {gt text="No s'ha passat cap criteri de cerca"}
            </div>        
        {else}
            {if @count($activitats) < 1}
                <div class="z-warningmsg">
                    <strong>{gt text="No s'ha trobat cap activitat que respongués als criteris sol·licitats"}</strong>
                </div>
            {else}  
                <div id="infoconsulta" class="z-statusmsg">                  
                    <strong>   {$activitats|@count} {gt text="activitats responen als criteris sol·licitats:"}</strong> 
                    <br>
                    {foreach from=$infoacti item='info' key='key' name=u}
                        {$info}<br>                   
                    {/foreach}
                </div>
                <ul>
                    {foreach from=$activitats item='acti' key='key' name=u}
                        <li> 
                            {if ($acti.activa eq 0)}{img modname='core' set='icons/extrasmall' height="8px" width="8px" src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
                            {if ($acti.prioritaria == 1)}{img modname='cataleg' height="8px" width="8px" src='star.png' title='Activitat prioritzada' alt='Activitat prioritzada'}{/if}
                            <a href=index.php?module=cataleg&type=user&func=show&actId={$acti.actId} target="_blank">{$acti.titol}</a>
                        </li>
                    {/foreach}
                </ul>
            {/if}
        {/if}
    </fieldset>
</div>


