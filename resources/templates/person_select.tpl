<select id="{$id}" class="{$class}" name="{$selected}">
    <option value="">--------</option>
    {foreach from=$people item=person}
        <option value="{$person.person_id}" {if $selected eq $person.person_id}selected{/if}>{$person.last_name}</option>
    {/foreach}
</select>
