{if $disabled}
    {if $selected eq 1}<input type="text" id="{$id}" class="{$class}" disabled value="January">{/if}
    {if $selected eq 2}<input type="text" id="{$id}" class="{$class}" disabled value="Febuary">{/if}
    {if $selected eq 3}<input type="text" id="{$id}" class="{$class}" disabled value="March">{/if}
    {if $selected eq 4}<input type="text" id="{$id}" class="{$class}" disabled value="April">{/if}
    {if $selected eq 5}<input type="text" id="{$id}" class="{$class}" disabled value="May">{/if}
    {if $selected eq 6}<input type="text" id="{$id}" class="{$class}" disabled value="June">{/if}
    {if $selected eq 7}<input type="text" id="{$id}" class="{$class}" disabled value="July">{/if}
    {if $selected eq 8}<input type="text" id="{$id}" class="{$class}" disabled value="August">{/if}
    {if $selected eq 9}<input type="text" id="{$id}" class="{$class}" disabled value="September">{/if}
    {if $selected eq 10}<input type="text" id="{$id}" class="{$class}" disabled value="October">{/if}
    {if $selected eq 11}<input type="text" id="{$id}" class="{$class}" disabled value="November">{/if}
    {if $selected eq 12}<input type="text" id="{$id}" class="{$class}" disabled value="December">{/if}
{else}
    <select id="{$id}" class="{$class}">
        <option value="" {if $selected eq ""}selected{/if}>----</option>
        <option value=1 {if $selected eq 1}selected{/if}>January</option>
        <option value=2 {if $selected eq 2}selected{/if}>Febuary</option>
        <option value=3 {if $selected eq 3}selected{/if}>March</option>
        <option value=4 {if $selected eq 4}selected{/if}>April</option>
        <option value=5 {if $selected eq 5}selected{/if}>May</option>
        <option value=6 {if $selected eq 6}selected{/if}>June</option>
        <option value=7 {if $selected eq 7}selected{/if}>July</option>
        <option value=8 {if $selected eq 8}selected{/if}>August</option>
        <option value=9 {if $selected eq 9}selected{/if}>September</option>
        <option value=10 {if $selected eq 10}selected{/if}>October</option>
        <option value=11 {if $selected eq 11}selected{/if}>November</option>
        <option value=12 {if $selected eq 12}selected{/if}>December</option>
    </select>
{/if}
