{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="admin_or_editor" value=1}
{else}
    {assign var="admin_or_editor" value=0}
{/if}



<div id="submitted_content">
   <div id="submitted_left">
       <label for="submitted_filter" id="submitted_filter_label">Find Content</label><br>
       <input type="text" name="submitted_filter" id="submitted_filter">
       <br /><br />
	<div id="submitted_list">
          <ul>
       		{foreach from=$mats item=i}
			<li>{$i.title} - {$i.author_name}</li>
		{/foreach}
          </ul>
       </div>
    </div>
    <div id="submitted_center">
        {include file="submitted_form.tpl"}
    </div>
</div>


{literal}
<script type="text/javascript">

</script>
{/literal}

