            <h3 style="font-weight: bold;">My Selections</h3>
            <ul>
{section name=rank_count start=1 loop=11}
    {assign var="found" value=0}
    {foreach from=$distribution_ranks item=rank}
        {if $smarty.section.rank_count.index eq $rank.rank}
            {assign var="found" value=1}
                <li id="rank_{$rank.distribution_list_preference_id}">
                    <div class="rank_number">{$rank.rank}.</div>
                    {if $rank.thumbnail_url}
                        <img src='{$rank.thumbnail_url}' style="width: 30px;"></img>
                    {else}
                        <img src='{$uri}/resources/images/book_48.png' style="width: 30px;"></img>
                        
                    {/if}
                    <span style="font-weight: bold; font-size: 11px; line-height: 13px;">{$rank.title}</span>
                </li>
        {/if}
    {/foreach}
    {if !$found}
               <li>
                    <div class="rank_number">{$smarty.section.rank_count.index}.</div>
               </li>
    {/if}
{/section}
            </ul>
