This form allows you to submit an self-published and web-based resources to be reviews in the JASA or TAS journals. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vehicula turpis malesuada gravida dapibus. Duis ullamcorper lacus lectus, eget cursus elit vehicula a. Nunc faucibus sollicitudin scelerisque. 
<br><br>
<div id="submit-error">
	<b>Sorry! An error occurred submitting your content. Please try again later.</b><br><br>
</div>

<form method='POST' action='/svc/submitContent' id='submit-content'>
	<div>
	<label>Name:</label>
		<input type='text' name='submitter_name' class="default"/>
</div>
<div>
	<label>Email address:</label>
		<input type='text' name='submitter_email' class="default" />
	</div>
<div id='content_title'>
	<label>Content Title:</label>
	<input type="text" name="title" class="default" />
</div>
<div id='content_desc'>
	<label>Content Description:</label>
		<textarea name="description" class="default" rows=8>Enter information similar to what would go in a book's preface.</textarea>
</div>
<div>
	<label>Content URL:</label>
		<input type='text' name='material_url' class="default" value="e.g. http://www.mysite.com/mybook.html">
</div>
<div id='access_inst'>
	<label>Access Instructions:</label>
	<textarea name='access_inst' rows=8 class="default">e.g. Does the site require a username and password?</textarea>
</div>
<div id='author_yes_wrap'>
	<label><input id='author_yes' type='checkbox' name='author_yes' checked=checked /> I am the author of the material being submitted.</label>
</div>
<div style='display: none;' id='author_name_wrap'>
	<label>Author's name:</label>
		<input type='text' name='author_name' class="default" >
	
	</div>
<div style='display: none;' id='author_email_wrap'>
	<label>Author's email:</label>
		<input type='text' name='author_email' class="default">
</div>
<div id='book_yes_wrap'>
	<label><input id='ebook' type='checkbox' name='ebook' checked=checked /> The material being submitted is an e-book.</label>
</div>
<input name='name' id='gotchu' />
<input type='hidden' name='t' />
<div class='button' onClick='submitContent();'>Submit Content</div>
</form>
<div id="submit-thanks">
	<b>Thank you for submitting....Some text informing the submitter about next steps.</b><br><br>
	<div class='button' onClick='resetSubmitContent();'>Submit Another</div>
</div>
