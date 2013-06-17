<div id="book_modal_gray" style="background-color: black; position: absolute; top:0px; z-index:9998; width: 100%; height:100%; filter: alpha(opacity = 50); opacity: 0.5;"></div>
<div id="book_modal" style="position: absolute; top:0px; z-index:9999; width: 100%; height:100%;">
    <div id="book_modal_content" style="min-height: 275px; width: 400px; margin: auto; background-color: white; margin-top: 15%; padding: 10px; border: 4px solid #D7DCFF; -moz-border-radius: 4px;">
        <table>
            <tr>
                <td style="width: 75px">
                    {if $book_marketing_info.thumbnail_url}<img class="thumbnail" src="{$book_marketing_info.thumbnail_url}" style="float: left;"></img>
                    {else}<img class="thumbnail" src="{$uri}/resources/images/book_48.png" style="float: left;"></img>{/if}
                </td>
                <td style="width: 300px;"><b>{$book.title}</b> </td>
            </tr>
            <tr>
                <td> <b>Authors:</b> </td>
                <td> {$book.authors} </td>
            </tr>
            <tr>
                <td> <b>Publisher:</b> </td>
                <td> {$book.publisher} </td>
            </tr>
            <tr>
                <td> <b>Year:</b> </td>
                <td> {$book.year} </td>
            </tr>
            <tr>
                <td> <b>Edition:</b> </td>
                <td> {$book.edition_number} </td>
            </tr>
            <tr>
                <td> <b>Review:</b> </td>
                <td> {$journals[$book_review.journal_id]} {$review_types[$book_review.review_type_id]}</td>
            </tr>
            <tr>
                <td> <b>Synopsis:</b> </td>
            </tr>
            <tr>
                <td colspan=2 style="margin-left: 5px">{$book.synopsis}</td>
            </tr>
            <tr>
                <td colspan=2>
                    {literal}
                    <a style="float:right; margin-right: 20px;" href="Javascript:;" onclick="$('#book_modal').fadeOut(500, function(){$('#book_modal_gray').remove(); $('#book_modal').remove();$('#body').height(null);$('#body').css('overflow-y','auto');});">(X) Close</a>
                    {/literal}
                </td>
            </tr>
        </table>
    </div>
</div>
