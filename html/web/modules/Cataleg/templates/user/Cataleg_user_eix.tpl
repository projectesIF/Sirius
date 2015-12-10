{if $eixinfo.nom != ""}

    <input type=hidden name="eixId" id="eixId" value={$eixinfo.eixId}>    

    {if isset($cerca) && $cerca}
        <span style="font-weight:bold; margin-left: 19px" >{gt text="Eix"}</span>&nbsp;&nbsp;
       {$eixinfo.ordre} - {$eixinfo.nom}
    {else}
        {*<h2 style="color: #457FB9;">Eix {$eixinfo.ordre}: {$eixinfo.nom} </h2> *}
        <h2 style="font-size:1.5em"><span style="font-weight:bold;" >{gt text="Eix"}</span>&nbsp;&nbsp;
            <span style='font-size:0.9em'>{$eixinfo.ordre} - {$eixinfo.nom}</span></h2>
        {/if}
    {/if}
