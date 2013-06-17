<div id="password_modal_gray" style="background-color: black; position: absolute; top:0px; z-index:9998; width: 100%; height:100%; filter: alpha(opacity = 50); opacity: 0.5;"></div>
<div id="password_modal" style="position: absolute; top:0px; z-index:9999; width: 100%; height:100%;">
    <div id="password_modal_content" style="min-height: 120px; width: 310px; margin: auto; background-color: white; margin-top: 15%; padding: 10px; border: 4px solid #D7DCFF; -moz-border-radius: 4px;">
        <table id="change_password_table">
            <tr>
                <td> <b>Choose a Password:</b> </td>
                <td> <input type="password" id="password_1"></input></td>
            </tr>
            <tr>
                <td> <b>Confirm Password:</b> </td>
                <td> <input type="password" id="password_2"></input> </td>
            </tr>
            <tr>
                <td colspan=2 id="change_password_message" style="font-style: italic; font-weight: bold; text-align: center;"></td>
            </tr>
            <tr>
                <td colspan=2>
                    <div class="button" id="do_change_password" onclick="changePassword()">Change Password</div>
                    {literal}
                    <div class="button2" id="cancel_change_password" onclick="$('#password_modal').fadeOut(500, function(){$('#password_modal_gray').remove(); $('#password_modal').remove();$('#body').height(null);$('#body').css('overflow-y','auto');});">Cancel</div>
                    {/literal}
                </td>
            </tr>
        </table>
    </div>
</div>
