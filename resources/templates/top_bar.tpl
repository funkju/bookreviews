<div id='status_holder'>
  <div id='status_message' class="alert alert-info">
    Best check yo self, you're not..
  </div>
</div>
<div id="top" class='navbar'>
    <nav class='navbar-inner'>
      <div class='container'>
         <a class='brand' href='/'>JASA/TAS Book Reviews</a>
	 <ul class='nav'>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
          {if $type ne "home"}
            <li> <a onclick="showStatus('Loading...',false);" href="{$uri}/home/">Go Home</a></li>
            {if $back}<li><a onclick="showStatus('Loading...',false);" href="{$back.url}">{$back.name}</a></li>{/if}
          {/if}
           <li> {if $user eq 0} <a id="toggleLogin" href="{$uri}/login">Login</a> {else} <a id="toggleLogin" href="{$uri}/svc/logout">Logout</a> {/if}</li>
         </ul>
      </div>
    </nav>
</div>
