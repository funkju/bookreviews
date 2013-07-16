{if $type ne "login"}

    <div id="welcome">
        Welcome {if $user.last_name != ""}{$user.first_name} {$user.last_name}{else}{$user.institution}{/if}.
    </div>

    {if $type ne "home"}
        <div id="back_to_home">
            <a onclick="showStatus('Loading...',false);" href="{$uri}/home/">Go Home</a>&nbsp;&nbsp;&nbsp;
            {if $back}<a onclick="showStatus('Loading...',false);" href="{$back.url}">{$back.name}</a>{/if}
        </div>
    {/if}

    <div id="status_holder">
        <div id="status_message"> The Status is Good!</div>
    </div>

{/if}
