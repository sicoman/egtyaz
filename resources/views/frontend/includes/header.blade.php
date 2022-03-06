    @include('frontend.includes.head')
    <body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQJ2X6D"
					  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div class="menuMobile">

		<div class="BgClose"></div>
		<div class="menuContent">
			<div class="headMenu">
				<i class="fa fa-close closeX"></i>
			</div>
			<ul class="menuRes">
    			<li><a href="{{route('home')}}">الرئيسية</a></li>
    			<li><a href="{{route('bank')}}">بنك الأسئلة</a></li>
    			<li><a href="{{route('exam')}}">اختبار تجريبي</a></li>
    			<li><a href="{{route('askTeacher')}}">اسئل معلم </a></li>
    			{{-- <li><a href="{{route('askTeacher')}}">الدعم الفني</a></li>--}}
    			<li><a href="{{route('contact')}}">اتصل بنا</a></li>
			</ul>
		</div>
	</div>
    <header class="header">
    	<div class="topBar">
    		<div class="container">
    			@if(!Auth::guard('web')->check())
					<a href="{{route('register')}}">تسجيل جديد</a>
					<a href="{{route('login')}}">تسجيل الدخول</a>
				@else
					<a href="{{route('logout')}}">تسجيل الخروج</a>
					<a href="{{route('profile')}}">بياناتى</a>
				@endif
    		</div>
    	</div>
    	<div class="container">
    		<a href="{{route('home')}}" class="logo"><img src="{{ asset('/frontend/images/') }}/logo.png" /></a>
    		<ul class="menuHome">
    			<li><a href="{{route('home')}}">الرئيسية</a></li>
    			<li><a href="{{route('bank')}}">بنك الأسئلة</a></li>
    			<li><a href="{{route('exam')}}">اختبار تجريبي</a></li>
    			<li><a href="{{route('askTeacher')}}">اسئل معلم </a></li>
    			<li><a href="{{route('contact')}}">اتصل بنا</a></li>
    		</ul>
			<i class="iconMenu"></i>
    	</div>
    </header>
    <div class="heightHeader"></div>
