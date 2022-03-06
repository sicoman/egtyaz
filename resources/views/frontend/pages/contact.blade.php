
@extends('layouts.app')
@section('Content')

 <div class="askTeacher support minHeight600">

    <div class="container">
    <br />
    <ul class="breadCpanel">
	    	<li><a href="/">الرئيسية</a></li>
	    	<li>أتصل بنا</li>
    </ul>
    <br />
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="/frontend/images/customer-service.png">
    			</div>
    			<div class="details">
    				<h2 class="title">مرحباً بك </h2>
    				<div class="desc">
                            {{ $welcome ?? 'error' }}
                    </div>
    				<div class="desc">
						966558260430+
                    </div>
    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
    			</div>
    		</div>
    		<form action="{{ route('postContact') }}" method="post" class="sendAsk">
                    @csrf
	    			<div class="row">
	    				<div class="col-md-4">
	    					<div class="clearfix">
		    					<span class="title">الاسم</span>
								<input type="text" required class="inputStyle" name="name" placeholder="غدير فيصل عبد العزيز  سلمان">
								<input type="email" required class="inputStyle" name="email" placeholder="User@google.com">
								<input type="mobile" required class="inputStyle" name="mobile" placeholder="9665xxxxxxx">
		    					<input type="hidden" type="contact" />
	    					</div>
	    				</div>
	    				<div class="col-md-8">
	    					<span class="title">تفاصيل رسالة</span>
							<textarea required name="message" placeholder="اكتب تفاصيل الرسالة" class="inputStyle"></textarea>
	    				</div>
	    			</div>
	    			<button type="submit" class="btnForm">ارسل الآن</button>

	    		</form>
        </div>

</div>
@stop
