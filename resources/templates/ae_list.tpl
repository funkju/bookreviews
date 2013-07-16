{foreach from=$aes item=ae}

<b>{$ae.last_name}, {$ae.first_name}</b><br>
{if $ae.email}{$ae.email}<br>{/if}
{if $ae.department}{$ae.department}<br>{/if}
{if $ae.institution}{$ae.institution}<br>{/if}
{if $ae.Address1}{$ae.Address1}<br>{/if}
{if $ae.Address2}{$ae.Address2}<br>{/if}
{if $ae.City}{$ae.City}, {/if}
{if $ae.State}{$ae.State} {/if}
{if $ae.ZIPCode}{$ae.ZIPCode}{/if}
{if $ae.City || $ae.State || $ae.ZIPCode}<br>{/if}
{if $ae.Country}{$ae.Country}<br>{/if}
{if $ae.voice}{$ae.voice}<br>{/if}
<hr><br>

{/foreach}
