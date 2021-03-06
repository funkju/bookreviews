{if $num_new_attachments ne 0}
	<div id='new_attach_status'>
		<b>{$num_new_attachments}</b> new attachment{if $num_new_attachments ne 1}s have {else} has {/if}been uploaded. <a href='{$uri}/reviews/newAttachments/{$user.previous_login}'>Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:;' onclick='dismissAttachmentMsg()'>Dismiss</a>
	</div>
{/if}
<ul class="menu">
    {literal}
    <li class="menu_item books" onclick="showStatus('Loading...',false, function(){goTo(uri+'/books');})">Books
    {/literal}
    {if $user.role_id ne $role.REVIEWER}
        {literal}<li class="menu_item people"  onclick="showStatus('Loading...',false, function(){goTo(uri+'/people');})">People{/literal}

        {literal}<li class="menu_item distribution"  onclick="showStatus('Loading...',false, function(){goTo(uri+'/distribution');})">Distribution{/literal}

    {else}
        {literal}<li class="menu_item edit_prof" onclick="showStatus('Loading...',false, function(){goTo(uri+'/profile');})">Edit Profile{/literal}
    {/if}
    {literal}
        <li class="menu_item reviews" onclick="showStatus('Loading...',false,function(){goTo(uri+'/reviews');})">Book Reviews
    {/literal}

    {if $user.role_id eq $role.ADMINISTRATOR || $user.role_id eq $role.EDITOR}
    {literal}
	<li class="menu_item submitted" onclick="showStatus('Loading...',false, function(){goTo(uri+'/submitted');})">Submitted Content
    {/literal}
    {literal}
        <li class="menu_item mailing" onclick="showStatus('Loading...',false,function(){goTo(uri+'/mailing');})">Mailing
    {/literal}
    {literal}
        <li class="menu_item reports" onclick="showStatus('Loading...',false,function(){goTo(uri+'/reports');})">Reports
    {/literal}
    {/if} 
    {literal}
        <li class="menu_item forms" onclick="showStatus('Loading...',false,function(){goTo(uri+'/forms');})">Forms
    {/literal}
</ul>
