{if $distType eq "create"}
    {include file="distribution_create.tpl"}
{elseif $distType eq 'assign'}
    {include file="distribution_assign.tpl"}
{elseif $distType eq 'manage'}
    {include file="distribution_manage.tpl"}
{else}
        <div id="distribution_actions">
  {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
            <a id="dist_assign_books"    href="{$uri}/distribution/assignBooks">Assign Books</a>
    {if $distribution_list}
            <a id="dist_deactivate_list" href="Javascript:;" onClick="deactivateDistList({$distribution_list.distribution_list_id})">Deactivate This List</a>
    {/if}
            <a id="dist_create_dist"     href="{$uri}/distribution/createDistribution">Create Distribution List</a>
            <a id="dist_manage_lists"    href="{$uri}/distribution/manageDistributions">Manage Distributions</a>
            <br>
  {/if}
            {if $distribution_list.expires}<span style="float: right; font-size:18px; padding-top: 10px;">Please make your selections by <b>{$distribution_list.expires}</b>.</span>{/if}
        </div>
  {if $distribution_list}
        <div id="distribution_ranks" style="top: {if $distribution_list.expires}184px{else}151px{/if};">
            {include file="distribution_ranks.tpl"}
        </div>
        <div id="distribution_books" {if $user.role_id ne $role.ADMINISTRATOR && $user.role_id ne $role.EDITOR}style="margin-top: 32px !important;"{/if} >
            <h3>{$distribution_list.name} <a href="Javascript:;" onClick="howToSaveMsg()" style="float: right; font-size: 12px; padding-top: 6px;">How do I save?</a></h3>
            <div id="distribution_books_table_holder">
                <table id="distribution_books_table">
                    <tbody>
{foreach from=$distribution_books item=book}
                        <tr id={$book.book_id} class="{cycle values='even,odd'}">
                            <td class='rank'>
                                <select>
                                <option>--</option>
                                    <option {if $book.rank eq 1}selected{/if}>1</option>
                                    <option {if $book.rank eq 2}selected{/if}>2</option>
                                    <option {if $book.rank eq 3}selected{/if}>3</option>
                                    <option {if $book.rank eq 4}selected{/if}>4</option>
                                    <option {if $book.rank eq 5}selected{/if}>5</option>
                                    <option {if $book.rank eq 6}selected{/if}>6</option>
                                    <option {if $book.rank eq 7}selected{/if}>7</option>
                                    <option {if $book.rank eq 8}selected{/if}>8</option>
                                    <option {if $book.rank eq 9}selected{/if}>9</option>
                                    <option {if $book.rank eq 10}selected{/if}>10</option>
                                </select>
                            </td>
                            <td class='title'>
                                <span style="font-weight: bold;">
                                    {$book.title}
                                </span>
                                <br>
                                <span style="padding-left: 10px;">
                                    <i>By:</i>
                                    {$book.authors}
                                </span>
                            </td>
                            <td class='details'>
                                <div class="button" onclick="bookModal({$book.book_id});">Details</div>
                            </td>
                        </tr>
{/foreach}
                    </tbody>
                </table>
            </div>
        </div>
  {else}
        <div id="distribution_message_holder" style="padding-top: 60px;">
        <center>
            <h2>No active distribution lists.</h2>
        </center>
       </div>
  {/if}
    <div id="distribution_scratch" style="display: none;"></div>
    {literal}
    <script type="text/javascript">
        {/literal}
        {if $distribution_list}
        var distribution_list_id = {$distribution_list.distribution_list_id};
        {/if}
        {literal}

        $("#distribution_books select").bind("change", rankChange);

        $(window).scroll(function(ev){
            var wh = $(window).height();

            if( wh > 615 && $(document).scrollTop() > {/literal}{if $distribution_list.expires}184{else}145{/if}{literal} ){
                var dh = $(document).height();
                var st = $(document).scrollTop();
                var drh = $("#distribution_ranks").height();

                $("#distribution_ranks").addClass('fixed_scroll');
                if(wh < 780 && drh >= (dh - st - 120)){
                    $("#distribution_ranks").css('margin-top',-1*(drh-(dh-st-100)));
                    
                }
            } else {
                $("#distribution_ranks").removeClass('fixed_scroll');
                $("#distribution_ranks").css('margin-top',null);
            }
        });

    </script>
    {/literal}
{/if}
