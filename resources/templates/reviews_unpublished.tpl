<div>
{if count($unpublished_reviews) ne 0}
{foreach from=$journals item=j}
  {if count($unpublished_reviews[$j.journal_id]) ne 0}
    <h2>{$j.journal}</h2>
    {foreach from=$review_types item=rt}
      {if count($unpublished_reviews[$j.journal_id][$rt.review_type_id]) ne 0}
      <fieldset style='margin: 10px;'>
       <legend style="margin-left: 5px; font-size: 18px;"><b>{$rt.review_type}</b></legend>
       <table style="padding-left: 20px; width: 100%">
          {foreach from=$unpublished_reviews[$j.journal_id][$rt.review_type_id] item=review}
          <tr >
            <td>
                <h4><a title="Click to Load Review" href="{$uri}/reviews/edit/{$review.book_review_id}">{$review.book.title}</a></h4>
                <div style="margin-left: 20px;">
                   {if $review.reviewer}<div style="display: inline-block; width:250px"><b>Review By:</b>  {$review.reviewer.first_name} {$review.reviewer.last_name}</div>{/if}
                   <b>Associate Editor:</b> {$review.assoc_editor.first_name} {$review.assoc_editor.last_name}<br>
                   <i>{$review.notes}</i>
                </div>
            </td>
          </tr>
          {foreachelse}
              <tr><td>No Unpublished.</td></tr>
          {/foreach}
       </table>
    </fieldset>
    {/if}
  {/foreach}
  {/if}
{/foreach}
{else}
<h2>No Unpublished Reviews</h2>
{/if}
</div>
