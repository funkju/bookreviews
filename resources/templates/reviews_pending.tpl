<div id="ae_list">
  <span id="reviews_pending_total">{$num_pending} pending reviews.</span><br>
  <div id="ae_list_holder">
    {foreach from=$pending_reviews key=ae item=item}
    <div class="reviews_pending_name" id="left_{$item[0].assoc_editor_id}" onclick="showPending({$item[0].assoc_editor_id})">
        <span class="count">({$item|@count})</span>
        <span class="name">{$ae}</span>
    </div>
    {/foreach}
  </div>
  <br>
  <b> Order By:</b><a style="font-weight: bold" id="sort_by_name" href="Javascript:;" onclick="sortPendingReviews('name');">Name</a></b> / <a onclick="sortPendingReviews('count');" href="Javascript:;" id="sort_by_count">Number</a>
</div>
<div id="reviews_list">
<span id="review_message">
    <br><br>
    <center>
    <h3>Click on a name on the left<br>to view list of reviews.</h3>
    </center>
</span>
{foreach from=$pending_reviews key=ae item=item}
  <div id="right_{$item[0].assoc_editor_id}" style="display:none;" class="pending_reviews">
  <h2>{$ae} ({$item|@count})</h2>
  {foreach from=$item item=review}
    <div>
        <h4><a title="Click to Load Review" href="{$uri}/reviews/edit/{$review.book_review_id}">{$review.book.title}</a></h4>
        <div style="margin-left: 10px;">
            <i>{$review.journal} - {$review.review_type}</i><br>
    {if $review.reviewer}
            <b>Review due from {$review.reviewer.first_name} {$review.reviewer.last_name} {if $review.date_promised} on {$review.date_promised}{/if}</b><br>
    {/if}
            <i>{$review.notes}</i>
        </div>
    </div>
  {/foreach}
  </div>
{/foreach}
</div>

<script type="text/javascript">
    {if $rem_ae}
    if($.cookie('show_ae')) showPending($.cookie('show_ae'));
    {/if}   
</script>
