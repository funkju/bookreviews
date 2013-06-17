{foreach from=$people item=person}
{assign var="addr" value=$person.person.address}
<div style="width: 758px">
<div class="mailing_address" style="float: left; min-height: 50px;">
    <h3><b>{if $addr.FullName ne ""}{$addr.FullName}{else}{$person.person.first_name} {$person.person.last_name}{/if}</b></h3>
    {if $addr.Department}
    <span style="display: inline-block; width: 300px; font-style: italic;">{$addr.Department}</span><br>
    {/if}
    {if $addr.Company}
    <span style="display: inline-block; width: 300px; font-style: italic;">{$addr.Company}</span><br>
    {/if}
    <span style="display: inline-block; width: 300px; font-style: italic;">{$addr.Address1}</span><br>
    <span style="display: inline-block; width: 300px; font-style: italic;">{$addr.Address2}</span><br>
    <span style="display: inline-block; width: 300px; font-style: italic;">{$addr.City}{if $addr.City},{/if} {$addr.State} {$addr.ZIPCode}</span>
</div>
<div id="mark_all_sent_{$person.person.person_id}" class="mark_all_sent button" style="float: right; font-size: 12px; margin: 15px; padding: 0 4px;">
    Mark All Sent
</div>
</div>
<div style="clear: left;"></div>
<ul id="mailing_for_{$person.person.person_id}" style="list-style: none; background-color: #DDDDE6; padding-top: 5px; padding-bottom: 5px;">
{foreach from=$person.books item="book"}
    <li style="margin-left: 10px; margin-bottom: 10px;">{if $book.book.book_marketing_info.thumbnail_url}
            <img style="float:left; margin-right: 10px; width: 48px;" src='{$book.book.book_marketing_info.thumbnail_url}'></img>
        {else}
            <img style="float:left; margin-left: 10px; width: 48px;" src='{$uri}/resources/images/book_48.png'></img>
        {/if}
        <div style="float:left; width: 580px; padding-right: 10px; height: 100%; ">
        <a href="{$uri}/reviews/edit/{$book.review.book_review_id}">{$book.book.title}</a><br>
        {$journals[$book.review.journal_id]}  {$review_types[$book.review.review_type_id]}
        </div>
        <div id="mark_sent_{$book.review.book_review_id}" class="mark_sent button2" style="float: left; padding:0 4px; font-size: 12px">Mark Sent</div>
        <div style="clear:left;"></div>
    </li>
{/foreach}
</ul>
<br><br>



{foreachelse}
    <br>
    <h3>No Books to Mail!</h3>

{/foreach}


<script type="text/javascript">
    {literal}
    $(".mark_all_sent").click(function(){markAllSent(this);});
    $(".mark_sent").click(function(){markSent(this);});
    {/literal}
</script>

