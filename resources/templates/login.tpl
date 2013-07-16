<div id="login_fields">
</div>
<div id="login_error"></div>
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
