  @extends('layouts.app') 
  @section('Content')
		<div class="Breadcrumb">
	        <img src="{{ asset('/frontend/images') }}/breadBg.png" alt="">
	        <div class="container"> 
	            <h2>التسجيل</h2>
	            <ul class="list-unstyled">
	                <li><a href="index.html">الرئيسية</a></li>
	                <li>التسجيل</li>
	            </ul>
	        </div>
	    </div>
 	    <div class="singUp">
	    	<div class="container">
  				@if(1 == 0)
	    		<div class="mask">
	    			<img src="{{ asset('/frontend/images') }}/singUp.png" />
	    		</div>
				@endif
	    		<form method="POST" action="{{ route('register') }}{{ Request::get("referer") ? '?referer='.Request::get("referer") : null }}" class="formSing validate-form">
				@csrf
	    			<div class="row">
	    				<div class="col-md-6">
	    					<span class="titleInput">البريد الإلكتروني</span>
	    					<input name="email" data-required="true" type="email" value="{{old('email')}}" class="inputStyle {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="أضف البريد الإلكتروني" />
							@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif
	    				</div>
	    				<div class="col-md-6">
	    					<span class="titleInput">رقم الموبايل</span>
	    					<input type="number" data-required="true" name="mobile" class="inputStyle" placeholder="أضف رقم الهاتف بشكل صحيح" />
							@if ($errors->has('mobile'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('mobile') }}</strong>
								</span>
							@endif
	    				</div>
					</div>
					<div class="row">	
	    				<div class="col-md-6">
	    					<span class="titleInput">الاسم بالكامل</span>
	    					<input type="text" data-required="true" name="name" class="inputStyle {{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="أضف الاسم بالكامل" />
							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif
	    				</div>
	    				<div class="col-md-6">
	    					<span class="titleInput">كلمة المرور</span>
	    					<input data-required="true" type="password" name="password" class="inputStyle {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="كلمة المرور" />
							@if ($errors->has('password'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
	    				</div>
					</div>
					<div class="row">	
	    				<div class="col-md-6">
	    					<span class="titleInput">النوع</span>
							<div class="selectStyle">
								<select class="selectmenu" name="gender" id="selectmenu">
									<option value="">حدد النوع</option>
									<option value="male">ذكر</option>
									<option value="female">أنثى</option>
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>
	    				<div class="col-md-6">
	    					<span class="titleInput">المرحلة التعليمية</span>
							<div class="selectStyle">
								<select name="stage_id" class="selectmenu" id="selectmenu">
								@foreach ($stages as $stage)
									<option value="{{$stage->id}}">{{$stage->name}}</option>
								@endforeach
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>
	    			</div>
					<label class="checkDiv">
						<span class="text">
							أوافق على شروط الاستخدام وسياسة الخصوصية
						</span>
						<label class="switch">
						  <input data-container=".checkDiv" data-message="يجب الموافقة على الشروط من أجل الاستمرار" data-required="true" type="checkbox" name="agree_on_terms" value="1">
						  <span class="sliderSwitch round"></span>
						</label>
					</label>
					<label class="checkDiv">
						<span class="text">
							ارسال لي كل جديد عبر البريد الالكتروني
						</span>
						<label class="switch">
						  <input data-container=".checkDiv" type="checkbox" name="accept_message" value="1" >
						  <span class="sliderSwitch round"></span>
						</label>
					</label>
	    		
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
	    			
	    			<button class="btnSingUp">سجل الآن</button>
	    		
	    		</form>
	    	</div>
	    </div>
  @endsection
@section('custom_javascript')
<script src=" {{ asset('/frontend/js/validateForm.js') }}"></script>
@stop
@section('custom_css')
<style>
	.checkDiv .invalid-feedback{
		margin: 0px;
	}
</style>
@stop