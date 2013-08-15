{if $distribution_list}
{if !$only}
  <h2>{$distribution_list.name}</h2>

<div d="distribution_assign_actions">
    <a href="{$uri}/distribution/assignBooks?dl_id=">Switch Distribution Lists</a>&nbsp;&nbsp;&nbsp;
    <b>Assign By:</b>
    <a href="{$uri}/distribution/assignBooks/?assign_by=AE&dl_id={$distribution_list.distribution_list_id}" {if $assign_by eq "AE"}style="font-weight: bold"{/if}>AE</a>
    <a href="{$uri}/distribution/assignBooks/?assign_by=BOOK&dl_id={$distribution_list.distribution_list_id}" {if $assign_by eq "BOOK"}style="font-weight: bold"{/if}>Book</a>
</div>
<div id="distribution_assign">
{/if}
{if $assign_by eq "AE"}
  {foreach from=$aes item="ae"}
            <h3>{$ae.first_name} {$ae.last_name} <a href="Javascript:;" onClick="showHistory({$ae.person_id});" style="font-size: 12px; float: right;">Show History</a></h3>
            <div id="dist_assign_{$ae.person_id}" style="min-height:50px;">
                <table>
    {foreach from=$ae.prefs item="p"}
                    <tr class="{cycle values='even,odd'}">
                        <td class="assign_rank">{if $p.rank != 0}{$p.rank}.{/if}</td>
                        <td class="assign_title"><b>{$p.book.title}</b><br><i style='margin-left: 10px;'>By:</i> {if $p.book.book_or_material}{$p.book.author_name}{else}{$p.book.authors}{/if}</td>
                        <td class="assign_button">
                        {if $p.book_review.date_received}
                            <div class="button3" style="width: 92px;">Review Recieved</div>
                        {else}
                          {if $p.assigned}
                            <div class="button" onClick="assignBook(0,{$p.distribution_list_preference_id})">Unassign</div>
                          {else}
                            {if $p.other_assigned eq 0}
                             <div class="button2" onClick="assignBook(1,{$p.distribution_list_preference_id})">Assign</div>
                            {else}
                             <div class="button3" >Assigned</div>
                            {/if}
                          {/if}
                        {/if}
                        </td>
                    </tr>
    {foreachelse}
            <h4 style="padding-left: 10px;">No Preferences Specified!</h4>
    {/foreach}
                </table>
                <a style="font-size:12px; margin-left: 21px;" href="Javascript:;" onclick="toggleAllAEs(this,{$ae.person_id},'books');">Show All Books</a>
                <table style="display: none; clear: right;" id="all_books_{$ae.person_id}">
    {assign var="any_more" value=0}
    {foreach from=$books item="book"}
        {assign var="has_pref" value=0}
        {assign var="other_assigned" value=$book.prefs[0].other_assigned}
        {foreach from=$ae.prefs item="p"}
            {if $p.book.book_id eq $book.book_id}{assign var="has_pref" value=1}{/if}
        {/foreach}
        {if !$has_pref}
        {assign var="any_more" value=1}
                    <tr class="{cycle values='even,odd'}">
                        <td class="assign_rank"></td>
                        <td class="assign_title"><b>{$book.title}</b><br><i style='margin-left: 10px;'>By:</i> {if $p.book.book_or_material}{$p.book.author_name}{else}{$p.book.authors}{/if}</td>
                        <td class="assign_button">
                        {if $other_assigned eq 0}
                          <div class="button2" onClick="assignBook(1,null,{literal}{{/literal}person_id:{$ae.person_id},book_id:{$book.book_id},distribution_list_id:{$distribution_list.distribution_list_id}{literal}}{/literal})">Assign</div>
                        {else}
                          <div class="button3" >Assigned</div>
                        {/if}
                        </td>
                    </tr>

        {/if}
    {/foreach}
    {if !$any_more}
                <tr><td colspan=3>No More Books</b></td></tr>
    {/if}
                </table>
            </div>
  {/foreach}
{ else }
  {foreach from=$books item="book"}
  {if !$only || $only eq $book.book_id}
         {if !$only}<div id="dist_assign_{$book.book_id}" style="min-height: 50px">{/if}
            <h3><table><tr><td><img style="max-height: 50px; max-width: 70px" src="{$book.book_marketing_info.thumbnail_url}"></td><td style="padding-left: 10px; vertical-align: bottom;font-size: 14px"><b>{$book.title}</b><br><i style='margin-left: 10px;'>By:</i> {if $p.book.book_or_material}{$p.book.author_name}{else}{$p.book.authors}{/if}</span></td></tr></table></h3>
                <table>
{if !$book.book_review.date_received}
    {foreach from=$book.prefs item="p"}
                    <tr class="{cycle values='even,odd'}">
                        <td class="assign_rank">{if $p.rank != 0}{$p.rank}.{/if}</td>
                        <td class="assign_title">{$p.person.first_name} {$p.person.last_name}</td>
                        <td class="assign_button" style="padding-right: 10px; width: 66px; font-size: 11px;">
                            <a href="Javascript:;" onclick="showHistory({$p.person_id});">Show History</a>
                        </td>
                        <td class="assign_button">
                        {if $p.assigned}
                          <div class="button" onClick="assignBook(0,{$p.distribution_list_preference_id})">Unassign</div>
                        {else}
                          {if $p.other_assigned eq 0}
                          <div class="button2" onClick="assignBook(1,{$p.distribution_list_preference_id})">Assign</div>
                          {else}
                          <div class="button3" >Assigned</div>
                          {/if}
                        {/if}
                        </td>
                    </tr>
    {foreachelse}
            <h4 style="padding-left: 30px;">No Preferences Specified!</h4>
    {/foreach}
                </table>

                <a style="font-size:12px; margin-left: 21px; float: right;" href="Javascript:;" onclick="toggleAllAEs(this,{$book.book_id},'aes');">Show All AEs</a>
                <table style="display: none; clear: right;" id="all_aes_{$book.book_id}">
    {assign var="any_more" value=0}
    {foreach from=$all_aes item="ae"}
        {assign var="has_pref" value=0}
        {assign var="other_assigned" value=$book.prefs[0].other_assigned}
        {foreach from=$book.prefs item="p"}
            {if $p.person.person_id eq $ae.person_id}{assign var="has_pref" value=1}{/if}
        {/foreach}
        {if !$has_pref}
        {assign var="any_more" value=1}
            <tr class="{cycle values='even,odd'}">
                <td class="assign_rank"></td>
                <td class="assign_title">{$ae.first_name} {$ae.last_name}</td>
                <td class="assign_button" style="padding-right: 10px; width: 66px; font-size: 11px;">
                    <a href="Javascript:;" onclick="showHistory({$ae.person_id});">Show History</a>
                </td>
                <td class="assign_button">
                 {if $other_assigned eq 0}
                  <div class="button2" onClick="assignBook(1,null,{literal}{{/literal}person_id:{$ae.person_id},book_id:{$book.book_id},distribution_list_id:{$distribution_list.distribution_list_id}{literal}}{/literal})">Assign</div>
                 {else}
                 <div class="button3" >Assigned</div>
                 {/if}
                </td>
            </tr>
        {/if}
    {/foreach}
    {if !$any_more}
         <tr><td colspan=3>No More AEs</b></td></tr>
         </table>
    {/if}
                </table>

