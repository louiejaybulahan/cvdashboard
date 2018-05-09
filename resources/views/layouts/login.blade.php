<!-- # ---------------------------------------------------
# kickassGFX.net - Best Graphic Source - Free Themes, Scripts & Plugins 
# ---------------------------------------------------
# This file has been downloaded from KickassGFX.net
# Homepage: http://www.kickassgfx.net/
# ---------------------------------------------------
# You'll find your Updates everyday at KickassGFX.net
# ---------------------------------------------------
# http://www.kickassgfx.net/
# --------------------------------------------------- --><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>User Login</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
<script type="text/javascript" src="{{ asset('js/plugins/jquery-1.7.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery-ui-1.8.16.custom.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.loginform button').hover(function(){
		$(this).stop().switchClass('default','hover');
	},function(){
		$(this).stop().switchClass('hover','default');
	});
	
	$('#login').submit(function(){
		var u = jQuery(this).find('#username');
		if(u.val() == '') {
			jQuery('.loginerror').slideDown();
			u.focus();
			return false;	
		}
	});
	
	$('#username').keypress(function(){
		jQuery('.loginerror').slideUp();
	});
});
</script>
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<meta charset="UTF-8"></head>

<body class="login">

<div class="loginbox radius3">
	<div class="loginboxinner radius3">
    	<div class="loginheader">
    		<h1 class="bebas">Sign In</h1>
        	<div class="logo"><!-- img src="./images/starlight_admin_template_logo.png" alt="" / --></div>
    	</div><!--loginheader-->
        
        <div class="loginform">
        	<div class="loginerror"><p>Invalid username or password</p></div>
            @yield('content')
        </div><!--loginform-->
    </div><!--loginboxinner-->
</div><!--loginbox-->

</body>
</html>
