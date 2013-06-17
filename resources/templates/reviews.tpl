{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="admin_or_editor" value=1}
{else}
    {assign var="admin_or_editor" value=0}
{/if}
{if $reviews_type eq "pendingReviews"}
    {include file="reviews_pending.tpl"}
{elseif $reviews_type eq "unpublishedReviews"}
    {include file="reviews_unpublished.tpl"}
{elseif $reviews_type eq "reviewsByIssue"}
    {include file="reviews_by_issue.tpl"}
{elseif $reviews_type eq "orderToPublish"}
		{include file="order_to_publish.tpl"}
{elseif $reviews_type eq "authorInformation"}
		{include file="author_information.tpl"}
{elseif $reviews_type eq "myReviews"}
		{include file="reviews_my_reviews.tpl"}
{elseif $reviews_type eq "newAttachments"}
		{include file="new_attachments.tpl"}
{elseif $reviews_type eq "home"}
    <div id="reviews_search">
        <input type="text" style="width: 500px; margin-right: 10px; float: left; height:24px; font-size: 18px;" {if $query.query}value="{$query.query}"{/if}></input>
        <select style="float:left; height:26px; font-size: 18px; margin-right: 10px; width:135px;">
            <option {if $query.search eq "Everything"}selected{/if}>Everything</option>
            <option {if $query.search eq "Book Title"}selected{/if}>Book Title</option>
            <option {if $query.search eq "Reviewer"}selected{/if}>Reviewer</option>
            <option {if $query.search eq "Assoc Editor"}selected{/if}>Assoc Editor</option>
        </select>
        <div id="reviews_search_button" class="button" style="float:left; height: 24px; font-size: 14px; padding: 0px !important;">Search</div>
        <div style="clear: left;"></div>
    </div>
    <div id="reviews_actions">
        <ul>
          {if $user.role_id eq $role.EDITOR || $user.role_id eq $role.ADMINISTRATOR}
          <li id="reviews_add">Add New</li>
					{/if}
					<li id="reviews_my" {literal}onclick="showStatus('Loading...', false, function(){window.location=uri+'/reviews/myReviews'});"{/literal}>My Reviews</li>
          {if $user.role_id eq $role.EDITOR || $user.role_id eq $role.ADMINISTRATOR}
          <li id="reviews_pending" {literal}onclick="showStatus('Loading...', false, function(){window.location=uri+'/reviews/pendingReviews'});"{/literal}>Pending Reviews</li>
          <li id="reviews_unpublished" {literal}onclick="showStatus('Loading...', false, function(){window.location=uri+'/reviews/unpublishedReviews'});"{/literal}>Unpublished Reviews</li>
          <li id="reviews_by_issue" {literal}onclick="showStatus('Loading...', false, function(){window.location=uri+'/reviews/reviewsByIssue'});"{/literal}>Reviews By Issue</li>
          {/if}
       </ul>
    </div>
     
    <div id="reviews_content">
    {if isset($results)}
      {if $results ne false}
      <div id="reviews_search_label">
        {if $search_type eq "reviewer"}
        <b>{$first_name}'s</b> Book Reviews
        {else $search_type eq "ae"}
        Reviews delegated by <b>{$first_name}</b> ({$results|@count})
        {/if}
      </div>
      <div id="reviews_search_results">
      {foreach from=$results item=r}
          <div style="background: #DDDDDD; margin: 4px;">
            <h4><a title="Click to Load Review" href="{$uri}/reviews/edit/{$r.book_review_id}">{$r.book.title}</a></h4>
                <div style="margin-left: 10px;">
                    <i>{$r.journal} - {$r.review_type}</i><br>
                    {if $review.reviewer}
                    <b>Review assigned to {$review.reviewer.first_name} {$review.reviewer.last_name}</b><br>
                    {/if}
                    {if $review.date_promised} 
                    <i>Review promised on {$review.date_promised}</i><br>
                    {/if}
                    {if $r.date_received}
                    <i>Review receieved on {$r.date_received}</i><br>
                    {/if}
                    <i>{$review.notes}</i>
                </div>
            </div>
      {/foreach}
      </div>
      {else}
      <div id="pending_content">
        <center>
            <h3>No reviews found for <b>{if $search_type eq "book"}<br>"<span style='text-decoration:underline;'>{$title}</span>"{else}{$first_name}{/if}</b></h3>
        </center>
      {/if}
 {else}
     <br><br>
     <center>
        <h3>Use the text box above<br>to search for reviews.</h3>
     </center>
  {/if}
</div>
    
    <script type="text/javascript">
        {literal}
        $("#reviews_search_button").bind("click",function(){
            searchReviews();
        });
        

        $("#reviews_add").bind("click", function(){
            createNewBookReview();
        });


        {/literal}

        {if $results}
            {literal}
                $("#reviews_content div.button").bind("click", function(){
                    review_id = this.id.replace("load_book_review_button__","");
                    showStatus("Loading...",false, function(){
                        window.location = uri+"/reviews/edit/"+review_id;
                    });
                });
            {/literal}
        {/if}

        {if $query}
            searchReviews({if $query.offset}{$query.offset}{else}undefined{/if},{if $query.order}"{$query.order}"{else}undefined{/if});
        {/if}
    </script>

{elseif $reviews_type eq "edit" OR $review_type eq "new"}
    {include file="review_form.tpl"}
{/if}