{else}
            <h4 style="padding-left: 30px;">Review Recieved</h4>
{/if}
 {/if}
 {if !$only}</div>{/if}
  {/foreach}
{/if}
{else}
Order By: <a id="sort_by_active" href="Javascript:;" onclick="sortDL('active')">Active</a> &nbsp; 
          <a id="sort_by_name" href="Javascript:;" onclick="sortDL('name')" style='font-weight: bold'>Name</a> &nbsp; 
          <a id="sort_by_create" href="Javascript:;" onclick="sortDL('created')">Created</a> &nbsp; 
          <a id="sort_by_expires" href="Javascript:;" onclick="sortDL('expires')">Expires</a>
<br><br>

<table>
    <tbody id="dist_list_holder">
{foreach from=$distribution_lists item=dl}
        <tr id="dist_list_{$dl.distribution_list_id}" style="padding-bottom: 10px;" class="dist_list_row">
            <td style="font-size: 25px; padding-right:15px; width: 500px;">
                <input type="hidden" class="created" value="{$dl.created_raw}"></input>
                <input type="hidden" class="expires" value="{$dl.expires_raw}"></input>
                <span style="{if $dl.active}font-weight: bold;{/if}">{$dl.name}</span><br>
                <div style="font-size: 14px; padding-left: 10px; padding-bottom: 10px;">
                <i>Created:</i> {$dl.created}&nbsp;&nbsp;&nbsp;
                <i>Expires:</i> {if !$dl.expires}Never{else}{$dl.expires}{/if}
                </div>
            </td>
            <td style="vertical-align: top;">
                <a id="assign_{$dl.distribution_list_id}" class="assign_dl" style:" width: 100px; display:block-inline;" href="Javascript:;" onclick="window.location = '{$uri}/distribution/assignBooks?dl_id={$dl.distribution_list_id}'">Assign Books</a>
            </td>
         </tr>
{/foreach}
    </tbody>
</table>
    

{/if}
</div>
