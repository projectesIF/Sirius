{switch expr=$type}
     {case expr='status'}
         <div class='z-statusmsg'>
     {/case}
     {case expr='info'}
         <div class='z-informationmsg'>
     {/case}
     {case expr='error'}
         <div class="z-errormsg">
     {/case}
    {/switch}
    {$msg}
</div>