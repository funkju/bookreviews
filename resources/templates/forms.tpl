<div id="tabs">
	<ul>
		<li class='plain'>Forms For...</li>
		<li><a href="#for-submit">Content Submission</a></li>
		<li><a href="#for-reviewers">Reviewers</a></li>
		<li><a href="#for-aes">Associate Editors</a></li>
		<li class='plain2'> -- Or -- </li>
		<li><a href="#for-login">Login</a></li>
	</ul>
	<div id="for-submit">
		{include file='self_submit.tpl'}
	</div>
	<div id="for-reviewers">
		<ul>
		    <li><a href="{$uri}/resources/forms/review_template.tex">LaTex Review Template</a>
		    <li><a href="{$uri}/resources/forms/jasa_copyright_transfer.pdf">JASA Copyright Transfer</a>
		    <li><a href="{$uri}/resources/forms/jasa_disclosure.pdf">JASA Disclosure</a>
		    <li><a href="{$uri}/resources/forms/tas_copyright_transfer.pdf">TAS Copyright Transfer</a>
		    <li><a href="{$uri}/resources/forms/tas_disclosure.pdf">TAS Disclosure</a>
		    <li><a href="{$uri}/resources/forms/guidelines_for_jasa_tas_book_reviewers.pdf">Guidelines For JASA/TAS Book Reviewers</a>
		</ul>
	</div>
	<div id="for-aes">
		<ul>
		   <!-- <li><a href="{$uri}/resources/forms/new_ae_welcome.pdf">New AE Welcome</a>
		     <li><a href="{$uri}/resources/forms/ae_areas_interest.pdf">AE Areas of Interest</a>-->
		    <li><a href="{$uri}/resources/forms/ae_process_guide.pdf">AE Process Guide</a> 
		</ul>
	</div>
	<div id="for-login">
		<form name="login" action="{$uri}/svc/doLogin" method="post" >
            <input type="text" name="username" id="username" value="username" class="default">
            <input type="password" name="password" id="password"  value="password" class="default">
            <div class="button" onClick="doLogin()">Login</div>
		</form>
	</div>
</div>
