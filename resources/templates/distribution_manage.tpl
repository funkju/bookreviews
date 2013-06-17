Order By: <a id="sort_by_active" href="Javascript:;" onclick="sortDL('active')">Active</a> &nbsp; 
          <a id="sort_by_name" href="Javascript:;" onclick="sortDL('name')" style='font-weight: bold'>Name</a> &nbsp; 
          <a id="sort_by_create" href="Javascript:;" onclick="sortDL('created')">Created</a> &nbsp; 
          <a id="sort_by_expires" href="Javascript:;" onclick="sortDL('expires')">Expires</a>
<br><br>

<table>
    <tbody id="dist_list_holder">
{foreach from=$distLists item=dl}
        <tr id="dist_list_{$dl.distribution_list_id}" style="padding-bottom: 10px;" class="dist_list_row">
            <td style="font-size: 25px; padding-right:15px; width: 500px;">
                <input type="hidden" class="created" value="{$dl.created_raw}"></input>
                <input type="hidden" class="expires" value="{$dl.expires_raw}"></input>
                <span style="{if $dl.active}font-weight: bold;{/if}">{$dl.name}</span><br>
                <div style="font-size: 14px; padding-left: 10px; padding-bottom: 10px;">
                <i>Created:</i> {$dl.created}&nbsp;&nbsp;&nbsp;
                <i>Expires:</i> {if !$dl.expires}Never{else}{$dl.expires}{/if}
                </div>
            </td>
            <td style="vertical-align: top;">
                <a class="manage_dl_view" href="{$uri}/distribution/assignBooks?dl_id={$dl.distribution_list_id}">View</a>&nbsp;&nbsp;
                <a class="manage_dl_edit" href="{$uri}/distribution/createDistribution?dl_id={$dl.distribution_list_id}">Edit</a>&nbsp;&nbsp;
                <a id="deact_{$dl.distribution_list_id}" class="manage_dl_deact" style="{if !$dl.active}display: none;{/if} width: 100px; display:block-inline;" href="Javascript:;" onclick="toggleDLAct({$dl.distribution_list_id},0)">Deactivate</a>
                <a id="act_{$dl.distribution_list_id}" class="manage_dl_act" style="{if $dl.active}display: none;{/if} width: 100px; display:block-inline;" href="Javascript:;" onclick="toggleDLAct({$dl.distribution_list_id},1)">Activate</a>
            </td>
         </tr>
{/foreach}
    </tbody>
</table>
