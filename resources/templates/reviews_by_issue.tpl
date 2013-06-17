{if !isset($reviews)}
<div id="journal">
    <label>Journal:</label>
    <select id="seljournal">
        <option value="">---</option>
        {foreach from=$journals item=j}
            <option value="{$j.journal_id}">{$j.journal}</option>
        {/foreach}
    </select>
</div>

<div id="year" style="display: none;">
    <label>Year:</label> 
    <select id="selyear">
        <option value="1990">1990</option>
        <option value="1991">1991</option>
        <option value="1992">1992</option>
        <option value="1993">1993</option>
        <option value="1994">1994</option>
        <option value="1995">1995</option>
        <option value="1996">1996</option>
        <option value="1997">1997</option>
        <option value="1998">1998</option>
        <option value="1999">1999</option>
        <option value="2000">2000</option>
        <option value="2001">2001</option>
        <option value="2002">2002</option>
        <option value="2003">2003</option>
        <option value="2004">2004</option>
        <option value="2005">2005</option>
        <option value="2006">2006</option>
        <option value="2007">2007</option>
        <option value="2008">2008</option>
        <option value="2009">2009</option>
        <option value="2010">2010</option>
        <option value="2011">2011</option>
        <option value="2012">2012</option>
        <option value="2013">2013</option>
        <option value="2014">2014</option>
        <option value="2015">2015</option>
    </select>
</div>
<img src="{$uri}/resources/images/spinner.gif" id="year_spinner" style="display: none;"></img>

<div id="month" style="display: none;">
    <label>Month:</label>
    <select id="selmonth">
        <option value="1">January</option>
        <option value="2">Febuary</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">Septemeber</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select>
</div>
<img src="{$uri}/resources/images/spinner.gif" id="month_spinner" style="display: none;"></img>

{literal}
<script type="text/javascript">
    $("#seljournal").bind("change",fillYears);
    $("#selmonth").bind("change",getReviewsByIssue);
    $("#selyear").bind("change",fillMonths);


    if($.cookie('rev_by_issue_journal_id')){
        $("#seljournal").val($.cookie('rev_by_issue_journal_id'));
    }
    if($.cookie('rev_by_issue_year')){
        $("#selyear").val($.cookie('rev_by_issue_year'));
        $("#year").show();
    }
    if($.cookie('rev_by_issue_month')){
        $("#selmonth").val($.cookie('rev_by_issue_month'));
        $("#month").show();
    }

    if($.cookie('rev_by_issue_journal_id') && $.cookie('rev_by_issue_year') && $.cookie('rev_by_issue_month')){
        getReviewsByIssue();
        cleanReviewsByIssueSelects();
    }

</script>
{/literal}

<img src="{$uri}/resources/images/spinner.gif" id="content_spinner" style="display: none; clear: left;"></img>
<div id="reviews_by_issue" style="clear:left; padding-top: 20px;"></div>
{else}
    <h3>Reviews for {$month} {$year} of {$journal}</h3>
		<div id='reviews_by_issue_actions'>
			<ul>
				<li id='order_to_publish'><a href="{$uri}/reviews/orderToPublish">Order To Publish List</a></li>
				<li id='author_info'><a href="{$uri}/reviews/authorInformation">Author Information List</a></li>
			</ul>
		</div>
		<br><br>
    <table style="padding-left: 20px; width: 100%">
      <tr><td style="width: 50px">Order</td></tr>
    {foreach from=$reviews item=review}
      <tr id="review_id_{$review.book_review_id}">
        <td>
            <select class="publish_order">
                <option value="">---</option>  
              {section name="pub_order" start=1 loop=$reviews}
                <option value="{$smarty.section.pub_order.index}" {if $smarty.section.pub_order.index eq $review.publish_order}selected{/if}>{$smarty.section.pub_order.index}</option>  
              {/section}
                <option value="{$smarty.section.pub_order.index}" {if $smarty.section.pub_order.index eq $review.publish_order}selected{/if}>{$smarty.section.pub_order.index}</option>
            </select>
        </td>
        <td>
            <h4><a title="Click to Load Review" href="{$uri}/reviews/edit/{$review.book_review_id}">{$review.title}</a></h4>
            <div style="margin-left: 20px;">
               {if $review.rev_first_name}<div style="display: inline-block; width:250px"><b>Review By:</b>  <a href="{$uri}/people/edit/{$review.rev_id}">{$review.rev_first_name} {$review.rev_last_name}</a></div>{/if}
               <b>Associate Editor:</b> <a href="{$uri}/people/edit/{$review.ae_id}">{$review.ae_first_name} {$review.ae_last_name}</a><br>
               <i>{$review.notes}</i>
            </div>
        </td>
      </tr>
      {foreachelse}
          <tr><td>No Reviews</td></tr>
      {/foreach}
   </table>

{/if}
