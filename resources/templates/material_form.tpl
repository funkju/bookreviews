{if $material.author_name eq $material.submitter_name && $material.author_email eq $material.submitter_email}
    {assign var="author_is_submitter" value="checked"}
{else}
    {assign var="author_is_submitter" value=""}
{/if}
        <div id="material_form" {if !$material}style="display: none;"{/if}>
            <input type="hidden" id="material__material_id" value="{$material.material_id}">
            <h3>Author/Submitter Information</h3>
            <div id="material_form_line_1">
                <label for="author_name" id="material_author_name">Author's Name</label>
                <label for="author_email" id="material_author_email">Author's Email</label>
                <br>
                <input class="material" type="text" name="author_name" id="material__author_name" tabindex=3 value="{$material.author_name}">
                <input class="material" type="text" name="author_email" id="material__author_email" tabindex=4 value="{$material.author_email}">
            </div>
            <div id="material_form_line_2">
                <label for="author_is_submitter" id="material_author_is_submitter"><input type='checkbox' value="{$author_is_submitter}" id='material__author_is_submitter' {if $author_is_submitter eq "checked"}checked="checked"{/if} />Content was submitted by the author.</label>
            </div>
            <div id="material_form_line_3"  style="{if $author_is_submitter eq 'checked'}display:none;{/if}" >
                <label for="submitter_name" id="material_submitter_name">Submitter's Name</label>
                <label for="submitter_email" id="material_submitter_email">Submitter's Email</label>
                <br>
                <input class="material" type="text" name="submitter_name" id="material__submitter_name" tabindex=1 value="{$material.submitter_name}">
                <input class="material" type="text" name="submitter_email" id="material__submitter_email" tabindex=2 value="{$material.submitter_email}">
            </div>
            <br>
            <h3>Material Information</h3>
            <div id="material_form_line_4">
                <label for="material_title">Title</label>
                <br>
                <input class="material" type="text" name="material_title" id="material__title" tabindex=5 value="{$material.title}">
            </div>
            <div id="material_form_line_5">
                <label for="material_description">Description</label>
                <br>
                <textarea class="material" type="text" name="material_description" id="material__description" tabindex=6>{$material.description}</textarea>
            </div>
            <div id="material_form_line_6">
                <label for="material_content_url">Content URL</label>
                <label for="material_price">Price</label>
                <br>
                <input class="material" type="text" name="material_content_url" id="material__content_url" tabindex=5 value="{$material.material_url}">
                <input class="material" type="text" name="material_price" id="material__price" tabindex=5 value="{$material.price}">
            </div>
            <div id="material_form_line_7">
                <label for="material_access_inst">Access Instructions</label>
                <br>
                <textarea class="material" type="text" name="material_access_inst" id="material__access_inst" tabindex=6>{$material.access_inst}</textarea>
            </div>
            <div id="material_form_line_8">
                <label for="material_notes">Notes</label>
                <br>
                <textarea class="material" type="text" name="material_notes" id="material__notes" tabindex=6>{$material.notes}</textarea>
            </div>
            {if $material.screen_status ne 4}
                {assign var="screen_status_display" value="display:none;"}
            {/if}
            <div id="material_form_line_9">
                <label for="material_screen_status">Screening Status</label>

                <label for="material_aes" id="material_ae_label" style="{$screen_status_display}">Associate Editor</label>
                <br>
                <select class="material" name="material_screen_status" id="material__screen_status" tabindex=7>
                    <option value=1 {if $material.screen_status eq 1}selected{/if}>Undecided</option>
                    <option value=2 {if $material.screen_status eq 2}selected{/if}>Reject</option>
                    <option value=3 {if $material.screen_status eq 3}selected{/if}>Distribute</option>
                    <option value=4 {if $material.screen_status eq 4}selected{/if}>Assign</option>
                </select>
                <select class="book_review" name="book_review_assoc_editor_id" id="book_review__assoc_editor_id" tabindex=8 style="{$screen_status_display}">
                    <option value="">--Select--</option>
                {foreach from=$aes item="ae"}
                    <option {if $ae.person_id eq $book_review.assoc_editor_id}selected{/if} value="{$ae.person_id}">{$ae.first_name} {$ae.last_name}</option>
                {/foreach}
                </select>
            </div>  
            <div id="material_form_line_10">
                <label for="book_review__journal_id">Journal</label>
                <label for="book_reivew__review_type_id">Review Type</label>
                <br>
                <input type='hidden' id='book_review__book_review_id' value="{if $book_review}{$book_review.book_review_id}{/if}">
                {assign var="class" value="book_review"}
                {assign var="id" value=book_review__journal_id}
                {if $book_review}
                    {assign var="selected" value=$book_review.journal_id}
                {/if}
                {include file="journal_select.tpl"}
                {assign var="id" value=book_review__review_type_id}
                {assign var="class" value="book_review"}
                {if $book_review}
                    {assign var="selected" value=$book_review.review_type_id}
                {/if}
                {include file="review_type_select.tpl"}
            </div>
            <div id="material_form_line_11">
                <div id="material_save_button" class="button">Save</div>
                <div id="material_discard_button" class="button2">Discard Changes</div>
            </div>
        </div>

{literal}
<script type="text/javascript">

    $("#material__author_is_submitter").click(function(){
        $("#material_form_line_3").toggle();
        if(!$("#material_form_line_3").is(":visible")){
            $("#material__submitter_name, #material__submitter_email").addClass("dirty_input");
        }
    });

    $("#material__screen_status").change(function(){
        if($(this).val() == 4) { $("#material__ae_id, #material_ae_label").show(); }
        else { $("#material__ae_id, #material_ae_label").hide(); }
    });


//  $("#material_save_button").bind('click', );
    $("#material_discard_button").bind('click', function(){
       if($("#person__person_id").val()) loadPerson($("#person__person_id").val());
       
    });

    initInputs("#material_center input, #material_center textarea, #material_center select");
</script>
{/literal}
