@extends('layouts.app')

@section('Content')
<div class="transformPage">
	
 <div class="askTeacher support minHeight600"> 

    <div class="container">
    <br>
    <ul class="breadCpanel">
	    	<li><a href="/">الرئيسية</a></li>
	    	<li>استرجاع كلمة المرور</li>
    </ul>
    <br>
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="/frontend/images/customer-service.png">
    			</div>
    			<div class="details">
    				<h2 class="title">استرجاع كلمة المرور</h2>
    				<div class="desc">
                            من فضلك قم بادخال البريد الالكترونى الخاص بك
                    </div>
    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
    			</div>
    		</div>
    		<form method="POST" action="{{ route('password.update') }}" class="sendAsk" _lpchecked="1">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
                    <div class="row">
	    				<div class="col-md-12">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
	    					<div class="clearfix">
		    					<span class="title">البريد الالكترونى</span>
                                <input id="email" type="email" class="inputStyle @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
	    					</div>
	    					<div class="clearfix">
		    					<span class="title">كلمة المرور</span>
                                <input id="password" type="password" class="inputStyle @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">	    	
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror			
                            </div>
	    					<div class="clearfix">
		    					<span class="title">تأكيد كلمة المرور</span>
                                <input id="password-confirm" type="password" class="inputStyle" name="password_confirmation" required autocomplete="new-password">
	    					</div>
	    				</div>
	    			</div>
	    			<button type="submit" class="btnForm">ارسل الآن</button>
	    			
	    		</form>
        </div>
        
</div>  	
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
