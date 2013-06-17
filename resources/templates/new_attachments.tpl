<center><h3>{$num_new_attachments} New Attachment{if $num_new_attachments ne 1}s{/if}</h3></center>
<br>
<ul style='margin-left: 25%'>
{foreach from=$new_attachments item=a}
	<li><a href='{$uri}/resources/attachments/{$a.book_review_id}/{$a.uploaded_date}/{$a.filename}'>{$a.filename}</a> uploaded on {$a.uploaded_date|date_format} by {$a.person.first_name} {$a.person.last_name}. <a href='{$uri}/reviews/edit/{$a.book_review_id}'>See Review</a><br>{if $a.note}&nbsp;&nbsp;<b>Note:  </b><i>{$a.note}</i>{/if}</li>
{/foreach}
</ul>
