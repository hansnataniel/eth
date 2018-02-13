<?php
	use App\Models\Setting;

	$setting = Setting::first();
?>

<html>
<head>
	<title>
		ETH Miningpool | @yield('title')
	</title>

	<link rel="shortcut icon" href="{{URL::to('img/front/eth_favicon.png')}}" />

	{{-- META TAGS --}}
	<meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keyword')">
	<meta name="robot" content="INDEX,FOLLOW">
	<meta name="copyright" content="ETH Mining pool">
	<meta name="language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{!!HTML::style('css/front/style.css')!!}

	@yield('head_additional')
</head>
<body>
	<div class="window-width"></div>
	<div class="window-height"></div>
	
	@if ($request->session()->has('success-message'))
		<div class="blur-container">
			<div class="mid">
				<div class='message success-message'>
					{!!$request->session()->get('success-message')!!}
					<div class="message-close"></div>
				</div>
			</div>
		</div>
	@endif

	{!! HTML::image('img/front/logo_gradient.png', '', ['class'=>'logo-gradient']) !!}
	<a href="{{ url('/') }}" class="logo">
		{!! HTML::image('img/front/eth_logo.png', 'ETH Mining Pool', ['title'=>'ETH Mining Pool']) !!}
	</a>

	@if (!Auth::check())
		<div class="links-item login-switch login-mob" href="{{ url('login') }}">
			LOGIN

			<div class="links-child login">
				@if ($request->session()->has('login-error'))
					<div class="login-error active">
				@else
					<div class="login-error">
				@endif
					@if ($request->session()->has('login-error'))
						<div class="login-error-item links-child-item login">
							{{ $request->session()->get('login-error') }}
						</div>
					@endif
				</div>

				{!! Form::open(['url'=>URL::to('login'), 'method'=>'POST']) !!}
		    		{!! Form::email('email', null, ['class'=>'links-child-text links-child-item login', 'placeholder'=>'Email']) !!}
		    		{!! Form::password('password', ['class'=>'links-child-text links-child-item login', 'placeholder'=>'Password']) !!}
		    		{!! Form::submit('LOGIN', ['class'=>'links-item-submit links-child-item login']) !!}
				{!! Form::close() !!}

				<a class="links-item-forgot links-child-item login" href="{{ url('password/remind') }}">
					Forgot Password?
				</a>
			</div>
		</div>
	@endif

	<div class="menu-switch">
		<div class="menu-switch-line line1"></div>
		<div class="menu-switch-line line2"></div>
		<div class="menu-switch-line line3"></div>
	</div>

	<nav class="menu-mob">
		<a class="mob-child" href="{{ url('/') }}">
        	HOME
        </a>
        @if (!Auth::check())
            <a class="mob-child" href="{{ url('register') }}">
            	CREATE ACCOUNT
            </a>
        @else
        	<a class="mob-child" href="{{ url('dashboard') }}">
            	DASHBOARD
            </a>
        	<a class="mob-child" href="{{ url('subscription') }}">
            	CLOUD MINING
            </a>
	        <a class="mob-child" href="{{ url('payment') }}">
	        	PAYMENT CONFIRMATION
	        </a>
        @endif
        <a class="mob-child" href="{{ url('contact-us') }}">
        	CONTACT US
        </a>
        @if (Auth::check())
        	<a class="mob-child" href="{{ url('withdrawal') }}">
	        	WITHDRAWAL
	        </a>
	        <a class="mob-child" href="{{ url('my-profile') }}">
	        	EDIT PROFILE
	        </a>
	        <a class="mob-child" href="{{ url('logout') }}">
	        	LOGOUT
	        </a>
        @endif
	</nav>

	{{-- @if (Route::has('login')) --}}
        {{-- @if (Auth::check())
        	<div class="top-right-hello">
        		Hello <strong>{{Auth::user()->name}}</strong>, <br>welcome to ETH - Mining Pool
        	</div>
        @endif --}}
        <nav class="top-right links">
            <a class="links-item" href="{{ url('/') }}">
            	HOME
            </a>
	        @if (!Auth::check())
	            <a class="links-item" href="{{ url('register') }}">
	            	CREATE ACCOUNT
	            </a>
	        @else
	            <a class="links-item" href="{{ url('dashboard') }}">
	            	DASHBOARD
	            </a>
	        	<a class="links-item" href="{{ url('subscription') }}">
	            	CLOUD MINING
	            </a>
	            <a class="links-item" href="{{ url('payment') }}">
	            	PAYMENT CONFIRMATION
	            </a>
	        @endif
            <a class="links-item" href="{{ url('contact-us') }}">
            	CONTACT US
            </a>
	        @if (Auth::check())
	        	<div class="links-item profile-switch">
	            	MY PROFILE

	            	<div class="links-child profile" style="padding: 0px;">
	            		<a class="profile-item links-child-item" href="{{url('withdrawal')}}">
	            			WITHDRAWAL
	            		</a>
	            		<a class="profile-item links-child-item" href="{{url('my-profile')}}">
	            			EDIT PROFILE
	            		</a>
	            		<a class="profile-item links-child-item" href="{{url('logout')}}">
	            			LOGOUT
	            		</a>
	            	</div>
	            </div>
	            {{-- <a class="links-item" href="{{ url('logout') }}">
	            	LOGOUT
	            </a> --}}
	        @else
	        	<div class="links-item login-switch" href="{{ url('login') }}">
	            	LOGIN

	            	<div class="links-child login">
        				@if ($request->session()->has('login-error'))
	            			<div class="login-error active">
        				@else
	            			<div class="login-error">
        				@endif
	        				@if ($request->session()->has('login-error'))
	            				<div class="login-error-item links-child-item login">
	            					{{ $request->session()->get('login-error') }}
	            				</div>
            				@endif
            			</div>

	            		{!! Form::open(['url'=>URL::to('login'), 'method'=>'POST']) !!}
		            		{!! Form::email('email', null, ['class'=>'links-child-text links-child-item login', 'placeholder'=>'Email']) !!}
		            		{!! Form::password('password', ['class'=>'links-child-text links-child-item login', 'placeholder'=>'Password']) !!}
		            		{!! Form::submit('LOGIN', ['class'=>'links-item-submit links-child-item login']) !!}
	            		{!! Form::close() !!}

	            		<a class="links-item-forgot links-child-item login" href="{{ url('password/remind') }}">
	            			Forgot Password?
	            		</a>
	            	</div>
	            </div>
	        @endif
        </nav>
    {{-- @endif --}}

	<div class="container">
		@yield('content')
	</div>

	<footer class="footer-container">
		<div class="footer-icon-container">
			<a href="http://facebook.com/{{$setting->facebook}}" target="_blank" class="footer-icon-item facebook" title="Facebook">
			</a>
			<a href="http://twitter.com/{{$setting->twitter}}" target="_blank" class="footer-icon-item twitter" title="Twitter">
			</a>
			<a href="http://instagram.com/{{$setting->instagram}}" target="_blank" class="footer-icon-item instagram" title="Instagram">
			</a>
		</div>
		<span>
			© 2017 EthMiningPool.net. Website developed by <a href="http://www.creids.net">Creids</a>
		</span>
	</footer>

	<div class="msg-ctn">
		<div class="mid">
			<div class="msg-content">
				@yield('msg-content')
			</div>
		</div>
	</div>

	{!!HTML::script('js/jquery-1.8.3.min.js')!!}
	{!!HTML::script('js/jquery.easing.1.3.js')!!}

	{!!HTML::script('js/front/master.js')!!}

	<script>
		$(document).ready(function(){
			$('.message-close').click(function(){
				$('.blur-container').fadeOut();
			});
		});
	</script>

	@yield('js_additional')
</body>
</html>