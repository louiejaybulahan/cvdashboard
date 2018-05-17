<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>@yield('title')</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="css/ie7.css"/>
<![endif]-->
@yield('cssExtention')
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<meta charset="UTF-8"></head>
<body class="loggedin">   
    <input type="hidden" id="fieldToken" name="fieldToken" value="{{ csrf_token() }}">
	<!-- START OF HEADER -->
	<div class="header radius3">
    	<div class="headerinner">        	
            <a href="" style="text-decoration: none;color:#FFFFFF;font-size:32px;"><!-- img src="./images/starlight_admin_template_logo_small.png" alt="" / --><div style="min-height:36px;">Pantawid Pamilya Dashboard</div></a>                    
            <div class="headright">
            	<div class="headercolumn">&nbsp;</div>
            	<div id="searchPanel" class="headercolumn">
                	<div class="searchbox">
                        <form action="" method="post">
                            <input type="text" id="keyword" name="keyword" class="radius2" value="Search here" /> 
                        </form>
                    </div><!--searchbox-->
                </div><!--headercolumn-->
            	<!-- div id="notiPanel" class="headercolumn">
                    <div class="notiwrapper">
                        <a href="./ajax/messages.php.html" class="notialert radius2">5</a>
                        <div class="notibox">
                            <ul class="tabmenu">
                                <li class="current"><a href="./ajax/messages.php.html" class="msg">Messages (2)</a></li>
                                <li><a href="./ajax/activities.php.html" class="act">Recent Activity (3)</a></li>
                            </ul>
                            <br clear="all" />
                            <div class="loader"><img src="./images/loaders/loader3.gif" alt="Loading Icon" /> Loading...</div>
                            <div class="noticontent"></div>
                        </div>
                    </div>
                </div --><!--headercolumn-->
                <div id="userPanel" class="headercolumn">
                    <a href="" class="userinfo radius2">
                        <img src="./images/avatar.png" alt="" class="radius2" />
                        <span>
                            <strong>
                                @if(Auth::check())
                                    {{ Auth::user()->fname.' '.Auth::user()->lname }}
                                @else 
                                    Guest
                                @endif
                            </strong>
                        </span>
                    </a>
                    <div class="userdrop">
                        <ul>
                            <!-- li><a href="">Profile</a></li>
                            <li><a href="">Account Settings</a></li -->
                            @if(Auth::check())
                                <li><a href="{{ url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li> 
                            @else
                                <li><a href="{{ url('login') }}">Login</a></li>     
                            @endif
                        </ul>
                        @if(Auth::check())
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @endif
                    </div><!--userdrop-->
                </div><!--headercolumn-->
            </div><!--headright-->
        
        </div><!--headerinner-->
	</div><!--header-->
    <!-- END OF HEADER -->
    
    <!-- START OF MAIN CONTENT  mainwrapper lefticon -->
    <div class="mainwrapper">
     	<div class="mainwrapperinner">         	
				<div class="mainleft">
					<div class="mainleftinner">
					
						<div class="leftmenu">
							<ul>	
                                @if(Auth::check())
                                    <li><a href="{{ route('users.index') }}" class="users"><span>Users</span></a></li>
                                @endif
								<li>
									<a href="#" class="dashboard menudrop active"><span>Turn Out</span></a>
								    <ul style="display: block;">
                                            <li><a href="{{ route('site.index') }}" class="dashboard"><span>Dashboard</span></a></li>
                                        @if(Auth::check())										
                                            <li><a href="{{ route('generateturnout.index') }}" class="widgets"><span>Generate Turnout</span></a></li>										
                                            <li><a href="{{ route('listturnout.index') }}" class="widgets"><span>List</span></a></li>								
                                            <li><a href="{{ route('uploadfile.index') }}" class="widgets"><span>Import Files</span></a></li>										
                                        @endif
									</ul>	
								</li>
								
								<li style="border-top: 1px solid rgb(221, 221, 221);">
									<a href="#" class="dashboard menudrop active"><span>OBTR</span></a>
								   <ul style="display: block;">									
										<li><a href="{{ route('obtrfileupload.index') }}" class="charts"><span>Accomplishment and Monitoring</span></a></li>
									</ul>	
								</li>
                                <li style="border-top: 1px solid rgb(221, 221, 221);">
                                    <a href="{{ route('backgroundprocess.index') }}" class="widgets"><span>Background Process</span></a>
                                </li>							
							</ul>
								
						</div><!--leftmenu toggle-->
						<div id="togglemenuleft" class=""><a></a></div>
					</div><!--mainleftinner-->
				</div><!--mainleft-->

				<div class="maincontent @yield('optLayout')">
					<div class="maincontentinner">
							<ul class="maintabmenu">
                                @if(isset($header) AND is_array($header))  
                                    @foreach($header as $m)
                                        <li class="{{ $m['selected'] }}"><a href="{{ $m['url'] }}">{{ $m['title'] }}</a></li>
                                    @endforeach
                                @else
                                    <li class="current"><a href="#">@yield('title') </a></li>
                                @endif
							</ul><!--maintabmenu-->		
              
							<div class="content">                              
								@yield('content')
							</div><!--content-->	
					</div><!--maincontentinner-->					
					<div class="footer"><p>Developed by : Louie Jay Bulahan &copy; 2018. Designed by: <a href="">ThemePixels.com</a></p></div><!--footer-->					
				</div><!--maincontent-->
				@yield('right')
     	</div><!--mainwrapperinner-->
    </div><!--mainwrapper-->
	<!-- END OF MAIN CONTENT -->    
<script type="text/javascript" src="{{ asset('js/plugins/jquery-1.7.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery-ui-1.8.16.custom.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/custom/general.js') }}"></script>
<script type="text/javascript">
    $ = jQuery; 
    var tokenName = '_token';
    var tokenValue = '{{ csrf_token() }}'; 
    function jsToken(val){
        if (val != '' && val != 'undefined' && val != null) {
            tokenValue = val;
            $('#fieldToken').val(tokenValue);
            return tokenValue;
        } else {
            if (tokenValue != '' && tokenValue != 'undefined' && tokenValue != null) return tokenValue;
            else return $('#fieldToken').val();
        }
    }		
    jQuery(window).ready(function(){
        jQuery('.theme').hide();
    });
    
</script>
@yield('jsExtention')
</body>
</html>
