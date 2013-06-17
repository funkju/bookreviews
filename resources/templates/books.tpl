{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="admin_or_editor" value=1}
{else}
    {assign var="admin_or_editor" value=0}
{/if}



<div id="book_content">
    {if !isset($books_to_assign)}
    <div id="book_top">
       <input type="text" name="book_search" id="book_search" {if $query}value="{$query.query}"{/if}>
       <div id="book_search_button" class="btn btn-primary btn-large">Search</div>
    </div>
    <div id="book_buttons">
        <ul>
            {if $admin_or_editor}
                <li id="book_add"><i class='icon-plus-sign'></i>Add New Book</li>
                <li id="book_del" style="display:{if isset($books_to_assign) || !$book}none{/if};"><i class='icon-trash'></i>Delete</li>
                <li id="assign_rev"><i class='icon-random'></i>Assign Reviews</li>
            {/if}
        </ul>
    </div>
    {/if}
    <br> 
    <div id="book_center" style="{if isset($books_to_assign)}width:750px;{/if}">
        {if $book}
            {include file="book_form.tpl"}
        {elseif $books_to_assign}
            {include file="books_to_assign.tpl"}
        {/if}
       <div id="book_list" style="display: none;">
          <ul>
          </ul>
       </div>
    </div>
</div>


{literal}
<script type="text/javascript">
    $("#book_save_button").bind('click', saveBook);
    $("#book_discard_button").bind('click', function(){
       if($("#book__book_id").val()) loadBook($("#book__book_id").val());
       
    });
    {/literal}

    {if $query}
        execFilter({if $query.offset}{$query.offset}{else}undefined{/if},{if $query.order}"{$query.order}"{else}undefined{/if});
    {/if}

    {if $admin_or_editor}
      {literal}
        $("#book_add").bind('click', addBook); 
        $("#book_del").bind('click', delBook);
        $("#assign_rev").bind('click', function(){
            showStatus("Loading...",false,function(){ window.location = uri+"/books/assignReviews"; });  
        });
      {/literal}
    {/if}
    
    {literal}

//  $("#book_search").bind('keyup', filterBook);
    $("#book_search_button").bind('click', function(){search_offset = 0; filterBook()});

    initInputs("#book_center input, #book_center textarea, #book_center select");

</script>
{/literal}

