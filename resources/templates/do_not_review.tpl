{foreach from=$reviews item=revs key=year}
<h2>{$year}</h2>
<ul>
{foreach from=$revs item=r}
<li>{$r.book_title} By: <i>{$r.authors}</i></li>
{/foreach}
</ul>
<br><br>
{/foreach}