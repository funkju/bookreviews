{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="not_admin_or_editor" value=0}
{else}
    {assign var="not_admin_or_editor" value=1}
{/if}

<div id="book_form">
    <input type="hidden" id="book__book_id" value="{$book.book_id}">
    {if $book_marketing_info.thumbnail_url}<img class="thumbnail" src="{$book_marketing_info.thumbnail_url}"></img>{/if}
    <div id="book_form_line_1">
        <label for="title" id="book_title">Title</label>
        <br>
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="book" id="book__title" tabindex=1 value="{$book.title}">
        {else}
            <input class="book" type="text" name="book" id="book__title" tabindex=1 value="{$book.title}" disabled>
        {/if}
    </div>
    <div id="book_form_line_2">
        <label for="authors" id="book_authors">Authors</label>
        <label for="publisher" id="book_publisher">Publisher</label>
        <br>
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="authors" id="book__authors" tabindex=4 value="{$book.authors}">
            <input class="book" type="text" name="publisher" id="book__publisher" tabindex=7 value="{$book.publisher}">
        {else}
            <input class="book" type="text" name="authors" id="book__authors" tabindex=4 value="{$book.authors}" disabled>
            <input class="book" type="text" name="publisher" id="book__publisher" tabindex=7 value="{$book.publisher}" disabled>
        {/if}
     </div>
     <div id="book_form_line_3">
        <label for="year" id="book_year">Year</label>
        <label for="edition_number" id="book_edition_number">Edition</label>
        <label for="copies" id="copies">Copies On Hand</label>
        <label for="book_num" id="book_num">Book #</label>
        <br>
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="year" id="book__year" tabindex=6 value="{$book.year}">
            <input class="book" type="text" name="edition_number" id="book__edition_number" tabindex=4 value="{$book.edition_number}">
            <input class="book" type="text" name="copies" id="book__copies_on_hand" tabindex=8 value="{$book.copies_on_hand}">
            <input class="book" type="text" name="book_num" id="book__book_num" tabindex=8 value="{$book.book_num}" disabled>
        {else}
            <input class="book" type="text" name="year" id="book__year" tabindex=6 value="{$book.year}" disabled>
            <input class="book" type="text" name="edition_number" id="book__edition_number" tabindex=4 value="{$book.edition_number}" disabled>
            <input class="book" type="text" name="copies" id="book__copies_on_hand" tabindex=8 value="{$book.copies_on_hand}" disabled>
            <input class="book" type="text" name="book_num" id="book__book_num" tabindex=8 value="{$book.book_num}" disabled>
        {/if}
    </div>
    <div id="book_form_line_4">
        <label for="synopsis" id="book_synopsis">Synopsis</label>
        <label for="notes" id="book_notes">Notes</label>
        <br>
        {if !$not_admin_or_editor}
            <textarea name="synopsis" id="book__synopsis" tabindex=9 class="book">{$book.synopsis}</textarea>
            <textarea name="notes" id="book__notes" tabindex=10 class="book">{$book.notes}</textarea>
        {else}
            <textarea name="synopsis" id="book__synopsis" tabindex=9 class="book" disabled>{$book.synopsis}</textarea>
            <textarea name="notes" id="book__notes" tabindex=10 class="book" disabled>{$book.notes}</textarea>
        {/if}
    </div>
    <div id="book_form_line_5">
        <br>
        {if !$not_admin_or_editor}
        {else}
        {/if}
    </div>
    <div id="book_form_line_6">
        <div id="book_form_line_6_left">
            <h4>Marketing Info</h4>
            <input type="hidden" id="book__book_marketing_info_id" value="{$book.book_marketing_info_id}">
            <div id="book_form_line_7">
                <label for="isbn" id="book_marketing_info_isbn">ISBN</label>
                <label for="pages" id="book_marketing_info_pages">Pages</label>
                <label for="binding_type" id="book_marketing_info_binding_type">Binding</label>
                <label for="price" id="book_marketing_info_price">Price</label>
                <br>
                {if !$not_admin_or_editor}
                
                    <input class="book_marketing_info" id="book_marketing_info__isbn" name="isbn" tabindex=11 value="{$book_marketing_info.isbn}"></input>
                    <input class="book_marketing_info" id="book_marketing_info__pages" name="pages" tabindex=12  value="{$book_marketing_info.pages}"></input>
                    <select class="book_marketing_info" id="book_marketing_info__binding_type" name="binding_type" tabindex=13>
                        <option value="">-</option>
                        <option value="H" {if $book_marketing_info.binding_type eq 'H'}selected{/if}>H</option>
                        <option value="P" {if $book_marketing_info.binding_type eq 'P'}selected{/if}>P</option>
                    </select>
                    <input class="book_marketing_info" id="book_marketing_info__price" name="price" tabindex=14 value="${$book_marketing_info.price}"></input>
                {else}
                    <input class="book_marketing_info" id="book_marketing_info__isbn" name="isbn" tabindex=11 value="{$book_marketing_info.isbn}" disabled></input>
                    <input class="book_marketing_info" id="book_marketing_info__pages" name="pages" tabindex=12 value="{$book_marketing_info.pages}" disabled></input>
                    <input class="book_marketing_info" id="book_marketing_info__binding_type" name="binding_type" tabindex=13 value="{$book_marketing_info.binding_type}" disabled></input>
                    <input class="book_marketing_info" id="book_marketing_info__price" name="price" tabindex=14 value="${$book_marketing_info.price}" disabled></input>
                {/if}
            </div>
            <br>
            <div id="book_form_line_9">
                {if !$not_admin_or_editor}
                    <input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_cd" name="with_cd" tabindex=15 {if $book_marketing_info.with_cd}checked{/if}>
                    <label for="with_cd" id="book_marketing_info_with_cd">With CD</label>
                    <input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_online" name="with_online" tabindex=16 {if $book_marketing_info.with_cd}checked{/if}>
                    <label for="with_online" id="book_marketing_info_with_online">Online Materials</label>
                {else}
                    <input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_cd" name="with_cd" tabindex=15 {if $book_marketing_info.with_cd}checked{/if} disabled>
                    <label for="with_cd" id="book_marketing_info_with_cd">With CD</label>
                    <input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_online" name="with_online" tabindex=16 {if $book_marketing_info.with_cd}checked{/if} disabled>
                    <label for="with_online" id="book_marketing_info_with_online">Online Materials</label>

                {/if}
            </div>
            <div id="book_form_line_10">
                <label for="notes" id="book_marketing_info_notes">Notes</label>
                <br>
                {if !$not_admin_or_editor}
                    <textarea name="notes" id="book_marketing_info__notes" class="book_marketing_info">{$book_marketing_info.notes}</textarea>
                {else}
                    <textarea name="notes" id="book_marketing_info__notes" class="book_marketing_info" disabled>{$book_marketing_info.notes}</textarea>
                {/if}
            </div>
        </div>
        <div id="book_form_line_6_right">
            <h4>"Extras" Marketing Info</h4>
            <input type="hidden" id="book__extra_book_marketing_info_id" value="{$book.extra_book_marketing_info_id}">
            <div id="book_form_line_11">
                <label for="isbn" id="book_marketing_info_isbn">ISBN</label>
                <label for="pages" id="book_marketing_info_pages">Pages</label>
                <label for="binding_type" id="book_marketing_info_binding_type">Binding</label>
                <label for="binding_type" id="book_marketing_info_price">Price</label>
                <br>
                {if !$not_admin_or_editor}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__isbn" name="isbn" tabindex=9 value="{$extra_book_marketing_info.isbn}" ></input>
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__pages" name="pages" tabindex=10 value="{$extra_book_marketing_info.pages}"></input>
                    <select class="extra_book_marketing_info" id="extra_book_marketing_info__binding_type" name="binding_type" tabindex=13>
                        <option value="">-</option>
												<option value="H" {if $extra_book_marketing_info.binding_type eq 'H'}selected{/if}>H</option>
                        <option value="P" {if $extra_book_marketing_info.binding_type eq 'P'}selected{/if}>P</option>
                    </select>
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__price" name="price" tabindex=11 value="${$extra_book_marketing_info.price}"></input>
                {else}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__isbn" name="isbn" tabindex=9 value="{$extra_book_marketing_info.isbn}" disabled></input>
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__pages" name="pages" tabindex=10 value="{$extra_book_marketing_info.pages}" disabled></input>
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__binding_type" name="binding_type" tabindex=11 value="{$extra_book_marketing_info.binding_type}" disabled></input>
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__price" name="price" tabindex=11 value="${$extra_book_marketing_info.price}" disabled></input>
                {/if}
            </div>
            <br>
            <div id="book_form_line_13">
                {if !$not_admin_or_editor}
                    <input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_cd" name="with_cd" tabindex=12 {if $extra_book_marketing_info.with_cd}checked{/if}>
                    <label for="with_cd" id="book_marketing_info_with_cd">With CD</label>
                    <input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_online" name="with_online" tabindex=13  {if $extra_book_marketing_info.with_online}checked{/if}>
                    <label for="with_online" id="book_marketing_info_with_online">Online Materials</label>
                {else}
                    <input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_cd" name="with_cd" tabindex=12 {if $extra_book_marketing_info.with_cd}checked{/if} disabled>
                    <label for="with_cd" id="book_marketing_info_with_cd">With CD</label>
                    <input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_online" name="with_online" tabindex=13 {if $extra_book_marketing_info.with_online}checked{/if} disabled>
                    <label for="with_online" id="book_marketing_info_with_online">Online Materials</label>
                {/if}
            </div>
            <div id="book_form_line_14">
                <label for="notes" id="book_marketing_info_notes">Notes</label>
                <br>
                {if !$not_admin_or_editor}
                    <textarea class="extra_book_marketing_info" name="notes" id="extra_book_marketing_info__notes">{$extra_marketing_info.notes}</textarea>
                {else}
                    <textarea class="extra_book_marketing_info" name="notes" id="extra_book_marketing_info__notes" disabled>{$extra_marketing_info.notes}</textarea>
                {/if}
            </div>
        </div>
    </div>
    <div id="book_form_line_15">
        <h4>Review Details</h4>
        <input type="hidden" id="book_review__book_review_id" value="{$book_review.book_review_id}">
        {assign var="id" value="book_review__journal_id"}
        {assign var="class" value="book_review"}
        {assign var="selected" value=$book_review.journal_id}
        {assign var="disabled" value=$not_admin_or_editor}
        <label id="book_review__journal_id" style="display: inline-block; width: 118px;">Journal</label>
        <label id="book_review__review_type_id">Review Type</label><br>
        {include file="journal_select.tpl"}
        {assign var="id" value="book_review__review_type_id"}
        {assign var="class" value="book_review"}
        {assign var="selected" value=$book_review.review_type_id}
        {assign var="disabled" value=$not_admin_or_editor}
        {include file="review_type_select.tpl"}
        {if $book_review}
        <div id="load_review" class="button2">Load Review</div>
        {/if}
    </div>
    <div id="book_form_buttons">
        {if !$not_admin_or_editor}
           <div id="book_discard_button" class="button2">Discard Changes</div>
           <div id="book_save_button" class="button">Save</div>
        {/if}
    </div>
</div>
<div id="book_list">
    <ul>
    </ul>
</div>
<script type="text/javascript">
    {literal}
    $("#load_review").bind("click",function(){
       {/literal}
       {if $book_review} 
       {literal}
       showStatus("Loading...",false,function(){
           {/literal}
            window.location = "{$uri}/reviews/edit/{$book_review.book_review_id}";
            {literal}
       });
       {/literal}
       {/if}
       {literal}
    });
    {/literal} 
</script>
