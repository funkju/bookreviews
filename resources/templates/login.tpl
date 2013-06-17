<div id="login_fields">
<h1 style="width: 315px; padding-bottom: 25px;">Login</h1>
<form name="login" action="{$uri}/svc/doLogin" method="post" >
    <table>
        <tbody>
            <tr>
                <td colspan=2>
                    <div id="login_error"></div>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">
                    <label for="username">Username:</label>
                </td>
                <td style="width: 150px;">
                    <input type="text" name="username" id="username" style="width: 100%;"><br>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">
                    <label for="username">Password:</label>
                </td>
                <td style="width: 150px;">
                    <input type="password" name="password" id="password"  style="width: 100%;">
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br>
                    <div class="button" onClick="doLogin()">Login</div>
                </td>
            </tr>
        </tbody>
    </table>
</form>
</div>
<div id="login_form_links">
{include file="forms.tpl"}
</div>

{literal}
<script type="text/javascript">
    $(document).keypress( function(e){
        if(e.keyCode == 13){
            doLogin();
            return false;
        }
    });
</script>
{/literal}
