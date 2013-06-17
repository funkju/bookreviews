<h3>How many books have arrived from publishers?</h3>
<table style='margin-left: 20px;'>
{foreach from=$reports.num_new_books item=num key=year}
<tr><td style='width: 125px;'><b>{$year}</b></td><td>{$num}</td></tr>
{/foreach}
</table>
<br><br>

<h3>How many of the books this year have an AE assigned?</h3>
<div style='margin-left: 20px;'><b>{$reports.have_ae}</b></div>
<br><br>

<h3>How many of the books this year are still waiting for an AE assignment?</h3>
<div style='margin-left: 20px;'><b>{$reports.no_ae}</b></div>
<br><br>

<h3>How have this year's books been categorized?</h3>
{foreach from=$reports.by_journal item=year_arr key=year}
<div style='font-size: 18px; font-weight: bold;'>{$year}</div>
<table style='margin-left: 20px;'>
{foreach from=$year_arr item=num key=journal_id}
<tr><td style='width: 125px;'><b>{if $journal_id eq 1}JASA{/if}{if $journal_id eq 2}TAS{/if}{if $journal_id eq 3}Do Not Review{/if}</b></td><td>{$num}</td></tr>
{/foreach}
{if $year eq $current_year}<tr><td><b>Waiting</b></td><td>{$reports.awaiting_assign}</td></tr>{/if}
</table>
{/foreach}
<br><br>

<h3>How many books have appeared in recent and upcoming issues?</h3><br>
<div style='font-size: 18px; font-weight: bold;'>JASA</div>
<table style='margin-left: 20px;'>
  {foreach from=$reports.reviews_by_issue[1] key=year item=arr}
	  {foreach from=$arr key=mon item=val}
  		<tr><td style='width: 125px;'><b>{$months[$mon]} {$year}</b></td><td>{$val}</td></tr>
		{/foreach}
  {/foreach}
</table>
<div style='font-size: 18px; font-weight: bold;'>TAS</div>
<table style='margin-left: 20px;'>
  {foreach from=$reports.reviews_by_issue[2] key=year item=arr}
	  {foreach from=$arr key=mon item=val}
  		<tr><td style='width: 125px;'><b>{$months[$mon]} {$year}</b></td><td>{$val}</td></tr>
		{/foreach}
  {/foreach}
</table>

