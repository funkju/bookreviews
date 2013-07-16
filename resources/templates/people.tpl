
<div id="people_content">
    {if !$aes}
    <div id="people_left">
       <label for="people_filter" id="people_filter_label">Find Person</label><br>
       <input type="text" name="people_filter" id="people_filter">
       <br><br>
       <div id="people_list">
          <ul>
            <div id="people_list_message" style="padding-top:40px; font-size:12px; text-align: center; color:grey;"> Use the textbox<br>above to search.</div>
          </ul>
       </div>
       <div id="show_reviews" class="button2" style="display:none; font-size: 11px; padding: 0px 3px 3px; margin-top:5px" >See Reviews</div>
    </div>
    <div id="people_top_right">
        <ul>
            {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
            <li id="people_ae_list">Get AE List</li>
            <li id="people_add">Add New</li>
            {/if}
        </ul>
    </div>

    <div id="people_center">
        {include file="person_form.tpl"}
    </div>
    {else}
        {include file="ae_list.tpl"}
    {/if}
</div>


<script type="text/javascript">
    /*Add listeners for DELETE and ADD links*/
    {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
            /*$("#people_del").bind('click', delPerson);*/


            $("#people_add").bind('click', addPerson);
            $("#people_ae_list").bind('click', {literal}function(){
                window.location = uri+"/people/AEList/";
            });{/literal}
    
    {/if}

    $("#people_filter").bind('keyup', filterPerson);

</script>

