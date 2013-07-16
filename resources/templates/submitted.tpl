{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="admin_or_editor" value=1}
{else}
    {assign var="admin_or_editor" value=0}
{/if}



<div id="submitted_content">
    <div id="submitted_center" style="{if isset($books_to_assign)}width:750px;{/if}">
       	{foreach from=$mats item=i}
		{$i}

	{/foreach}

	<div id="submitted_list" style="display: none;">
          <ul>
          </ul>
       </div>
    </div>
</div>


{literal}
<script type="text/javascript">

</script>
{/literal}

