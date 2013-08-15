{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="n_a_or_e" value=0}
{else}
    {assign var="n_a_or_e" value=1}
{/if}

<div id="reviews_actions" style="float: right">
    <ul>
    {if !$n_a_or_e}
        <li id="review_delete">Delete Review</li>
    {/if}
    </ul>
</div>
<div id="reviews_form_content" style="clear: left;">
    <input type="hidden" id="book_review__book_review_id" value={$book_review.book_review_id}
    <div id="book_section">
        <h4><label id="book_review_book_id" for="book_id">Book:</label><img style="display: none;" id="title_loading_spinner" src="{$uri}/resources/images/spinner.gif"></h4>
        <div id="book_form">
            <input type="hidden" id="book_review__book_or_material" class="reviews" value="{$book_review.book_or_material}">
            <input type="hidden" id="book_review__book_id" class="reviews" value="{$book_review.book_id}">

            {if $book_review.book_or_material}
                <input id="book_review__title" {if !!$n_a_or_e}disabled{/if} value="{$book_review.book.title}" class="reviews">
    			<br>
                <i>Note: This is web-only content.</i>
                <br> 
                <label id="book_review_authors">Author:</label>
                <span id="book__authors" class="book">{$book_review.book.author_name}&nbsp;</span>
                <br>
                <label id="book_review_edition_number">URL:</label>
                <span id="book__material_url" class="book">{$book_review.book.material_url}&nbsp;</span>
                <br>
                <label id="book_review_year">Access Instructions:</label>
                <span id="book__access_inst" class="book">{$book_review.book.access_inst}&nbsp;</span>
                <br>
                <label id="book_review_year">Notes:</label>
                <span id="book__access_inst" class="book">{$book_review.book.notes}&nbsp;</span>
            {else}
                <input id="book_review__title" {if !!$n_a_or_e}disabled{/if} value="{$book_review.book.title}" class="reviews">
                <br>
                <label id="book_review_book_num">Book #:</label>
                <span id="book__num" class="book">{$book_review.book.book_num}&nbsp;</span>
                <br>
                <label id="book_review_authors">Authors:</label>
                <span id="book__authors" class="book">{$book_review.book.authors}&nbsp;</span>
                <br>
                <label id="book_review_edition_number">Edition Number:</label>
                <span id="book__edition_number" class="book">{$book_review.book.edition_number}&nbsp;</span>
                <br>
                <label id="book_review_year">Year:</label>
                <span id="book__year" class="book">{$book_review.book.year}&nbsp;</span>
                <br>
                <div class="button{if $book_review.book_id}2{else}3{/if}" id="edit_book_button">Details</div>

            {/if}
        </div>
    </div>
    <div id="assoc_editor_section">
        <h4><label id="book_review_assoc_editor_id" for="person_id">Associate Editor:</label><img style="display: none;" id="assoc_editor_loading_spinner" src="{$uri}/resources/images/spinner.gif"></h4>
        <div id="assoc_editor_form">
            <input type="hidden" id="book_review__assoc_editor_id" class="reviews" value="{$book_review.assoc_editor_id}">
            <input type="text" {if !!$n_a_or_e}disabled{/if} id="book_review__assoc_editor" value="{if $book_review.assoc_editor}{$book_review.assoc_editor.last_name}, {$book_review.assoc_editor.first_name}{else}----{/if}">
            <br>
            <label id="person_institution">Institution:</label>
            <span id="assoc_ed__institution" class="institution">{$book_review.assoc_editor.institution}&nbsp;</span>
            <br>
            <label id="assoc_ed_phone">Phone:</label>
            <span id="assoc_ed__phone" class="phone">{$book_review.assoc_editor.address.voice}&nbsp;</span>
            <br>
            <label id="person_email">E-Mail:</label>
            <span id="assoc_ed__email" class="email">{$book_review.assoc_editor.address.email}&nbsp;</span>
            <br>
            <div class="button{if $book_review.assoc_editor_id}2{else}3{/if}" id="view_ae_details_button">Details</div>
        </div>
    </div>
    <div id="reviewer_section">
        <h4><label id="book_review_reviewer_id" for="person_id">Reviewer:</label><img style="display: none;" id="reviewer_loading_spinner" src="{$uri}/resources/images/spinner.gif"></img></h4>
        <div id="reviewer_form">
            <input type="hidden" id="book_review__reviewer_id" class="reviews" value="{$book_review.reviewer_id}">
            <input type="text" {if !!$n_a_or_e}disabled{/if} id="book_review__reviewer" value="{if $book_review.reviewer}{$book_review.reviewer.last_name}, {$book_review.reviewer.first_name}{else}----{/if}">
            <br>
            <label id="reviewer_institution">Institution:</label>
            <span id="reviewer__institution" class="institution">{$book_review.reviewer.institution}&nbsp;</span>
            <br>
            <label id="reviewer_phone">Phone:</label>
            <span id="reviewer__phone" class="phone">{$book_review.reviewer.address.voice}&nbsp;</span>
            <br>
            <label id="reviewer_email">E-Mail:</label>
            <span id="reviewer__email" class="email">{$book_review.reviewer.address.email}&nbsp;</span>
            <br>
            <div class="button{if $book_review.reviewer}2{else}3{/if}" id="view_reviewer_details_button">Details</div>
        </div>
    </div>
    <div id="review_section">
        <h4><label id="book_review">Review:</label></h4>
        <div id="review_form">
            <div id="review_form_line_1">
            </div>
            <div id="review_form_line_2">
                {assign var="id" value="book_review__journal_id"}
                {assign var="class" value="reviews"}
                {assign var="selected" value=$book_review.journal_id}
                {assign var="disabled" value=$n_a_or_e}
                <label id="book_review_journal">Journal:</label>{include file="journal_select.tpl"}<br>
                {assign var='id' value="book_review__review_type_id"}
                {assign var="class" value="reviews"}
                {assign var="selected" value=$book_review.review_type_id}
                {assign var="disabled" value=$n_a_or_e}
                <label id="book_review_review_type">Review Type:</label>{include file="review_type_select.tpl"}<br>
                {assign var='id' value="book_review__issue_month"}
                {assign var='selected' value=$book_review.issue_month}
                {assign var="disabled" value=$n_a_or_e}
                <label id="book_review_issue">Issue:</label>
                {include file="month_select.tpl"}
                {if !$n_a_or_e}
                <select id="book_review__issue_year" class="reviews" >
                    <option value="">----</option> 
                    <option {if $book_review.issue_year eq 2000}selected{/if}>2000</option> 
                    <option {if $book_review.issue_year eq 2001}selected{/if}>2001</option> 
                    <option {if $book_review.issue_year eq 2002}selected{/if}>2002</option> 
                    <option {if $book_review.issue_year eq 2003}selected{/if}>2003</option> 
                    <option {if $book_review.issue_year eq 2004}selected{/if}>2004</option> 
                    <option {if $book_review.issue_year eq 2005}selected{/if}>2005</option> 
                    <option {if $book_review.issue_year eq 2006}selected{/if}>2006</option> 
                    <option {if $book_review.issue_year eq 2007}selected{/if}>2007</option> 
                    <option {if $book_review.issue_year eq 2008}selected{/if}>2008</option> 
                    <option {if $book_review.issue_year eq 2009}selected{/if}>2009</option> 
                    <option {if $book_review.issue_year eq 2010}selected{/if}>2010</option> 
                    <option {if $book_review.issue_year eq 2011}selected{/if}>2011</option> 
                    <option {if $book_review.issue_year eq 2012}selected{/if}>2012</option> 
                    <option {if $book_review.issue_year eq 2013}selected{/if}>2013</option> 
                    <option {if $book_review.issue_year eq 2014}selected{/if}>2014</option> 
                    <option {if $book_review.issue_year eq 2015}selected{/if}>2015</option> 
                    <option {if $book_review.issue_year eq 2016}selected{/if}>2016</option> 
                    <option {if $book_review.issue_year eq 2017}selected{/if}>2017</option> 
                    <option {if $book_review.issue_year eq 2018}selected{/if}>2018</option> 
                    <option {if $book_review.issue_year eq 2019}selected{/if}>2019</option> 
                    <option {if $book_review.issue_year eq 2020}selected{/if}>2020</option> 
                </select>
                {else}
                    <input type="text" id="book_review__issue_year" class="reviews" disabled value="{$book_review.issue_year}">
                {/if}
                <br>
                <label id="book_review_pub_order">Publish Order:</label><input id="book_review__publish_order" value="{$book_review.publish_order}" class="reviews" {if !!$n_a_or_e}disabled{/if}>
                <input id="book_review__do_not_publish" type="checkbox" {if !!$n_a_or_e}disabled{/if} {if $book_review.do_not_publish}checked{/if} class="reviews"> <label id="book_review_do_not_publish">Do Not Publish</label>
            </div>
            <div id="review_form_line_3">
                <label id="book_review_date_promised">Review Date Promised:</label> <input id="book_review__date_promised" value="{$book_review.date_promised}" class="reviews" style="margin-right: 40px" {if !!$n_a_or_e}disabled{/if}><br>
                <label id="book_review_date_recd">Date Review Recieved:</label> <input id="book_review__date_received" value="{$book_review.date_received}" class="reviews" {if !!$n_a_or_e}disabled{/if}><br>
                <label id="book_review_date_sent">Date Book Sent:</label><input id="book_review__date_sent" value="{$book_review.date_sent}" class="reviews" {if !!$n_a_or_e}disabled{/if}><br>
        </div>
        <label id="book_review_note">Notes:</label><br>
        <textarea id="book_review__notes" class="reviews" {if !!$n_a_or_e}disabled{/if}>{$book_review.notes}</textarea>
        </div>
    </div>
    <div id="review_attachment_section">
        <h4><label id="review_attachment">Attachments:</label></h4>
        <div id="review_attachments">
            <div id="attachment_form_line_1">
							<table style='table-layout: fixed; width: 100%;'>
								<tr>
									{if !$n_a_or_e}<th style='width: 20px;'></th>{/if}
									<th style='min-width: 150px;'>Filename</th>
									<th style='width: 115px;'>Date</th>
									<th style='width: 115px;'>User</th>
									<th>Note</th>
								</tr>
								{foreach from=$attachments item=a}
								<tr id='attach_{$a.book_review_attachment_id}'>
									{if !$n_a_or_e}<td><img class='delete_attach' onclick='deleteAttach({$a.book_review_attachment_id})' src='{$uri}/resources/images/delete.png'></img></td>{/if}
									<td><a href='{$a.link_href}'>{$a.filename}</a></td>
									<td>{$a.uploaded_date}</td>
									<td>{$a.first_name} {$a.last_name}</td>
									<td>{$a.note}</td>
								<tr>
								{foreachelse}
								<tr>
									<td colspan=4><i>No Uploaded Files</i></td>
								</tr>
								{/foreach}

							</table>
            </div>
            <div id="attachment_form_line_2">
						<form method='POST' enctype="multipart/form-data">
							<table>
								<tr>
								  <td>Select A File</td>
									<td>File Description</td>
								</tr>
								<tr>
									<td> <input type='file' id='review_attachment_file' name='review_attachment_file'></td>
									<td><textarea id='review_attachment_note' name='review_attachment_note'></textarea></td>
									<td><div class='button' id='upload_attachment' {literal}onclick='showStatus("Attaching File...",false,function(){$("#attachment_form_line_2 > form").submit();});'{/literal}>Attach</div></td>
								</tr>
							</table>
						</form>
						</div>
        </div>
    </div>



