{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Assignació dels grups de Zikula generals del catàleg"}</h3>
</div>
   <form name="grupsZikula" id="grupsZikula" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <fieldset>
           <legend>{gt text='Assignació de les variables de mòdul'}</legend>
           <div class="z-formrow">
               <label for="Sirius">{gt text="Grup general- <b>Usuaris de Sirius</b>"}</label>
               <select type="text" id="Sirius" name="grz[Sirius]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Sirius eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
           </div>
            <div class="z-formrow">
               <label for="ExSirius">{gt text="Grup general- <b>Ex-usuaris de Sirius</b>"}</label>
               <select type="text" id="ExSirius" name="grz[ExSirius]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.ExSirius eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
           </div>
           <br>
            <div class="z-formrow">
               <label for="Personals">{gt text="Grup general- <b>Usuaris personals</b>"}</label>
               <select type="text" id="Personals" name="grz[Personals]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Personals eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
           </div>
            <div class="z-formrow">
               <label for="Generics">{gt text="Grup general- <b>Usuaris genèrics</b>"}</label>
               <select type="text" id="Generics" name="grz[Generics]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Generics eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <br>
            <div class="z-formrow">
               <label for="Gestors">{gt text="Grup de <b>gestors</b> de Sirius"}</label>
               <select type="text" id="Gestors" name="grz[Gestors]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Gestors eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <br>
            <div class="z-formrow">
               <label for="Gestform">{gt text="Grup dels Gestors de formació <b>(Gestform)</b>"}</label>
               <select type="text" id="Gestform" name="grz[Gestform]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Gestform eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
           </div>
            <div class="z-formrow">
               <label for="LectorsCat">{gt text="Grup de <b>lectors</b> del catàleg"}</label>
               <select type="text" id="LectorsCat" name="grz[LectorsCat]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.LectorsCat eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
           </div>
            <div class="z-formrow">
               <label for="EditorsCat">{gt text="Grup d'<b>editors</b> del catàleg"}</label>
               <select type="text" id="EditorsCat" name="grz[EditorsCat]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.EditorsCat eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <br>
            <div class="z-formrow">
               <label for="Uni">{gt text="Grup de les <b>Unitats (UNI)</b> dels Serveis Centrals"}</label>
               <select type="text" id="UNI" name="grz[UNI]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Uni eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <div class="z-formrow">
               <label for="St">{gt text="Grup de les <b>Seccions Territorials (ST)</b>"}</label>
               <select type="text" id="ST" name="grz[ST]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.St eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <div class="z-formrow">
               <label for="Se">{gt text="Grup dels <b>Serveis educatius (SE)</b>"}</label>
               <select type="text" id="SE" name="grz[SE]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Se eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <br>
            <div class="z-formrow">
               <label for="Odissea">{gt text="Grup dels administradors d'<b>Odissea</b>"}</label>
               <select type="text" id="Odissea" name="grz[Odissea]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Odissea eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <div class="z-formrow">
               <label for="Cert">{gt text="Grup de l'aplicatiu de <b>Certificació</b>"}</label>
               <select type="text" id="Cert" name="grz[Cert]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.Cert eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
               <div class="z-formrow">
               <label for="gA">{gt text="Grup extra <b>gA</b>"}</label>
               <select type="text" id="gA" name="grz[gA]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.gA eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
            <div class="z-formrow">
               <label for="gB">{gt text="Grup extra <b>gB</b>"}</label>
               <select type="text" id="gB" name="grz[gB]"/>
               <option value=''>Tria un grup</option>
               {foreach from=$allgroupsZikula item='group'}
                   <option value="{$group.gid}" {if $grupsZikula.gB eq $group.gid}selected{/if}>{$group.name}</option>
               {/foreach}
               </select>
            </div>
        </fieldset>
    </form>
               <div id="botons" class="z-buttonrow z-buttons z-center">
    <button id='btn_save' class="z-bt-save" type="button" onclick="javascript:desa();" title="{gt text="Desa"}">{gt text="Desa"}</button>
    <button id="btn_cancel" class="z-bt-cancel"  type="button"  onclick="javascript:cancel();" title="{gt text="Cancel·la"}">{gt text="Cancel·la"}</button>
</div>
    </div>
<script>
function cancel(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    window.location = "index.php?module=Cataleg&type=admin&func=modulesettings";
}
function desa(){
    // Evitar el submit múltiple. Desactivar botó
    if (document.getElementById("btn_save")) document.getElementById("btn_save").disabled = true;
    if (document.getElementById("btn_cancel")) document.getElementById("btn_cancel").disabled = true;
    document.grupsZikula.action="index.php?module=Cataleg&type=admin&func=setgrupsZikula";
    document.grupsZikula.submit();
}
</script>