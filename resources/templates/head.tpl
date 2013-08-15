<meta http-equiv="X-UA-Compatible" content="IE=9" /> 
<META http-equiv="Content-Style-Type" content="text/css">


<link rel="stylesheet" type="text/css" href="{$uri}/resources/css/amstat.min.css?{$smarty.now}">
<link rel="stylesheet" type="text/css" href="{$uri}/resources/css/main.css?{$smarty.now}">
<link rel="stylesheet" type="text/css" href="{$uri}/resources/css/jquery/jquery-ui.min.css?{$smarty.now}">
<link rel="stylesheet" type="text/css" media="screen" href="{$uri}/resources/css/jqGrid/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{$uri}/resources/css/jquery/jquery-autocomplete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" media="screen"  href="{$uri}/resources/css/jquery/jquery-tablesorter/jquery.tablesorter.css?{$smarty.now}"></link>

<link rel="stylesheet" type="text/css" media="screen" href="{$uri}/resources/css/{$type}.css?{$smarty.now}" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" media="screen" href="{$uri}/resources/css/{$type}-ie.css?{$smarty.now}" />
<![endif]-->

<script type="text/javascript">
    var uri = "{$uri}";
    var server_name = "{$server_name}";
</script>

{literal}
<style type="text/css">
a.tex { background: url("{/literal}{$uri}{literal}/resources/images/tex-icon.png") no-repeat; padding-left: 20px;}
a.pdf { background: url("{/literal}{$uri}{literal}/resources/images/pdf-icon.png") no-repeat; padding-left: 20px;}
</style>
{/literal}


<script type="text/javascript" src="{$uri}/resources/js/jquery.js?{$smarty.now}"></script>
<script type="text/javascript" src="{$uri}/resources/js/jquery-ui.js?{$smarty.now}"></script>
<script type="text/javascript" src="{$uri}/resources/js/jquery.cookie.js?{$smarty.now}"></script>
<script type="text/javascript" src="{$uri}/resources/js/jquery/jquery-autocomplete/jquery.autocomplete.js?{$smarty.now}"></script>
<script type="text/javascript" src="{$uri}/resources/js/jquery/jquery-autocomplete/lib/jquery.bgiframe.min.js?{$smarty.now}"></script>
<script type="text/javascript" src="{$uri}/resources/js/jquery.tablesorter.min.js?{$smarty.now}"></script>
<script type="text/javascript" src="{$uri}/resources/js/jquery.tablesorter.pager.js?{$smarty.now}"></script>

<!-- "Class JS" -->
<script type="text/javascript" src="{$uri}/resources/js/{$type}.js"></script>
  
<script type="text/javascript" src="{$uri}/resources/js/procReturn.min.js?{$smarty.now}"></script>
<script type="text/javascript" src="{$uri}/resources/js/main.js?{$smarty.now}"></script>


<script src="{$uri}/resources/js/grid.locale-en.js" type="text/javascript"></script>
<script src="{$uri}/resources/js/jqGrid/src/grid.base.js" type="text/javascript"></script>
<script src="{$uri}/resources/js/jqGrid/src/grid.tbltogrid.js" type="text/javascript"></script>

<script>
    var IP = "{$smarty.server.REMOTE_ADDR}";
{literal}
    //Set content div min-height to properly place footer
    contentResize = function() {
        $("#content").css('min-height', $(window).height() - $("#top").height() - $("#footer").height());
    }
    $(window).resize(contentResize);
       

    
    $(document).ready( function() {
        contentResize();
    
    
    });


{/literal}
    
    
    {if $user.person_id} var user_id = {$user.person_id};{/if}
    

</script>

<title>JASA/TAS Reviews</title>
<link href="{$uri}/resources/images/icons/book_open.png" type="image/png" rel="icon"/>
