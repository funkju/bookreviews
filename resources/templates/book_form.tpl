{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="not_admin_or_editor" value=0}
{else}
    {assign var="not_admin_or_editor" value=1}
{/if}

<div id="book_form">
    <input type="hidden" id="book__book_id" value="{$book.book_id}">
    <form class='well form-horizontal'>
    <h2 class='book_title'>{if $book.title}{$book.title}{else}Book Information{/if}</h2>
    <br />
    <br />
    {if $book_marketing_info.thumbnail_url}<img class="thumbnail" src="{$book_marketing_info.thumbnail_url}"></img>{/if}
    <div class='control-group'>
        <label for="title" id="book_title" class='control-label'>Title</label>
        <div class='controls'>
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="book" id="book__title" tabindex=1 value="{$book.title}">
        {else}
            <input class="book" type="text" name="book" id="book__title" tabindex=1 value="{$book.title}" disabled>
        {/if}
        </div>
    </div>
    <div class='control-group'>
       <label for="authors" id="book_authors" class='control-label'>Authors</label>
        <div class='controls'>
          {if !$not_admin_or_editor}
            <input class="book" type="text" name="authors" id="book__authors" tabindex=4 value="{$book.authors}">
          {else}
            <input class="book" type="text" name="authors" id="book__authors" tabindex=4 value="{$book.authors}" disabled>
          {/if}
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label' for="publisher" id="book_publisher">Publisher</label>
        <div class='controls'>   
          {if !$not_admin_or_editor}
            <input class="book" type="text" name="publisher" id="book__publisher" tabindex=7 value="{$book.publisher}">
          {else}
            <input class="book" type="text" name="publisher" id="book__publisher" tabindex=7 value="{$book.publisher}" disabled>
          {/if}
        </div>
    </div>
    <div class='control-group'>
        <label  class='control-label' for="year" id="book_year">Year</label>
        <div class='controls'>   
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="year" id="book__year" tabindex=6 value="{$book.year}">
        {else}
            <input class="book" type="text" name="year" id="book__year" tabindex=6 value="{$book.year}" disabled>
        {/if}
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label' for="edition_number" id="book_edition_number">Edition</label>
        <div class='controls'>   
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="edition_number" id="book__edition_number" tabindex=4 value="{$book.edition_number}">
        {else}
            <input class="book" type="text" name="edition_number" id="book__edition_number" tabindex=4 value="{$book.edition_number}" disabled>
        {/if}
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label' for="copies" id="copies">Copies On Hand</label>
        <div class='controls'>   
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="copies" id="book__copies_on_hand" tabindex=8 value="{$book.copies_on_hand}">
        {else}
            <input class="book" type="text" name="copies" id="book__copies_on_hand" tabindex=8 value="{$book.copies_on_hand}" disabled>
        {/if}
        </div>
    </div>
    <div class='control-group'>
        <label  class='control-label' for="book_num" id="book_num">Book #</label>
        <div class='controls'>
        {if !$not_admin_or_editor}
            <input class="book" type="text" name="book_num" id="book__book_num" tabindex=8 value="{$book.book_num}" disabled>
        {else}
            <input class="book" type="text" name="book_num" id="book__book_num" tabindex=8 value="{$book.book_num}" disabled>
        {/if}
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label' for="synopsis" id="book_synopsis">Synopsis</label>
	<div class='controls'>
          {if !$not_admin_or_editor}
            <textarea name="synopsis" id="book__synopsis" tabindex=9 class="book">{$book.synopsis}</textarea>
          {else}
            <textarea name="synopsis" id="book__synopsis" tabindex=9 class="book" disabled>{$book.synopsis}</textarea>
          {/if}
        </div>
    </div>
    <div class='control-group'>
        <label class='control-label' for="notes" id="book_notes">Notes</label>
        <div class='controls'>
          {if !$not_admin_or_editor}
            <textarea name="notes" id="book__notes" tabindex=10 class="book">{$book.notes}</textarea>
          {else}
            <textarea name="notes" id="book__notes" tabindex=10 class="book" disabled>{$book.notes}</textarea>
          {/if}
        </div>
    </div>
    </form>
    <form class='well form-horizontal'>
	<h2>Marketing Info</h2>
	<br />
        <br />
            <input type="hidden" id="book__book_marketing_info_id" value="{$book.book_marketing_info_id}">
            <div class='control-group'>
                <label class='control-label' for="isbn" id="book_marketing_info_isbn">ISBN</label>
                <div class='controls'>
	          {if !$not_admin_or_editor}
                    <input class="book_marketing_info" id="book_marketing_info__isbn" name="isbn" tabindex=11 value="{$book_marketing_info.isbn}"></input>
                  {else}
                    <input class="book_marketing_info" id="book_marketing_info__isbn" name="isbn" tabindex=11 value="{$book_marketing_info.isbn}" disabled></input>
                  {/if}
                </div>
	    </div>
            <div class='control-group'>
                <label class='control-label' for="pages" id="book_marketing_info_pages">Pages</label>
		<div class='controls'>
                {if !$not_admin_or_editor}
                    <input class="book_marketing_info" id="book_marketing_info__pages" name="pages" tabindex=12  value="{$book_marketing_info.pages}"></input>
                {else}
                    <input class="book_marketing_info" id="book_marketing_info__pages" name="pages" tabindex=12 value="{$book_marketing_info.pages}" disabled></input>
                {/if}
  		</div> 
	    </div>
            <div class='control-group'>
                <label class='control-label' for="binding_type" id="book_marketing_info_binding_type">Binding</label>
		<div class='controls'>
                {if !$not_admin_or_editor}
                    <select class="book_marketing_info" id="book_marketing_info__binding_type" name="binding_type" tabindex=13>
                        <option value="">-</option>
                        <option value="H" {if $book_marketing_info.binding_type eq 'H'}selected{/if}>H</option>
                        <option value="P" {if $book_marketing_info.binding_type eq 'P'}selected{/if}>P</option>
                    </select>
                {else}
                    <input class="book_marketing_info" id="book_marketing_info__binding_type" name="binding_type" tabindex=13 value="{$book_marketing_info.binding_type}" disabled></input>
                {/if}
  		</div> 
	    </div>
            <div class='control-group'>
                <label class='control-label' for="price" id="book_marketing_info_price">Price</label>
		<div class='controls'>
                {if !$not_admin_or_editor}
                    <input class="book_marketing_info" id="book_marketing_info__price" name="price" tabindex=14 value="${$book_marketing_info.price}"></input>
                {else}
                    <input class="book_marketing_info" id="book_marketing_info__price" name="price" tabindex=14 value="${$book_marketing_info.price}" disabled></input>
                {/if}
  		</div> 
	    </div>
            <div class='control-group'>
		<div class='controls'>
                {if !$not_admin_or_editor}
                    <label for="with_cd" id="book_marketing_info_with_cd" class='checkbox'><input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_cd" name="with_cd" tabindex=15 {if $book_marketing_info.with_cd}checked{/if}>With CD</label>
                {else}
                    
                    <label for="with_cd" id="book_marketing_info_with_cd" class='checkbox'><input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_cd" name="with_cd" tabindex=15 {if $book_marketing_info.with_cd}checked{/if} disabled>With CD</label>

                {/if}
                </div>
            </div>
            <div class='control-group'>
               <div class='controls'>
                {if !$not_admin_or_editor}
                    <label for="with_online" class='checkbox' id="book_marketing_info_with_online"> <input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_online" name="with_online" tabindex=16 {if $book_marketing_info.with_cd}checked{/if}>
