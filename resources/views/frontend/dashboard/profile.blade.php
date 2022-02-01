
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
		@php
		 	if( !isset($CURRENT_USER) ){
				$CURRENT_USER = Auth::user() ;
			}
		@endphp
	    <div class="profile minHeight600">
    		<div class="row">
    			<div class="col-md-6">
    				<div class="profileStyle">
    					<h2 class="title"><i class="flaticon-user"></i> المعلومات الشخصية</h2>
    					<form method="post" class="profileForm">
						<input type="hidden" name="action" value="update" />
						@csrf
    						<h2 class="titleForm">البريد الإلكتروني</h2>
    						<input type="text" class="inputStyle" name="email" value="{{$CURRENT_USER->email}}" />
    						<h2 class="titleForm">كلمة المرور</h2>
    						<input type="password" class="inputStyle" value="" name="password" />
    						<h2 class="titleForm">الاسم الثلاثي</h2>
    						<input type="text" class="inputStyle" value="{{$CURRENT_USER->name}}" name="name" />
    						<h2 class="titleForm">رقم الهاتف</h2>
							<input class="phone" id="phone" value="{{$CURRENT_USER->mobile}}" name="mobile" type="tel">
    						<h2 class="titleForm">النوع</h2>
    						<div class="selectStyle">
								<select name="gender" class="selectmenu" id="selectmenu1">
									<option @if($CURRENT_USER->gender == "") selected @endif value="">تحديد الجنس</option>
									<option @if($CURRENT_USER->gender == "male") selected @endif value="male">ذكر</option>
									<option @if($CURRENT_USER->gender == "female") selected @endif value="female">أنثى</option>
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
							<div class="clearfix">
								<button type="submit" class="btnForm">حفظ</button>
							</div>
							
    					</form>
    				</div>
    			</div>
    			<div class="col-md-6">
    				<div class="profileStyle">
    					<h2 class="title"><i class="flaticon-tag"></i> الاشتراكات</h2>
    					<div class="profileForm">
    						<h2 class="titleForm">يمكنك الان تجديد اشتراك والاطلاع على التفاصيل</h2>
							@foreach ($myPackages as $package)
   						 	<div class="Bouquet">
    							<div class="clearfix">
    								<h2 class="titleBouquet">{{$package['package']['name']}}</h2>
    								<span class="price hide">{{$package['price']}} {{$package['byPoints'] ? "نقطة" : trans('currency.'.env('currency_code' , 'SAR')) }} </span>
    							</div>
    							<ul class="listDate">
    								<li><span>بداية الاشتراك :</span>{{$package['startTime']}}</li>
    								<li><span>انهاء الاشتراك :</span>{{$package['endTime']}}</li>
    							</ul>
    							<div class="clearfix btns">
    								<a href="{{route("packages")}}" class="btnBouquet">التفاصيل</a>
    								<a href="{{route("buy_package_details",["id" => $package['package']['id']])}}" class="btnBouquet">تجديد</a>
    							</div>
    						</div>	
							@endforeach
							<h4 style="margin-bottom: 20px;text-align: center;text-decoration: underline;text-underline-offset: 10px;"><a href="{{route("packages")}}">للمزيد من الباقات اضغط هنا</a></h4>
    					</div>
    				</div>
    			</div>
    		</div>
	    </div>

  @endsection