<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

    <head>
        {include file="head.tpl"}
    </head>

    <body>
        <div id="body">
        {include file="top_bar.tpl"}
        <div id="content" style="margin: auto; width: 750px;">
            <div id="header">
                {include file="header.tpl"}
            </div>
            {include file="welcome.tpl"}
            {if $type eq "home"}
                <div id="menu">
                    {include file="home.tpl"}
                </div>
            {/if}
            {if $type eq "login"}
                <div id="login">
                    {include file="login.tpl"}
                </div>
            {/if}
            {if $type eq "people"}
                <div id="people">
                    {include file="people.tpl"}
                </div>
            {/if}
            {if $type eq "profile"}
                <div id="people">
                    <div id="people_center">
                        {include file="person_form.tpl"}
                    </div>
                </div>
            {/if}
            {if $type eq "books"}
                <div id="books">
                    {include file="books.tpl"}
                </div>
            {/if}
            {if $type eq "reviews"}
                <div id="reviews">
                    {include file="reviews.tpl"}
                </div>
            {/if}
            {if $type eq "distribution"}
                <div id="distribution">
                    {include file="distribution.tpl"}
                </div>
            {/if}
            {if $type eq "forms"}
                <div id="forms">
                    {include file="forms.tpl"}
                </div>
            {/if}
            {if $type eq "postage"}
                <div id="postage">
                    {include file="postage.tpl"}
                </div>
            {/if}
            {if $type eq "mailing"}
                <div id="mailing">
                    {include file="mailing.tpl"}
                </div>
            {/if}
						{if $type eq "reports"}
								<div id="reports">
									{include file="reports.tpl"}
								</div>
						{/if}

	    {if $type eq "submitted"}
		<div id="submitted">
			{include file="submitted.tpl"}
		</div>
	    {/if}
        </div>
        {include file="foot.tpl"}
        <div id="body">
    </body>

</html>
