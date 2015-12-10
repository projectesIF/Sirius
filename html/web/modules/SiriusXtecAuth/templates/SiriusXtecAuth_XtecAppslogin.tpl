{if $logtype eq 'in'}<p class="z-informationmsg">{gt text="El sistema prova de validar les credencials XTEC als aplicatius de gestió gtaf i e13."}</p>
    {if $logoutXtecApps}
    <p class="z-informationmsg">{gt text="En sortir de Sirius, també es tancarà la sessió dels altres aplicatius."}</p>
    {else}
    <p class="z-informationmsg">{gt text="No es tancarà la sessió dels altres aplicatius fins que es tanqui el navegador."}</p>
    {/if}
{/if}
{if $logtype eq 'out'}
	 <p class="z-informationmsg">{gt text="Tancant les sessions dels aplicatius de gestió..."}</p>
{/if}
<iframe src="{$gtafLogin}" style="display:none"></iframe>
<iframe src="{$e13Login}" style="display:none"></iframe>
<script type="text/JavaScript">
<!--
setTimeout("location.href = 'index.php';",{{$sctime}});
-->
</script>
