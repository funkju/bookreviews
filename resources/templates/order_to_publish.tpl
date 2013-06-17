{if !isset($reviews)}
{literal}
<script type="text/javascript">
        getOrderToPublish();
</script>
{/literal}

<img src="{$uri}/resources/images/spinner.gif" id="content_spinner" style="display: none; clear: left;"></img>
<div id="order_to_publish_list" style="clear:left; padding-top: 20px;"></div>
{else}
    <h3>Order To Publish</h3>
		<h3>{$month} {$year}</h3>
		<h3>Book Reviews: {$journal}</h3>
		<br>
		<table>
    {foreach from=$reviews item=review}
		    {if $review.book_num}<b>{$review.book_num}</b><br>{/if}
				<b>{$review.title}</b><br>
				{if $review.author}{$review.author}{else}--AUTHOR--{/if}<br>
				{if $review.publisher}{$review.publisher}{else}--PUBLISHER--{/if}<br>
				{if $review.publish_date}{$review.publish_date}{else}--PUB DATE--{/if}<br>
				{if $review.isbn}{$review.isbn}{else}--ISBN--{/if}<br>
				{if $review.pages}{$review.pages} pp.{else}--PAGES--{/if}<br>
				{if $review.price}${$review.price}{else}--PRICE--{/if} ({if $review.binding}{$review.binding}{else}-{/if})<br><br>
    {foreachelse}
         <b>No Reviews</b>
    {/foreach}
		</table>
{/if}
