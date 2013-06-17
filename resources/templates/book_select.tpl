<select id="{$id}" class="{$class}">
    {foreach from=$books item=book}
        <option value={$book.book_id}>{$book.title}
    {/foreach}
</select>
