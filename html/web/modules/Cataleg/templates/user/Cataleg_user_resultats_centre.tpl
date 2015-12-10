<div class="z-form">
    <fieldset>
        <legend  style="font-weight: bold; color: #6A6A6A;"> Resultat </legend>
        {if $total < 1 }
            <div class="z-warningmsg">
                <strong>{gt text="No s'ha trobat cap activitat que respongués als criteris sol·licitats"}</strong>
            </div>
        {else}  
            <div class="z-statusmsg">
                <strong>   {$total} {gt text="activitats es realitzen a algun dels centres sol·licitats:"}</strong> 
                <br>                            
                {foreach from=$acentres item='centre' key='key' name=u}
                    {if $centre.CODI_ENTITAT    != ''}
                        {if $centre.trobat eq '1'}
                {*            {$key} - {$centre.CODI_TIPUS_ENTITAT} {$centre.NOM_ENTITAT} ({$centre.NOM_LOCALITAT}) <br>                   *}
                        {else}
                            <span style="text-decoration:line-through;">{$key} - {$centre.CODI_TIPUS_ENTITAT} {$centre.NOM_ENTITAT} ({$centre.NOM_LOCALITAT})</span>  NO S'HA TROBAT ACTIVITATS PER A QUEST CENTRE</span> <br>                   
                        {/if}
                    {else}
                        <span style="text-decoration:line-through;">{$key}</span> - CODI NO TROBAT <br>
                    {/if}
                {/foreach}
            </div>

            {assign var='passacentre' value=''}
            {foreach from=$activitats item='acti' key='key' name=u}
                {if $passacentre != $acti.NOM_ENTITAT} 
                    {if $passacentre != ''}                        
                        </ul>
                    </fieldset>
                {/if}  
                <fieldset>
                    <legend>{$acti.centre} {$acti.CODI_TIPUS_ENTITAT} {$acti.NOM_ENTITAT} - {$acti.NOM_LOCALITAT} ({$acti.NOM_DT})</legend>
                    <ul>
                    {/if}
                    <li>  
                        {if ($acti.activa eq 0)}{img modname='core' set='icons/extrasmall' height="8px" width="8px" src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
                        {if ($acti.prioritaria == 1)}{img modname='cataleg' height="8px" width="8px" src='star.png' title='Activitat prioritzada' alt='Activitat prioritzada'}{/if}
                        <a href="index.php?module=cataleg&type=user&func=show&actId={$acti.actId}" target="_blank">{$acti.titol}</a>
                    </li>
                    {assign var='passacentre' value=$acti.NOM_ENTITAT}    
                {/foreach}

            {/if}
    </fieldset>
</div>


