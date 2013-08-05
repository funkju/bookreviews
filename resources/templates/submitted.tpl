{if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {assign var="admin_or_editor" value=1}
{else}
    {assign var="admin_or_editor" value=0}
{/if}



<div id="submitted_content">
   <div id="submitted_left">
       <label for="submitted_filter" id="submitted_filter_label">Find Content</label><br>
       <input type="text" name="submitted_filter" id="submitted_filter"><br>
       <label for="submitted_unscreened"><input type="checkbox" id="submitted_unscreened" name="submitted_unscreened" />Show screened</label>
       <br /><br />
	<div id="submitted_list">
          <ul>
       		{foreach from=$mats item=i}
            {if $i.screen_status eq 1}{assign var="screen_text" value=""}{/if}
            {if $i.screen_status eq 2}{assign var="screen_text" value="(Rejected)"}{/if}
            {if $i.screen_status eq 3}{assign var="screen_text" value="(Distribute)"}{/if}
            {if $i.screen_status eq 4}{assign var="screen_text" value="(Assign) "}{/if}
			<li id="{$i.material_id}">{$screen_text}  {$i.title} - {$i.author_name}</li>
          {foreachelse}
         <div id="material_list_message" style="padding-top:40px; font-size:12px; text-align: center; color:grey;"> No unscreened<br>submitted content.</div>	{/foreach}
          </ul>
       </div>
    </div>
    <div id="submitted_top_right">
    </div>
    <div id="submitted_center">
        {include file="material_form.tpl"}
    </div>
</div>


{literal}
<script type="text/javascript">
      
      $("#submitted_ae_assign").bind('click', function(){
        showStatus('Loading...',false, function(){ window.location = uri+"/submitted/assign/"; });
      });

      $("#submitted_filter").bind('keyup', filterMaterial);
      $("#submitted_unscreened").bind('change', filterMaterial);


      $("#submitted_list li").bind("click",function(){
          $("#submitted_list li").each(function(idx,el){
            $(el).removeClass("selected");
          });

          $(this).addClass("selected");
          loadMaterial(this.id);
      });

</script>
{/literal}

