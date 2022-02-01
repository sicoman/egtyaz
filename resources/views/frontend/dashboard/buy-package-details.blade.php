
@extends('layouts.dashboard') 
@section('Content')

@include('frontend.includes.topPartDashboard')
@include('frontend.includes.breadCrumbDashboard')	  
@php
 if($package->price){
    $route = route("buy_package", ["id" => $package->id,"processor" => "paypal"]);  
 }else{
    $route = route("buy_package", ["id" => $package->id,"processor" => "free"]);  
 }

@endphp
	    
	    <div class="askTeacher courseStyle paymentStyle minHeight600">
		  <form action="{{$route}}">
			<div class="row">
				<div class="col-md-6">
					<div class="courseItem clearfix">
						<div class="details">
								<span class="price">سعر الباقة  {{$package->new_price}}</span>
								<h2 class="title">{{$package->name}}</h2>
								<div class="desc">
							     	{{$package->description}}
								</div>
							</div>
					</div>
				</div>
				<div class="col-md-6">
					@include('frontend.dashboard.global.buy' , [ 'item' => $package ] ) 
				</div>
			</div>
	      </form>		
    	</div>

<style>
.courseStyle .courseItem .btnForm{
	position: relative;
}

.original_price{	
	text-align: right;line-height: 53px;font-size: 25px;float:right;
}

.old_price{
	position:relative;white-space: nowrap;
}

.old_price:after {
    border-top: 2px solid #000;
    position: absolute;
    content: "";
    right: 0;
    top:50%;
    left: 0;
}

.green{
	color: green;
}


</style>

@endsection
@section('custom_javascript')	
 <script type="text/javascript">
	 $('.apply_coupon').on('click', function(){
		 let couponCode = $('.coupon').val();
		 $.get(`/cp/coupon/${couponCode}`,function(data){
			 if(data.coupon){
				let price = {{$package->new_price}};
				$('.original_price').addClass('old_price'); 
				if(data.coupon.type == "fixed"){  
					price -= parseFloat(data.coupon.amount);
				}else{
					let ori = parseFloat(price);
					price = ori * (1 - parseInt(data.coupon.amount) / 100);		
				}
				$('.usedCoupon').attr('value', couponCode);
				$('.coupon_price h4').text(price).addClass('green');
				$('.coupon_price').removeClass('hidden');
			 }else{
					$.toast({
						heading: 'كود الخصم',
						text: 'عفوا كود الخصم او الكوبون غير متاح او لقد انتهت صلاحيته',
						showHideTransition: 'slide',
						icon: 'error',
						position : 'top-right',
						textAlign: 'right',
						hideAfter: 2500
					});
			 }
		 });
		 return false;
	 });
 </script>
@endsection