</div>

{if !$n_a_or_e}
<div id="review_buttons">
    <div class="button2" id="discard_review_changes">Discard Changes</div>
    <div class="button" id="save_review">Save Changes</div>
</div>
{/if}


<script type="text/javascript">


    {literal}
    $("#save_review").bind("click",function(){
        saveBookReview();
    });
    {/literal}
  
    {literal}
    $("#discard_review_changes").bind("click",function(){
    {/literal}
        window.location = "{$uri}/reviews/edit/{$book_review.book_review_id}";
    {literal}
    });

    $("#review_delete").bind("click",function(){
        delBookReview();
    });
    
    {/literal}

    {literal}
    $("#edit_book_button").bind("click",function(){
        if($("#book_review__book_id").val()){
          book_id = $("#book_review__book_id").val();
          window.location = "{/literal}{$uri}{literal}/books/edit/"+book_id;
        }
    });
    {/literal}


    {literal}
    $("#view_reviewer_details_button").bind("click",function(){
         if($(this).hasClass("button2")){
             reviewer_id = $("#book_review__reviewer_id").val();
             window.location = "{/literal}{$uri}{literal}/people/edit/"+reviewer_id;
         }
    });
    {/literal}

    {literal}
    $("#view_ae_details_button").bind("click",function(){
         if($(this).hasClass("button2")){
             ae_id = $("#book_review__assoc_editor_id").val();
             window.location = "{/literal}{$uri}{literal}/people/edit/"+ae_id;

         }
    });
    {/literal}

    
    $("#book_review__title").autocomplete("{$uri}/svc/read/",
    {literal}{
        dataType: 'json',
        formatQuery: function(t){
            $("#title_loading_spinner").css('display','');
            return [['title', 'like', '%'+t+'%']];
        },
        extraParams : {
            'cls'    : 'book',
            'order'  : 'title',
            'select' : 'title, authors, year, edition_number'
        },
        parse: function(d){
           $("#title_loading_spinner").css('display','none');


           var parsed = [];
           var rows = d.results;

           for (var i=0; i < rows.length; i++) {
  		       var row = rows[i];
               if (row) {
			       parsed[parsed.length] = {
				       data: row,
				       value: row['book_id'],
					   result:row['title']
				   };
			   }

		    }
		    return parsed;

        },
        val : 'book_id',
        result : 'title'
    });

    $("#book_review__reviewer").bind("focus",function(){
        if($(this).val() == "----"){
            $(this).val("");
        }
    });
    $("#book_review__reviewer").bind("blur",function(){
        if($(this).val() == "" || $("#book_review__reviewer_id").val() == ""){
            $(this).val("----");
        }
    });
    $("#book_review__assoc_editor").bind("focus",function(){
        if($(this).val() == "----"){
            $(this).val("");
        }
    });
    $("#book_review__assoc_editor").bind("blur",function(){
        if($(this).val() == "" || $("#book_review__assoc_editor_id").val() == ""){
            $(this).val("----");
        }
    });
    
    {/literal}
    $("#book_review__assoc_editor").autocomplete("{$uri}/svc/read",
    {literal}{
        dataType: 'json',
        formatQuery: function(t){
            $("#book_review__assoc_editor_id").val("");
            $("#assoc_ed__institution").html("");
            $("#assoc_ed__phone").html("");
            $("#assoc_ed__email").html("");

            $("#assoc_editor_loading_spinner").css('display','');
            var ts = t.replace(",","").replace(/^\s\s*/,'').replace(/\s\s*$/,'').split(" ");
            var q = [];

            for(var i = 0; i < ts.length; i++){
                q.push([['first_name','like','%'+ts[i]+'%'],
                        'OR',
                        ['last_name','like','%'+ts[i]+'%']]);
                q.push('AND');
            }
            q.pop();
            return q;
        },
        extraParams : {
            'cls'    : 'person',
            'order'  : 'last_name, first_name',
            'select' : "person_id, last_name, first_name, institution"
        },
        parse: function(d){
           $("#assoc_editor_loading_spinner").css('display','none');

           var parsed = [];
           var rows = d.results;

           for (var i=0; i < rows.length; i++) {
  		       var row = rows[i];
               if (row) {
			       parsed[parsed.length] = {
				       data: row,
				       value: row['person_id'],
					   result:row['last_name']+", "+row['first_name']
				   };
			   }

		    }
			$("#book_review__assoc_editor_id").addClass("dirty_input");
		    return parsed;
        },
        val     : 'person_id',
        result  : 'last_name'
    });
    {/literal}
    $("#book_review__reviewer").autocomplete("{$uri}/svc/read",
    {literal}{
        dataType: 'json',
        formatQuery: function(t){
            $("#book_review__reviewer_id").val("");
            $("#reviewer__institution").html("");
            $("#reviewer__phone").html("");
            $("#reviewer__email").html("");
            
            $("#reviewer_loading_spinner").css('display','');
            var ts = t.replace(",","").replace(/^\s\s*/,'').replace(/\s\s*$/,'').split(" ");
            var q = [];

            for(var i = 0; i < ts.length; i++){
                q.push([['first_name','like','%'+ts[i]+'%'],
                        'OR',
                        ['last_name','like','%'+ts[i]+'%']]);
                q.push('AND');
            }
            q.pop();
            return q;
        },
        extraParams : {
            'cls'    : 'person',
            'order'  : 'last_name, first_name',
            'select' : "person_id, last_name, first_name, institution"
        },
        parse: function(d){
           $("#reviewer_loading_spinner").css('display','none');
           
           var parsed = [];
           var rows = d.results;

           for (var i=0; i < rows.length; i++) {
  		       var row = rows[i];
               if (row) {
			       parsed[parsed.length] = {
				       data: row,
				       value: row['person_id'],
					   result:row['last_name']+", "+row['first_name']
				   };
			   }

		    }
    
            $("#book_review__reviewer_id").addClass("dirty_input");

		    return parsed;
        },
        val     : 'person_id',
        result  : 'last_name'
    });


    $("#book_review__title").bind("result",function(ev, data, value){
        $("#book_review__book_id").val(value);
        $("#book_review__book_id").addClass("dirty_input");
        $("#edit_book_button").removeClass("button3").addClass("button2");

        data.authors        = (!data.authors)        ? "---" : data.authors;
        data.edition_number = (!data.edition_number) ? "---" : data.edition_number;
        data.year           = (!data.year)           ? "---" : data.year;
        $("#book__authors").html(data.authors);
        $("#book__edition_number").html(data.edition_number);
        $("#book__year").html(data.year);
    });

    $("#book_review__reviewer").bind("result", function(ev,data,value){
        $("#view_reviewer_details_button").removeClass("button3").addClass("button2");
        $("#reviewer__institution").html(data.institution);
        $("#book_review__reviewer_id").val(data.person_id);
        $("#book_review__reviewer_id").trigger('change');
        loadReviewerContact(parseInt(data.person_id));
    });
    
    $("#book_review__assoc_editor").bind("result", function(ev,data,value){
        $("#view_ae_details_button").removeClass("button3").addClass("button2");
        $("#assoc_ed__institution").html(data.institution);
        $("#book_review__assoc_editor_id").val(data.person_id);
        $("#book_review__assoc_editor_id").trigger('change');

        loadAssocEditorContact(parseInt(data.person_id));
    });

	$("#book_review__journal_id").bind("change",function(){
		if($(this).val() == 3){
		$("#book_review__reviewer, #book_review__assoc_editor").val("---").attr('disabled','disabled');
		$("#book_review__assoc_editor_id, #book_reivew__reviewer_id").val("").addClass("dirty_input");
		$("#assoc_ed__institution, #assoc_ed__phone, #assoc_ed__email, #reviewer__institution, #reviewer__phone, #reviewer__email").html("");
		$("#view_ae_details_button, #view_reviewer_details_button").removeClass("button2").addClass("button3");
		} else {
		$("#book_review__reviewer, #book_review__assoc_editor").attr('disabled','');
		if($("#book_review__reviewer").val() != "----") 	$("#view_reviewer_details_button").removeClass("button3").addClass("button2");
		if($("#book_review__assoc_editor").val() != "----") 	$("#view_ae_details_button").removeClass("button3").addClass("button2");

		}
	});

    {/literal}

    $("#book_review__date_promised").datepicker();
    $("#book_review__date_received").datepicker();
    $("#book_review__date_sent").datepicker();
    $("#book_review__date_ct_sent").datepicker();
    $("#book_review__date_ct_recd").datepicker();


    initInputs("input.reviews, select.reviews, textarea.reviews");

		if("{$upload_error}" !== ""){literal}{{/literal}
			showStatus("{$upload_error}",15000);
		{literal}}{/literal}
</script>