Online Materials</label>
                {else}
                    <label for="with_online" class='checkbox' id="book_marketing_info_with_online"><input type="checkbox" class="book_marketing_info" id="book_marketing_info__with_online" name="with_online" tabindex=16 {if $book_marketing_info.with_cd}checked{/if} disabled>
Online Materials</label>
                {/if}
                </div>
            </div>
            <div class='control-group'>
                <label class='control-label' for="notes" id="book_marketing_info_notes">Notes</label>
                <div class='controls'>
                  {if !$not_admin_or_editor}
                    <textarea name="notes" id="book_marketing_info__notes" class="book_marketing_info">{$book_marketing_info.notes}</textarea>
                  {else}
                    <textarea name="notes" id="book_marketing_info__notes" class="book_marketing_info" disabled>{$book_marketing_info.notes}</textarea>
                  {/if}
                </div>
            </div>
        </form>
        <form class='well form-horizontal'>
            <h2>"Extras" Marketing Info</h2>
	    <br />
  	    <br />
            <input type="hidden" id="book__extra_book_marketing_info_id" value="{$book.extra_book_marketing_info_id}">
            <div class="control-group">
                <label for="isbn" id="book_marketing_info_isbn" class='control-label'>ISBN</label>
                <div class='controls'>
		{if !$not_admin_or_editor}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__isbn" name="isbn" tabindex=9 value="{$extra_book_marketing_info.isbn}" ></input>
                {else}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__isbn" name="isbn" tabindex=9 value="{$extra_book_marketing_info.isbn}" disabled></input>
                {/if}
                </div>
	     </div>
             <div class='control-group'>
                <label class='control-label' for="pages" id="book_marketing_info_pages">Pages</label>
                <div class='controls'>
{if !$not_admin_or_editor}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__pages" name="pages" tabindex=10 value="{$extra_book_marketing_info.pages}"></input>
                {else}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__pages" name="pages" tabindex=10 value="{$extra_book_marketing_info.pages}" disabled></input>
                {/if}

                </div>
	     </div>
             <div class='control-group'>
                <label for="binding_type" class='control-label' id="book_marketing_info_binding_type">Binding</label>
                <div class='controls'>
{if !$not_admin_or_editor}
                    <select class="extra_book_marketing_info" id="extra_book_marketing_info__binding_type" name="binding_type" tabindex=13>
                        <option value="">-</option>
												<option value="H" {if $extra_book_marketing_info.binding_type eq 'H'}selected{/if}>H</option>
                        <option value="P" {if $extra_book_marketing_info.binding_type eq 'P'}selected{/if}>P</option>
                    </select>
                {else}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__binding_type" name="binding_type" tabindex=11 value="{$extra_book_marketing_info.binding_type}" disabled></input>
                {/if}

                </div>
	     </div>
             <div class='control-group'>
                <label for="binding_type" class='control-label' id="book_marketing_info_price">Price</label>
                <div class='controls'>
{if !$not_admin_or_editor}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__price" name="price" tabindex=11 value="${$extra_book_marketing_info.price}"></input>
                {else}
                    <input class="extra_book_marketing_info" id="extra_book_marketing_info__price" name="price" tabindex=11 value="${$extra_book_marketing_info.price}" disabled></input>
                {/if}

                </div>
	    </div>
            <div class='control-group'>
		<div class='controls'>
		{if !$not_admin_or_editor}
                    <label class='checkbox' for="with_cd" id="book_marketing_info_with_cd"><input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_cd" name="with_cd" tabindex=12 {if $extra_book_marketing_info.with_cd}checked{/if}>
                    With CD</label>
                {else}
                   <label class='checkbox' for="with_cd" id="book_marketing_info_with_cd"><input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_cd" name="with_cd" tabindex=12 {if $extra_book_marketing_info.with_cd}checked{/if} disabled> With CD</label>
                {/if}
		</div>
            </div>
	    <div class='control-group'>
		<div class='controls'>
{if !$not_admin_or_editor}
                    <label class='checkbox' for="with_online" id="book_marketing_info_with_online"><input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_online" name="with_online" tabindex=13  {if $extra_book_marketing_info.with_online}checked{/if}> Online Materials</label>
                {else}
                    <label class='checkbox' for="with_online" id="book_marketing_info_with_online"><input type="checkbox" class="extra_book_marketing_info" id="extra_book_marketing_info__with_online" name="with_online" tabindex=13 {if $extra_book_marketing_info.with_online}checked{/if} disabled> Online Materials</label>
                {/if}
		</div>
	    </div>
            <div class='control-group'> 
                <label class='control-label' for="notes" id="book_marketing_info_notes">Notes</label>
                <div class='controls'>
                {if !$not_admin_or_editor}
                    <textarea class="extra_book_marketing_info" name="notes" id="extra_book_marketing_info__notes">{$extra_marketing_info.notes}</textarea>
                {else}
                    <textarea class="extra_book_marketing_info" name="notes" id="extra_book_marketing_info__notes" disabled>{$extra_marketing_info.notes}</textarea>
                {/if}
		</div>
            </div>
    </form>
    <form class='well form-horizontal'>
        <h2>Review Details</h2>
	<br />
	<br />
        <input type="hidden" id="book_review__book_review_id" value="{$book_review.book_review_id}">
        {assign var="id" value="book_review__journal_id"}
        {assign var="class" value="book_review"}
        {assign var="selected" value=$book_review.journal_id}
        {assign var="disabled" value=$not_admin_or_editor}
        <div class='control-group'>
	    <label class='control-label' id="book_review__journal_id" style="display: inline-block; width: 118px;">Journal</label>
            <div class='controls'>
		{include file="journal_select.tpl"}
	    </div>
        </div>
        {assign var="id" value="book_review__review_type_id"}
        {assign var="class" value="book_review"}
        {assign var="selected" value=$book_review.review_type_id}
        {assign var="disabled" value=$not_admin_or_editor}
        <div class='control-group'>
            <label class='control-label' id="book_review__review_type_id">Review Type</label>
	    <div class='controls'>
        	{include file="review_type_select.tpl"}
	    </div>
        </div>
        {if $book_review}
	<div class='control-group'>
		<label class='control-label'></label>
		<div class='controls'>
		        <div id="load_review" class="btn">Load Review</div>
		</div>
	</div>
        {/if}
    </form>
    <div id="book_form_buttons">
        {if !$not_admin_or_editor}
           <div id="book_discard_button" class="btn btn-large">Discard Changes</div>&nbsp;&nbsp;
           <div id="book_save_button" class="btn btn-primary btn-large">Save</div>
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
