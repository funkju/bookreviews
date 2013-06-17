{if $books_to_assign}
{if $books_to_assign|@count eq 1}
    <h3>There is 1 book needing a review assignment.</h3>
{else}
    <h3>There are {$books_to_assign|@count} books needing review assignments.</h3>
{/if}

<table>
    <tr>
        <th>Title</th>
        <th>Journal</th>
        <th>Type</th>
    </tr>
{/if}
{foreach from=$books_to_assign item=bta}
    <tr class="assign_reviews {cycle values="even,odd"}">
        <input type="hidden" id="book_review__{$bta.book_id}__book_review_id" value="{$bta.book_review_id}">
        <td class="title"><a href="{$uri}/books/edit/{$bta.book_id}" title="Click to Edit">{$bta.title}</a></td>
        <td> 
            {assign var="class" value="assign_book_review"}
            {assign var="id" value=book_review__`$bta.book_id`__journal_id}
            {assign var="selected" value=$bta.journal_id}
            {include file="journal_select.tpl"}
        </td>
        <td>
            {assign var="id" value=book_review__`$bta.book_id`__review_type_id}
            {assign var="selected" value=$bta.review_type_id}
            {include file="review_type_select.tpl"}
        </td>
        <!--
        <td>
            <div class="button2 assign_book_review" id="load_book_button__{$bta.book_id}" onclick="showStatus('Loading...',false,{literal}function(){window.location=uri+'/books/edit/{/literal}{$bta.book_id}';{literal}});">Load Review</div>
        </td>{/literal}--!>
    </tr>
{foreachelse}
    <h2>All Books have Review's Assigned</h2>
{/foreach}
{if $books_to_assign}
</table>

<script type="text/javascript">
    {literal}
    $("select.assign_book_review").bind("change",function(){
        var id_parts = this.id.split("__");
        var book_id = id_parts[1];
        var book_review_id = $("#book_review__"+book_id+"__book_review_id").val();

        if(id_parts[2] == "journal_id"){
            var journal_id = $(this).val();
            assignBookReview(book_id, book_review_id, journal_id, null);
        } else {
            var review_type_id = $(this).val();
            assignBookReview(book_id, book_review_id, null, review_type_id);
        }
    });

    $(document).scroll(function(ev){
        $.cookie('scroll',$(document).scrollTop());
    });

    {/literal}

    {if $rem_scroll}
        $(document).scrollTop($.cookie('scroll'));
    {/if}
</script>



{/if}
