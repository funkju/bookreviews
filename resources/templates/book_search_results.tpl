<div id='book_search_header'>
  {if $begin > 0}<a href="Javascript:;" class='prev' onclick="execFilter({math equation="x-y" x=$begin y=$limit});">&lt;&lt;prev</a>{/if}
  <span style="margin-right: 10px;"><b>{$total}</b> results for "{$query}"</span>
  <span style=""><b>(Showing {if $begin eq 0 && $end ne 0}1{else}{$begin}{/if}-{$end})</b></span>
  {if $end < $total}<a href="Javascript:;" onclick="execFilter({$end});" class='next'>next&gt;&gt;</a>{/if}<br>
</div>
<div id="book_list">
    <table class='table table-striped'>
    {foreach from=$books item=book}
        <tr onClick="window.location = '{$uri}/books/edit/{$book.book_id}';">
          <td>
            <span style="font-weight: bold;">
                {$book.title_highlight}
            </span>
            <br>
            <span style="padding-left: 10px;">
                <i>By:</i>
                {$book.authors_highlight}
            </span>
          </td>
        </tr>
    {/foreach}
    </table>
</div>
