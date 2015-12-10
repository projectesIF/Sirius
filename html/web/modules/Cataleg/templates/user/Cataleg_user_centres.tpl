{*
* Plantilla: Cataleg_user_centres.tpl
* Àmbit d'ús: edició de centres relacionats amb una activitat 
* Cataleg_user_persDest -> Cataleg_user_fitxaActivitat
* Mostra informació relativa als codis de centre introduits
*}
{if isset($nomsCentres)}
    {if @count($nomsCentres.exist)>0}
    <div class="z-statusmsg">
        {foreach item="centre" from=$nomsCentres.exist}
            <span style="font-weight:bold">{$centre.CODI_ENTITAT} - {$centre.NOM_TIPUS_ENTITAT} {$centre.NOM_ENTITAT} </span>- {$centre.NOM_LOCALITAT} ({$centre.NOM_DT})
            <br />
        {/foreach}     
    </div>
    {/if}
    {if @count($nomsCentres.no_exist)>0}
        <div class="z-errormsg">
            <legend>{gt text= "Els codis següents no corresponen a centres:"}</legend>
            <span style="font-weight:bold;">
                {foreach item="centre" from=$nomsCentres.no_exist}
                    {$centre}<br />
                {/foreach}      
            </span>
        </div>
    {/if}
{/if}



