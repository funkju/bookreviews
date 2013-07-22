        <div id="submitted_form" {if !$material}style="display: none;"{/if}>
            <input type="hidden" id="material__material_id" value="{$material.material_id}">
            <div id="submitted_form_Company">
                <label for="first_name" id="submitted_first_name">Submitter's Name</label>
                <label for="middle_name" id="submitted_middle_name">Submitter's Email</label>
                <br>
                <input class="submitted" type="text" name="name" id="submitted__name" tabindex=1 value="{$material.name}">
                <input class="submitted" type="text" name="email" id="submitted__email" tabindex=2 value="{$material.email}">
            </div>
            <div id="submitted_form_Department">
                <label for="username" id="submitted_username">Username</label>
                <label>&nbsp;</label>
                <label>Role</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
                    <input class="submitted" type="text" name="username" id="person__username" tabindex=4 value="{$person.username}">
                {else}
                    <input class="submitted" type="text" name="username" id="person__username" tabindex=4 value="{$person.username}" disabled>
                {/if}
                <div id="password_button_holder">
                    {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                        <div class="button" id="change_password" style="display:;">Change Password</div>
                    {/if}
                    &nbsp;
                </div>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
                    <select class="submitted" name="role_id" id="person__role_id" tabindex=5>
                      <option value="">Choose a Role
                      {foreach from=$roles item=l_role}
                        <option value={$l_role.role_id} {if $person.role_id eq $l_role.role_id}selected{/if}>{$l_role.role_full}
                      {/foreach}
                    </select>
                {else}
                    <input type="text" name="role_id" id="person__role_id" tabindex=5 disabled value=
                    {foreach from=$roles item=l_role}
                        {if $person.role_id eq $l_role.role_id}"{$l_role.role_full}"{/if}
                    {/foreach}>
                {/if}
             </div>
             <div id="submitted_form_line_3">
                <label for="institution" id="submitted_institution">Institution</label>
                    <label for="department" id="submitted_department">Department</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR  || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="submitted" type="text" name="institution" id="person__institution" tabindex=6 value="{$person.institution}">
                    <input class="submitted" type="text" name="department" id="person__department" tabindex=7  value="{$person.department}">
                {else}
                    <input class="submitted" type="text" name="institution" id="person__institution" tabindex=6 value="{$person.institution}" disabled>
                    <input class="submitted" type="text" name="department" id="person__department" tabindex=7  value="{$person.department}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_4">
                <label for="notes" id="submitted_notes">Notes</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <textarea name="notes" id="person__notes" class="submitted" tabindex=8>{$person.notes}</textarea>
                {else}
                    <textarea name="notes" id="person__notes" class="submitted" tabindex=8 disabled>{$person.notes}</textarea>
                {/if}
            </div>
            <div id="submitted_form_line_5">
                <h3>Mailing Address</h3>
                <input type="hidden" id="address__address_id" value="{$address.address_id}">
            </div>
            <div id="submitted_form_line_6">
                <label for="FullName" id="submitted_FullName">Full Name</label>
                <label for="voice" id="submitted_voice">Voice</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR  || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="FullName" id="address__FullName" tabindex=9 value="{$address.FullName}"> 
                    <input class="address" type="text" name="voice" id="address__voice"  tabindex=16 value="{$address.voice}">
                {else}
                    <input class="address" type="text" name="FullName" id="address__FullName" tabindex=9 value="{$address.FullName}" disabled> 
                    <input class="address" type="text" name="voice" id="address__voice"  tabindex=16 value="{$address.voice}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_7">
                <label for="Company" id="submitted_Company">Company</label>
                <label for="fax" id="submitted_fax">Fax</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Company" id="address__Company"  tabindex=10 value="{$address.Company}">
                    <input class="address" type="text" name="fax" id="address__fax"  tabindex=17 value="{$address.fax}">
                {else}
                    <input class="address" type="text" name="Company" id="address__Company"  tabindex=10 value="{$address.Company}" disabled>
                    <input class="address" type="text" name="fax" id="address__fax"  tabindex=17 value="{$address.fax}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_8">
                <label for="Department" id="submitted_Department">Department</label>
                <label for="email" id="submitted_email">E-Mail</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Department" id="address__Department"  tabindex=11 value="{$address.Department}">
                    <input class="address" type="text" name="email" id="address__email"  tabindex=18 value="{$address.email}">
                {else}
                    <input class="address" type="text" name="Department" id="address__Department"  tabindex=11 value="{$address.Department}" disabled>
                    <input class="address" type="text" name="email" id="address__email"  tabindex=18 value="{$address.email}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_9">
                <label for="Address1" id="submitted_Address1">Address1</label>
                <br>
                {if  $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Address1" id="address__Address1"  tabindex=12 value="{$address.Address1}">
                {else}
                    <input class="address" type="text" name="Address1" id="address__Address1"  tabindex=12 value="{$address.Address1}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_10">
                <label for="Address2" id="submitted_Address2">Address2</label>
                <br>
                {if  $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Address2" id="address__Address2"  tabindex=12 value="{$address.Address2}">
                {else}
                    <input class="address" type="text" name="Address2" id="address__Address2"  tabindex=12 value="{$address.Address2}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_11">
                <label for="City" id="submitted_City">City</label>
                <br>
                {if  $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="City" id="address__City"  tabindex=12 value="{$address.City}">
                {else}
                    <input class="address" type="text" name="City" id="address__City"  tabindex=12 value="{$address.City}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_12">
                <label for="State" id="submitted_State">State/Province/Region</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="State" id="address__State"  tabindex=13 value="{$address.State}"> 
                {else}
                    <input class="address" type="text" name="State" id="address__State"  tabindex=13 value="{$address.State}" disabled> 
                {/if}
            </div>
            <div id="submitted_form_line_13">
                <label for="ZIPCode" id="submitted_ZIPCode">ZIP Code</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="ZIPCode" id="address__ZIPCode"  tabindex=14 value="{$address.ZIPCode}">
                {else}
                    <input class="address" type="text" name="ZIPCode" id="address__ZIPCode"  tabindex=14 value="{$address.ZIPCode}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_14">
                <label for="country" id="submitted_country">Country</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="country" id="address__Country"  tabindex=15 value="{$address.Country}">
                {else}
                    <input class="address" type="text" name="country" id="address__Country"  tabindex=15 value="{$address.Country}" disabled>
                {/if}
            </div>
            <div id="submitted_form_line_15">
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <div id="submitted_discard_button" class="button2">Discard Changes</div>
                   <div id="submitted_save_button" class="button">Save</div>
                {/if}
            </div>
        </div>

{literal}
<script type="text/javascript">

//    $("#submitted_save_button").bind('click', );
    $("#submitted_discard_button").bind('click', function(){
       if($("#person__person_id").val()) loadPerson($("#person__person_id").val());
       
    });

    initInputs("#submitted_center input, #submitted_center textarea, #submitted_center select");
</script>
{/literal}
