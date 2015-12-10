<div class="z-form">
    <fieldset>
        <legend  style="font-weight: bold; color: #6A6A6A;"> Resultat </legend>
        {if $total < 1 }
            <div class="z-warningmsg">
                <strong>{gt text="No s'ha trobat cap activitat que respongués als criteris sol·licitats"}</strong>
            </div>
        {else}  
            <div class="z-statusmsg">
                <strong>   {$total} {gt text="activitats es realitzen a la zona de la DT de "}{$delegacio}</strong> 
                <br>
                {foreach from=$acentres item='centre' key='key' name=u}
                    {if $centre.CODI_ENTITAT    != ''}
                        {if $centre.trobat eq '1'}
                            {$key} - {$centre.CODI_TIPUS_ENTITAT} {$centre.NOM_ENTITAT} ({$centre.NOM_LOCALITAT}) <br>                   
                        {else}
                            <span style="text-decoration:line-through;">{$key} - {$centre.CODI_TIPUS_ENTITAT} {$centre.NOM_ENTITAT} ({$centre.NOM_LOCALITAT})</span>  NO S'HA TROBAT ACTIVITATS PER A QUEST CENTRE</span> <br>                   
                        {/if}
                    {else}
                        <span style="text-decoration:line-through;">{$key}</span> - CODI NO TROBAT <br>
                    {/if}
                {/foreach}
            </div>


            {assign var='localitat' value=''}
            {foreach from=$activitats item='acti' key='key' name=u}
                {if $localitat != $acti.NOM_LOCALITAT} 
                    {if $localitat != ''}                        
                    </ul>
                    </fieldset>
                {/if}             
                <fieldset>
                    {*                     <legend style="border-bottom:solid; border-left:solid; border-width:2px; "> {$acti.NOM_LOCALITAT} </legend>*}
                    <legend> {$acti.NOM_LOCALITAT} </legend>
                    <ul>
                    {/if}
                    <li> 
                        {$acti.CODI_ENTITAT} - {$acti.CODI_TIPUS_ENTITAT} {$acti.NOM_ENTITAT} {if ($acti.activa eq 0)}{img modname='core' set='icons/extrasmall' height="8px" width="8px" src='button_cancel.png' __title='Finalment no ofertada' __alt='Finalment no ofertada'}{/if}
                            {if ($acti.prioritaria == 1)}{img modname='cataleg' height="8px" width="8px" src='star.png' title='Activitat prioritzada' alt='Activitat prioritzada'}{/if}
                            <a href="index.php?module=cataleg&type=user&func=show&actId={$acti.actId}" target="_blank">{$acti.titol}</a>      
                    </li>
                    {assign var='localitat' value=$acti.NOM_LOCALITAT}                    
             {/foreach}
            {/if}
    </fieldset>
</div>


