<div id="IWmainContent">
    {ajaxheader modname=IWmessages filename=IWmessages.js}
    {ajaxheader modname=IWmain filename=IWmain.js}
    {include file=IWmessages_user_menu.tpl}
    <fieldset>
        <legend><strong>{gt text="Message(s) received"}</strong></legend>
        {if $messagecount eq 0}
        <div>
            {gt text="You don't have any messages"}
        </div>
        {else}
        <form method="post" enctype="application/x-www-form-urlencoded" action="index.php?module=IWmessages">
            <input type="hidden" name="rppsend" value="{$rppsend}">
            <table border="0">
                <tr>
                    <td>{gt text="Filter the messages"}</td>
                    <td>
                        <select name="filter" onchange='this.form.submit()'>
                            {section name=filter_MS loop=$filter_MS}
                            <option {if $filter eq $filter_MS[filter_MS].id}selected{/if} value="{$filter_MS[filter_MS].id}">{$filter_MS[filter_MS].name}</option>
                            {/section}
                        </select>
                    </td>
                    <td width="50"></td>
                    <td>
                        {gt text="Records per page"}
                    </td>
                    <td>
                        <select name="rpp" onchange='this.form.submit()'>
                            {section name=rpp_MS loop=$rpp_MS}
                            <option {if $rpp eq $rpp_MS[rpp_MS].id}selected{/if} value="{$rpp_MS[rpp_MS].id}">{$rpp_MS[rpp_MS].name}</option>
                            {/section}
                        </select>
                    </td>
                    <td width="50"></td>
                    <td>
                        {$pager}
                    </td>
                </tr>
            </table>
        </form>

        <form name="prvmsg" method="post">
            <input type="hidden" name="qui" value="d">
            <table border="0" cellspacing="1" cellpadding="3" width="100%">
                <thead>
                    <tr>
                        <th align="center"><input name="allbox" onclick="CheckAll();" type="checkbox" value="{gt text="Check all"}"  id='check' name="check" /></th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>{gt text="From"}</th>
                        <th>{gt text="Subject"}</th>
                        <th>{gt text="Date"}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach name=i item=message from=$messages}
                    <tr align="left" style="background-color:{cycle values="#eee,#fff"};" id="msg{$smarty.foreach.i.iteration}">
                        <td align="center">
                                              <input type="checkbox" onclick="CheckCheckAll();" id="msg_id{$smarty.foreach.i.iteration}" name="msg_id{$smarty.foreach.i.iteration}" value="{$message.msg_id}" />
                                          </td>
                                          <td align="center" onClick="document.prvmsg.msg_id{$smarty.foreach.i.iteration}.checked=true;marcar();">
                                              <div>
                                                  {if $message.marcat eq 1}
                                                  {img src='flag.png' modname='core' set='icons/extrasmall' __alt='Mark the message with a flag'  id="flag_`$smarty.foreach.i.iteration`"}
                                                  {else}
                                                  {img src='flag.png' modname='core' set='icons/extrasmall' __alt='Mark the message with a flag'  style="display: none;" id="flag_`$smarty.foreach.i.iteration`"}
                                                  {/if}
                                                  <div id="mark_{$smarty.foreach.i.iteration}"></div>
                                          </div>
                                      </td>
                                      <td align="center">
                                          {if $message.file1 neq '' || $message.file2 neq '' || $message.file3 neq ''}
                                          {img src='file.gif' modname='IWmessages' __alt='Attached files' }
                                          {/if}
                                      </td>
                                      <td align="center">
                                          {if $message.read_msg eq 1}
                                          {if $message.replied eq 1}
                                          {img src='rewrite.gif' modname='IWmessages' __alt='Message rewritted' }
                                          {else}
                                          {img src='read.gif' modname='IWmessages' __alt='Message read' }
                                          {/if}
                                          {else}
                                          {img src='email.gif' modname='IWmessages' __alt='Message unread' }
                                          {/if}
                                      </td>
                                      <td align="center">
                                          {if $message.msg_image neq ""}
                                          <img src="modules/IWmain/images/smilies/{$message.msg_image}" alt="" />
                                          {else}
                                          &nbsp;
                                          {/if}
                                          <div id="noteinfo_{$smarty.foreach.i.iteration}"></div>
                                      </td>
                                      {assign var="posterdata" value=$message.posterdata}
                                      <td>
                                          {$users[$posterdata.uid]}
                                      </td>
                                      <td>
                                          <a href="#" onclick="javascript:display({$message.msg_id},'d',{$inici},{$rpp},{$inicisend},{$rppsend},{$filter},{$filtersend})">{$message.subject|safetext}</a>
                                      </td>
                                      <td align="left">
                                          {$message.messagetime}
                                      </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            <div style="text-align:left; margin: 5px;">
                <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
                <input type="button" name="delete" value="{gt text="Delete"}" onclick="javascript:esborrar('{gt text="Confirm the action before deleting the messages selected?"}')" />
                       <input type="button" name="marca" value="{gt text='Mark/Unmark messages'}"  onclick="javascript:marcar()" />
                <input type="hidden" name="total_messages" id="total_messages" value="{$smarty.foreach.i.iteration}" />
            </div>
        </form>
        {/if}
        {if $imgInWidth}
        <div style="float:left;">{gt text="Number of messages in inbox"}:</div><div style="float: left; margin-left: 5px;">{$messagecount}</div>
        <div style="clear:both; height:2px;">&nbsp;</div>
        <div style="background: url(modules/IWmessages/images/use.jpg) repeat-y; float:left; width: {$imgInWidth}px;">&nbsp;</div><div style="float: left; margin-left: 5px;">{$limitInBox100} %</div>
        {/if}
        {if isset($inComeOver) AND $inComeOver}
        <script>
            alert("{{gt text="You have overcame the number of messages allowed in inbox. You should delete some messages."}}");
        </script>
        {/if}
    </fieldset>
    <fieldset>
        <legend><strong>{gt text="Message sent"}</strong></legend>
        {if $messagecountsend eq 0}
        <div>
            {gt text="You don't have any messages"}
        </div>
        {else}
        <form method="post" enctype="application/x-www-form-urlencoded" action="index.php?module=IWmessages">
            <input type="hidden" name="rpp" value="{$rpp}">
            <table border="0">
                <tr>
                    <td>
                        {gt text="Filter the messages"}
                    </td>
                    <td>
                        <select name="filtersend" onchange='this.form.submit()'>
                            {section name=filtersend_MS loop=$filtersend_MS}
                            <option {if $filtersend eq $filtersend_MS[filtersend_MS].id}selected{/if} value="{$filtersend_MS[filtersend_MS].id}">{$filtersend_MS[filtersend_MS].name}</option>
                            {/section}
                        </select>
                    </td>
                    <td width="50">&nbsp;</td>
                    <td>
                        {gt text="Records per page"}
                    </td>
                    <td>
                        <select name="rppsend" onchange='this.form.submit()'>
                            {section name=rpp_MS loop=$rpp_MS}
                            <option {if $rppsend eq $rpp_MS[rpp_MS].id}selected{/if} value="{$rpp_MS[rpp_MS].id}">{$rpp_MS[rpp_MS].name}</option>
                            {/section}
                        </select>
                    </td>
                    <td width="50">&nbsp;</td>
                    <td>
                        {$pagersend}
                    </td>
                </tr>
            </table>
        </form>

        <form name="prvmsgsend" method="post">
            <input type="hidden" name="qui" value="r">
            <table border="0" cellspacing="1" cellpadding="3" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input name="allboxsend" onclick="CheckAllsend();" type="checkbox" value="{gt text="Check all"}" id="checksend" /></th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>{gt text="To"}</th>
                        <th>{gt text="Subject"}</th>
                        <th>{gt text="Hand-over date"}</th>
                        <th>{gt text="Date of reading"}</th>
                    </tr>
                </thead>
                {foreach name=is item=messagesend from=$messagessend}
                <tr align="left" style="background-color:{cycle values="#eee,#fff"};" id="msgsend{$smarty.foreach.is.iteration}">
                    <td align="center">
                                          <input type="checkbox" onclick="CheckCheckAllsend();" name="msgsend_id{$smarty.foreach.is.iteration}" id="msgsend_id{$smarty.foreach.is.iteration}" value="{$messagesend.msg_id}" />
                                      </td>
                                      <td align="center">
                                          {if $messagesend.file1 neq '' || $messagesend.file2 neq '' || $messagesend.file3 neq ''}
                                          <img src="modules/IWmessages/images/file.gif" alt="{gt text="Attached files"}" />
                                               {/if}
                                      </td>
                                      <td align="center">
                                          {if $messagesend.read_msg eq 1}
                                          {img src='read.gif' modname='IWmessages' __alt='Message read' }
                                          {else}
                                          {img src='email.gif' modname='IWmessages' __alt='Message unread' }
                                          {/if}
                                      </td>
                                      <td align="center">
                                          {if $messagesend.msg_image neq ""}
                                          <img src="modules/IWmessages/images/smilies/{$messagesend.msg_image}" alt="" />
                                          {/if}
                                          <div id="noteinfosend_{$smarty.foreach.is.iteration}"></div>
                                  </td>
                                  {assign var="posterdata" value=$messagesend.posterdata}
                                  <td>
                                      {$users[$posterdata.uid]}
                                  </td>
                                  <td>
                                      <a href="#" onclick="javascript:displaysend({$messagesend.msg_id},'r', {$posterdata.uid},{$inici},{$rpp},{$inicisend},{$rppsend},{$filter},{$filtersend})">{$messagesend.subject|safetext}</a>
                                  </td>
                                  <td align="left">
                                      {$messagesend.messagetime}
                                  </td>
                                  <td align="left">
                                      {$messagesend.messagetimeread}
                                  </td>
                </tr>
                {/foreach}
            </table>
            <div style="text-align:left; margin: 5px;">
                <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
                <input type="button" name="delete" value="{gt text="Delete"}" onclick="javascript:esborrarsend('{gt text="Confirm the action before deleting the messages selected?"}')" />
                       <input type="hidden" name="total_messages_send" id="total_messages_send" value="{$smarty.foreach.is.iteration}" />
            </div>
        </form>
        {/if}
        {if $imgOutWidth}
        <div style="float:left;">{gt text="Number of messages in outbox"}:</div><div style="float: left; margin-left: 5px;">{$messagecountsend}</div>
        <div style="clear:both; height:2px;">&nbsp;</div>
        <div style="background: url(modules/IWmessages/images/use.jpg) repeat-y; float:left; width: {$imgOutWidth}px;">&nbsp;</div><div style="float: left; margin-left: 5px;">{$limitOutBox100} %</div>
        {/if}
        {if isset($inComeOver) AND $inComeOver}
        <script>
            alert("{{gt text="You have overcame the number of messages allowed in outbox. You should delete some messages."}}");
        </script>
        {/if}
    </fieldset>
</div>
