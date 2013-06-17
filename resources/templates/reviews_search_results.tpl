<span style="margin-right: 10px;"><b>{$total}</b> results for "{$query}" in {$search}</span>
<span style=""><b>(Showing {if $begin eq 0 && $end ne 0}1{else}{$begin}{/if}-{$end})</b></span>
<span style="float: right;">Order By: 
    <a href="Javascript:;" onclick="searchReviews(0,'book_title');" style="{if $order eq "book_title"}font-weight: bold; text-decoration: underline;{else}font-weight: normal;{/if}">Title</a>&nbsp;&nbsp;
    <a href="Javascript:;" onclick="searchReviews(0,'ae_name');" style="{if $order eq "ae_name"}font-weight: bold; text-decoration: underline;{else}font-weight: normal;{/if}">Assoc Editor</a>&nbsp;&nbsp;
    <a href="Javascript:;" onclick="searchReviews(0,'rev_name');" style="{if $order eq "rev_name"}font-weight: bold; text-decoration: underline;{else}font-weight: normal;{/if}">Reviewer</a>&nbsp;&nbsp;
    <a href="Javascript:;" onclick="searchReviews(0,'date_promised');" style="{if $order eq "date_promised"}font-weight: bold; text-decoration: underline;{else}font-weight: normal;{/if}">Promised</a>&nbsp;&nbsp;
    <a href="Javascript:;" onclick="searchReviews(0,'date_received');" style="{if $order eq "date_received"}font-weight: bold; text-decoration: underline;{else}font-weight: normal;{/if}">Received</a>
</span><br>
{if $begin > 0}<a href="Javascript:;" onclick="searchReviews({math equation="x-y" x=$begin y=$limit});">&lt;&lt;prev</a>{/if}
{if $end < $total}<a href="Javascript:;" onclick="searchReviews({$end});" style="float: right;">next&gt;&gt;</a>{/if}<br>
<div id="reviews_list">
    <ul>
    {foreach from=$reviews item=review}
        <li id="{$review.book_review_id}">
            <span style="font-weight: bold;">
                {$review.book_title}
            </span><br>
            <div style="margin-left: 10px;">
                <i>{$review.journal} - {$review.review_type}</i><br>
                {if $review.ae_name}
                AE: <b>{$review.ae_name}</b> &nbsp;&nbsp;&nbsp;
                {/if}
                {if $review.rev_name}
                Reviewer: <b>{$review.rev_name}</b>
                {/if}
                {if $review.ae_name || $review.rev_name}<br>{/if}
                {if $review.date_promised}
                Review <b>promised</b> on <b>{$review.date_promised}</b><br>
                {/if}
                {if $review.date_received}
                Review <b>received</b> on <b>{$review.date_received}</b><br>
                {/if}   
            </div>
        </li>
    {/foreach}
    </ul>
</div>
