  @extends('layouts.app') 
  @section('Content')
	<div class="transformPage">
   
	    <div class="Breadcrumb">
	        <img src="{{ asset('/frontend/images') }}/breadBg.png" alt="" />
	        <div class="container"> 
	            <h2>اسئل معلم</h2>
	            <ul class="list-unstyled">
	                <li><a href="index.html">الرئيسية</a></li>
	                <li>اسئل معلم</li>
	            </ul>
	        </div>
	    </div>
	   
	    <div class="askTeacher">
	    	<div class="container">
	    		<div class="askDiv">
	    			<div class="imgDiv">
	    				<img class="iconImg" src="{{ asset('/frontend/images') }}/042-test.png" />
	    			</div>
	    			<div class="details">
	    				<h2 class="title">اسال معلم</h2>
	    				<div class="desc">
	    					اسال معلمك في شتى الأسئلة وسوف يتم الردعليك فوراً اكتب السؤال والمادة والمهاردة
	    				</div>
	    				<a href="#" class="flaticon-share icon"></a>
	    			</div>
	    		</div>
	    		<form class="sendAsk">
	    			<div class="row">
	    				<div class="col-md-4">
	    					<span class="title">حدد المادة</span>
							<div class="selectStyle">
								<select class="selectmenu" id="selectmenu">
									<option>اختر المادة المتميز فيها</option>
									<option>اختر المادة المتميز فيها</option>
									<option>اختر المادة المتميز فيها</option>
									<option>اختر المادة المتميز فيها</option>
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>
	    				<div class="col-md-4">
	    					<span class="title">حدد نوع المهارة</span>
							<div class="selectStyle">
								<select class="selectmenu" id="selectmenu2">
									<option>حدد المهارة التي تناسبك</option>
									<option>حدد المهارة التي تناسبك</option>
									<option>حدد المهارة التي تناسبك</option>
									<option>حدد المهارة التي تناسبك</option>
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>
	    				<div class="col-md-4">
	    					<span class="title">تفاصيل السؤال</span>
							<div class="selectStyle">
								<input type="text" class="inputStyle" placeholder="اكتب تفاصيل السؤال" />
							</div>
	    				</div>
	    			</div>
	    			<button class="btnForm">ارسل الآن</button>
	    			
	    		</form>
	    	</div>
	    </div>
  @endsection