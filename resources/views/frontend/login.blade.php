
  @extends('layouts.app') 
  @section('Content')
  	    <div class="Breadcrumb">
	        <img src="{{ asset('/frontend/images') }}/breadBg.png" alt="" />
	        <div class="container"> 
	            <h2>تسجيل الدخول</h2>
	            <ul class="list-unstyled">
	                <li><a href="{{ route('home') }}">الرئيسية</a></li>
	                <li>تسجيل الدخول</li>
	            </ul>
	        </div>
	    </div>
		    <div class="login">
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-md-6 hidden-sm hidden-xs">
			    		<div class="mask">
			    			<img src="{{ asset('/frontend/images') }}/4163907.png" />
			    			<h2 class="titleMask">تسجيل الدخول</h2>
			    		</div>
	    			</div>
	    			<div class="col-md-6">
			    		<form method="POST" action="{{ route('login') }}" class="formSing validate-form">
						@csrf
			    			<div class="row">
			    				<div class="col-md-12">
									@if ($errors->has('email'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
									@endif
			    					<span class="titleInput">البريد الإلكتروني</span>
			    					<input data-required="true" type="email" name="email" class="inputStyle" placeholder="أضف البريد الإلكتروني" />
			    				</div>
			    				<div class="col-md-12">
			    					<span class="titleInput">كلمة المرور</span>
			    					<input data-required="true" type="password" name="password" class="inputStyle" placeholder="كلمة المرور" />
			    				</div>
			    			</div>
			    			<div class="socialLogin">
			    				<center class="bgT">
			    					<h2 class="titleSocial">أو عن طريق التواصل الاجتماعي</h2>
			    				</center>
			    				<center>
			    					<ul class="listSocial clearfix">
			    						<li><a href="{{ url('/auth/facebook') }}" class="fa fa-facebook"></a></li>
			    						<li><a href="{{ url('/auth/google') }}" class="fa fa-google-plus"></a></li>
			    						<li><a href="{{ url('/auth/twitter') }}" class="fa fa-twitter"></a></li>
			    					</ul>
		    					</center>
			    			</div>
			    			
			    			<button class="btnSingUp">تسجيل الدخول</button>
			    			<center>
			    				<a href="#" class="singBtn">انشاء عضوية جديدة</a>
								<p style="margin-top: 30px;"><a href="{{route("password.request")}}">اذا كنت قد نسيت كلمة المرور الخاصة بك اضغط هنا لاستعادتها</a></p>
			    			</center>
			    		</form>
			    
	    			</div>
	    		</div>


	    	</div>
	    </div>
	    
  @endsection
@section('custom_javascript')
<script src=" {{ asset('/frontend/js/validateForm.js') }}"></script>
@stop