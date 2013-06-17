<h3><b>My Reviews ({$total})</b></h3><br>
<div id="reviews_list">
    <ul>
    {foreach from=$reviews item=review}
        <li id="{$review.book_review_id}" onclick="window.location = '{$uri}/reviews/edit/{$review.book_review_id}';">
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
