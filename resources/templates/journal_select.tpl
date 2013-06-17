{if !$disabled}
    <select id="{$id}" class="{$class}">
        <option value="">----</option>
        {foreach from=$journals item=j}
        <option {if $j.journal_id eq $selected}selected{/if} value={$j.journal_id}>{$j.journal}
        {/foreach}
    </select>

{else}
    {foreach from=$journals item=j}
       {if $j.journal_id eq $selected}<input id="{$id}" class="{$class}" disabled type="text" value={$j.journal}>{/if}
    {/foreach}
{/if}
