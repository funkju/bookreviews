<div id="dist_person_hist_modal_gray" style="background-color: black; position: absolute; top:0px; z-index:9998; width: 100%; height:100%; filter: alpha(opacity = 50); opacity: 0.5;"></div>
<div id="dist_person_hist_modal" style="position: absolute; top:0px; z-index:9999; width: 100%; height:100%;">
    <div id="dist_person_hist_modal_content" style="height: 415px; width: 650px; margin: auto; background-color: #D7DCFF; margin-top: 10%; padding: 5px; border: 4px solid #D7DCFF; -moz-border-radius: 4px;">
        <div style='background-color: white; overflow-y: hidden; height: 405px; padding: 5px;'>
            <h4 style="padding-left: 10px; font-weight: bold; text-decoration: underline;">History for {$person.first_name} {$person.last_name}</h4>
            <table style="border-collapse: collapse; margin-left: 10px;">
                <tr>
                    <td style="padding-right: 10px;">
                        <i>Pending Reviews:</i>
                    </td>
                    <td>
                        <b>{$pending}</b>
                    </td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">
                        <i>Average Rank Assigned:</i>
                    </td>
                    <td>
                        <b>{$avg_rank}</b>
                    </td>
                </tr>
            </table>
            <br>
            <span style="margin-left: 10px; font-weight: bold;">Last {$reviews|@count} Book Reviews</span>
            <table class="dist_header" style="border-collapse: collapse; margin-left: 10px; width: 630px; text-align: center;">
                <tr>
                    <td style="font-weight: bold; width: 40px; border-bottom: 1px solid #444444;" onClick="distHistSort(this,'th.pend');">
                        <i>Pend</i>
                    </td>
                    <td style="width: 40px; border-bottom: 1px solid #444444;" onClick="distHistSort(this,'th.rank');">
                        <i>Rank</i>
                    </td>
                    <td style="width: 90px; border-bottom: 1px solid #444444;" onClick="distHistSort(this,'th.promised');">
                        <i>Promised</i>
                    </td>
                    <td style="border-bottom: 1px solid #444444; text-align: left;" onClick="distHistSort(this,'th.title');">
                        <i>Title</i><span style="float: right; font-weight: normal !important; font-size: 14px; margin-top: 3px;">(Click headers to sort)</span>
                    </td>
                </tr>
            </table>
            <div style="height: 265px; overflow-y: auto;">
                <table class="dist_hist" style="border-collapse: collapse; margin-left: 10px; width: 610px; text-align: center;">
                    <thead style="display:none;">
                        <tr>
                            <th class="pend"> Pend </th>
                            <th class="rank"> Rank </th>
                            <th class="promised"> Promised </th>
                            <th class="title"> Title </th>
                        </tr>
                    </thead>
                    <tbody>
{foreach from=$reviews item=rev}
                        <tr class="{cycle values="even,odd"}">
                            <td style="width: 40px">{if !$rev.date_received}<img src="{$uri}/resources/images/icons/tick.png"></img>&nbsp;{/if}</td>
                            <td style="width: 40px">{$rev.rank}</td>
                            <td style="width: 90px">{if $rev.date_promised}{$rev.date_promised}{else}---{/if}</td>
                            <td style="text-align: left;">{$rev.book.title}</td>
                        </tr>
{/foreach}
                    </tbody>
                </table>
            </div>
        {literal}
            <a style="float:right; margin-right: 20px;" href="Javascript:;" onclick="$('#dist_person_hist_modal').fadeOut(500, function(){$('#dist_person_hist_modal_gray').remove(); $('#dist_person_hist_modal').remove();$('#body').height(null);$('#body').css('overflow-y','auto');});">(X) Close</a>
        {/literal}
        </div>
    </div>
</div>
