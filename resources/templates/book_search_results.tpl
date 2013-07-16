<span style="margin-right: 10px;"><b>{$total}</b> results for "{$query}"</span>
<span style=""><b>(Showing {if $begin eq 0 && $end ne 0}1{else}{$begin}{/if}-{$end})</b></span>
{if $begin > 0}<a href="Javascript:;" onclick="execFilter({math equation="x-y" x=$begin y=$limit});">&lt;&lt;prev</a>{/if}
{if $end < $total}<a href="Javascript:;" onclick="execFilter({$end});" style="float: right;">next&gt;&gt;</a>{/if}<br>
<div id="book_list">
    <ul>
    {foreach from=$books item=book}
        <li id="{$book.book_id}">
            <span style="font-weight: bold;">
                {$book.title_highlight}
            </span>
            <br>
            <span style="padding-left: 10px;">
                <i>By:</i>
                {$book.authors_highlight}
            </span>
        </li>
    {/foreach}
    </ul>
</div>
