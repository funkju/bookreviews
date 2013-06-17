{if !$disabled}
<select id="{$id}" class="{$class}" {if $disabled}disabled{/if}>
    <option value="">----</option>
    {foreach from=$review_types item=r}
        <option value={$r.review_type_id} {if $r.review_type_id eq $selected}selected{/if}>{$r.review_type}
    {/foreach}
</select>
{else}
    {foreach from=$review_types item=r}
        {if $r.review_type_id eq $selected}
        <input type="text" id="{$id}" class="{$class}" disabled value="{$r.review_type}">
        {/if}
    {/foreach}
{/if}
