        <div id="people_form" {if !$person}style="display: none;"{/if}>
            <input type="hidden" id="person__person_id" value="{$person.person_id}">
            <div id="people_form_Company">
                <label for="first_name" id="people_first_name">First Name</label>
                <label for="middle_name" id="people_middle_name">Middle Name</label>
                <label for="last_name" id="people_last_name">Last Name</label>
                <br>
                {if $user.role_id eq $role.EDITOR || $user.role_id eq $role.ADMINISTRATOR || $user.person_id eq $person.person_id}
                    <input class="people" type="text" name="first_name" id="person__first_name" tabindex=1 value="{$person.first_name}">
                    <input class="people" type="text" name="middle_name" id="person__middle_name" tabindex=2 value="{$person.middle_name}">
                    <input class="people" type="text" name="last_name" id="person__last_name" tabindex=3     value="{$person.last_name}">
                {else}
                    <input class="people" type="text" name="first_name" id="person__first_name" tabindex=1 value="{$person.first_name}" disabled>
                    <input class="people" type="text" name="middle_name" id="person__middle_name" tabindex=2 value="{$person.middle_name}" disabled>
                    <input class="people" type="text" name="last_name" id="person__last_name" tabindex=3     value="{$person.last_name}" disabled>
                {/if}
            </div>
            <div id="people_form_Department">
                <label for="username" id="people_username">Username</label>
                <label>&nbsp;</label>
                <label>Role</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
                    <input class="people" type="text" name="username" id="person__username" tabindex=4 value="{$person.username}">
                {else}
                    <input class="people" type="text" name="username" id="person__username" tabindex=4 value="{$person.username}" disabled>
                {/if}
                <div id="password_button_holder">
                    {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                        <div class="button" id="change_password" style="display:;">Change Password</div>
                    {/if}
                    &nbsp;
                </div>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
                    <select class="people" name="role_id" id="person__role_id" tabindex=5>
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
             <div id="people_form_line_3">
                <label for="institution" id="people_institution">Institution</label>
                    <label for="department" id="people_department">Department</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR  || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="people" type="text" name="institution" id="person__institution" tabindex=6 value="{$person.institution}">
                    <input class="people" type="text" name="department" id="person__department" tabindex=7  value="{$person.department}">
                {else}
                    <input class="people" type="text" name="institution" id="person__institution" tabindex=6 value="{$person.institution}" disabled>
                    <input class="people" type="text" name="department" id="person__department" tabindex=7  value="{$person.department}" disabled>
                {/if}
            </div>
            <div id="people_form_line_4">
                <label for="notes" id="people_notes">Notes</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <textarea name="notes" id="person__notes" class="people" tabindex=8>{$person.notes}</textarea>
                {else}
                    <textarea name="notes" id="person__notes" class="people" tabindex=8 disabled>{$person.notes}</textarea>
                {/if}
            </div>
            <div id="people_form_line_5">
                <h3>Mailing Address</h3>
                <input type="hidden" id="address__address_id" value="{$address.address_id}">
            </div>
            <div id="people_form_line_6">
                <label for="FullName" id="people_FullName">Full Name</label>
                <label for="voice" id="people_voice">Voice</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR  || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="FullName" id="address__FullName" tabindex=9 value="{$address.FullName}"> 
                    <input class="address" type="text" name="voice" id="address__voice"  tabindex=16 value="{$address.voice}">
                {else}
                    <input class="address" type="text" name="FullName" id="address__FullName" tabindex=9 value="{$address.FullName}" disabled> 
                    <input class="address" type="text" name="voice" id="address__voice"  tabindex=16 value="{$address.voice}" disabled>
                {/if}
            </div>
            <div id="people_form_line_7">
                <label for="Company" id="people_Company">Company</label>
                <label for="fax" id="people_fax">Fax</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Company" id="address__Company"  tabindex=10 value="{$address.Company}">
                    <input class="address" type="text" name="fax" id="address__fax"  tabindex=17 value="{$address.fax}">
                {else}
                    <input class="address" type="text" name="Company" id="address__Company"  tabindex=10 value="{$address.Company}" disabled>
                    <input class="address" type="text" name="fax" id="address__fax"  tabindex=17 value="{$address.fax}" disabled>
                {/if}
            </div>
            <div id="people_form_line_8">
                <label for="Department" id="people_Department">Department</label>
                <label for="email" id="people_email">E-Mail</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Department" id="address__Department"  tabindex=11 value="{$address.Department}">
                    <input class="address" type="text" name="email" id="address__email"  tabindex=18 value="{$address.email}">
                {else}
                    <input class="address" type="text" name="Department" id="address__Department"  tabindex=11 value="{$address.Department}" disabled>
                    <input class="address" type="text" name="email" id="address__email"  tabindex=18 value="{$address.email}" disabled>
                {/if}
            </div>
            <div id="people_form_line_9">
                <label for="Address1" id="people_Address1">Address1</label>
                <br>
                {if  $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Address1" id="address__Address1"  tabindex=12 value="{$address.Address1}">
                {else}
                    <input class="address" type="text" name="Address1" id="address__Address1"  tabindex=12 value="{$address.Address1}" disabled>
                {/if}
            </div>
            <div id="people_form_line_10">
                <label for="Address2" id="people_Address2">Address2</label>
                <br>
                {if  $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="Address2" id="address__Address2"  tabindex=12 value="{$address.Address2}">
                {else}
                    <input class="address" type="text" name="Address2" id="address__Address2"  tabindex=12 value="{$address.Address2}" disabled>
                {/if}
            </div>
            <div id="people_form_line_11">
                <label for="City" id="people_City">City</label>
                <br>
                {if  $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="City" id="address__City"  tabindex=12 value="{$address.City}">
                {else}
                    <input class="address" type="text" name="City" id="address__City"  tabindex=12 value="{$address.City}" disabled>
                {/if}
            </div>
            <div id="people_form_line_12">
                <label for="State" id="people_State">State/Province/Region</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="State" id="address__State"  tabindex=13 value="{$address.State}"> 
                {else}
                    <input class="address" type="text" name="State" id="address__State"  tabindex=13 value="{$address.State}" disabled> 
                {/if}
            </div>
            <div id="people_form_line_13">
                <label for="ZIPCode" id="people_ZIPCode">ZIP Code</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="ZIPCode" id="address__ZIPCode"  tabindex=14 value="{$address.ZIPCode}">
                {else}
                    <input class="address" type="text" name="ZIPCode" id="address__ZIPCode"  tabindex=14 value="{$address.ZIPCode}" disabled>
                {/if}
            </div>
            <div id="people_form_line_14">
                <label for="country" id="people_country">Country</label>
                <br>
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <input class="address" type="text" name="country" id="address__Country"  tabindex=15 value="{$address.Country}">
                {else}
                    <input class="address" type="text" name="country" id="address__Country"  tabindex=15 value="{$address.Country}" disabled>
                {/if}
            </div>
            <div id="people_form_line_15">
                {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR || $user.person_id eq $person.person_id}
                    <div id="people_discard_button" class="button2">Discard Changes</div>
                   <div id="people_save_button" class="button">Save</div>
                {/if}
            </div>
        </div>

{literal}
<script type="text/javascript">

    $("#people_save_button").bind('click', savePerson);
    $("#people_discard_button").bind('click', function(){
       if($("#person__person_id").val()) loadPerson($("#person__person_id").val());
       
    });
    $("#change_password").bind('click', passwordModal);

    initInputs("#people_center input, #people_center textarea, #people_center select");
</script>
{/literal}
