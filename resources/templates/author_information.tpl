{if !isset($reviews)}
{literal}
<script type="text/javascript">
        getAuthorInformation();
</script>
{/literal}

<img src="{$uri}/resources/images/spinner.gif" id="content_spinner" style="display: none; clear: left;"></img>
<div id="author_information_list" style="clear:left; padding-top: 20px;"></div>
{else}
    <h3>Author Information, {$month} {$year}</h3>
		<h3>Book Reviews: {$journal}</h3>
		<br><br>
    {foreach from=$reviews item=review}
        <b><i>{$review.num}</i></b><br>
        <b>{$review.title}</b><br>
				{if $review.FullName}{$review.FullName}{else}
					{if $review.first_name || $review.last_name}
					{$review.first_name} {$review.last_name}
					{else}
					--NAME--
					{/if}
				{/if}<br>
				{if $review.Company}{$review.Company}<br>{/if}
				{if $review.Department}{$review.Department}<br>{/if}
				{if $review.Address1}{$review.Address1}{else}--ADDRESS--{/if}<br>
				{if $review.Address2}{$review.Address2}<br>{/if}
				{if $review.City}{$review.City}{else}-CITY-{/if}{if $review.State}, {$review.State}{else}{if !$review.Country}-STATE-{/if}{/if}{if $review.ZIPCode} {$review.ZIPCode}{else}-ZIP-{/if}<br>
				{if $review.Country}{$review.Country}<br>{/if}
				{if $review.voice}{$review.voice}{else}--PHONE--{/if}<br>
				{if $review.email}{$review.email}{else}--EMAIL--{/if}<br><br>
      {foreachelse}
          <tr><td>No Reviews</td></tr>
      {/foreach}
   </table>

{/if}
