{if $distribution_list}

<input type="text" id="dist_name" value="{$distribution_list.name}">
<input type="hidden" id="dist_list_id" value="{$distribution_list.distribution_list_id}">
<div id="dist_expire_wrap">
    <label>Expires:</label>
    <input type="text" id="dist_expire" value="{$distribution_list.expires}">
</div>
<div class="button" id="dist_save_button">Save Distribution List</div>

{else}

<input type="text" id="dist_name" value="{$smarty.now|date_format:"%b %Y"} Distribution List">
<div id="dist_expire_wrap">
    <label>Expires:</label>
    <input type="text" id="dist_expire">
</div>
<div class="button" id="dist_create_button">Create Distribution List</div>

{/if}

<div style="clear:left; border-bottom: 1px solid #444444; margin-bottom: 10px;"></div>
<div id="chk_holder">Check <a href="Javascript:;" id="check_all">All</a>/<a href="Javascript:;" id="check_none">None</a></div>
<table id="dist_create_table">
    <thead>
        <tr>
            <th></th>
            <th>Title</th>
            <th>Authors</th>
            <th>Year</th>
        </tr>
    </thead>
    <tbody>
{foreach from=$books item=book}
        <tr id="{$book.book_id}">
            <td class="check">  <input type="checkbox" {if !$distribution_list || $book.selected}checked{/if}></td>
            <td class="title">  {$book.title}</td>
            <td class="authors">{$book.authors}</td>
            <td class="Year">{$book.year}</td>
        </tr>
{foreachelse}
        <tr>
            <td colspan=3 style='padding-top: 20px; font-size: 20px; font-weight: bold;'>No Books Available</td>
        </tr>
{/foreach}
    </tbody>
</table>

{literal}
<script type="text/javascript">
    $("#dist_expire").datepicker();

    $("#check_all").bind("click",function(){
        $("td.check > input").attr('checked',true);
    });
    $("#check_none").bind("click",function(){
        $("td.check > input").attr('checked',false);
    });
    $("#dist_create_table td").bind("click",function(ev){
        //Toggle checkbox on cell clicks
        if(ev.originalTarget.type != "checkbox") $("input:checkbox",$(this).parent()).attr('checked',!$("input:checkbox",$(this).parent()).attr('checked'));
    });

    $("#dist_save_button").bind("click",saveDistList);
    $("#dist_create_button").bind("click",createDistList);
</script>
{/literal}
