{if $num_new_attachments ne 0}
	<div id='new_attach_status'>
		<b>{$num_new_attachments}</b> new attachment{if $num_new_attachments ne 1}s have {else} has {/if}been uploaded. <a href='{$uri}/reviews/newAttachments/{$user.previous_login}'>Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:;' onclick='dismissAttachmentMsg()'>Dismiss</a>
	</div>
{/if}
<ul class="menu">
    {literal}
    <li class="menu_item" onclick="showStatus('Loading...',false, function(){goTo(uri+'/books');})"><i class='icon-book'></i>Books
    {/literal}
    {if $user.role_id ne $role.REVIEWER}
        {literal}<li class="menu_item"  onclick="showStatus('Loading...',false, function(){goTo(uri+'/people');})"><i class='icon-user'></i>People{/literal}

        {literal}<li class="menu_item"  onclick="showStatus('Loading...',false, function(){goTo(uri+'/distribution');})"><i class='icon-globe'></i>Distribution{/literal}

    {else}
        {literal}<li class="menu_item edit_prof" onclick="showStatus('Loading...',false, function(){goTo(uri+'/profile');})">Edit Profile{/literal}
    {/if}
    {literal}
        <li class="menu_item" onclick="showStatus('Loading...',false,function(){goTo(uri+'/reviews');})"><i class='icon-file'></i>Book Reviews
    {/literal}

    {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {literal}
        <li class="menu_item" onclick="showStatus('Loading...',false,function(){goTo(uri+'/mailing');})"><i class='icon-inbox'></i>Mailing
    {/literal}
    {literal}
        <li class="menu_item" onclick="showStatus('Loading...',false,function(){goTo(uri+'/reports');})"><i class='icon-signal'></i>Reports
    {/literal}
    {/if} 
    {literal}
        <li class="menu_item" onclick="showStatus('Loading...',false,function(){goTo(uri+'/forms');})"><i class='icon-folder-open'></i>Forms
    {/literal}
</ul>
