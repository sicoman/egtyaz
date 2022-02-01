<div class="payment">
		 				<div class="row visaImages">
                         <div class="col-xs-4 ">
		 						<div class="itemVisa" data-val="paypal">
		 							<img style="width: 90px;" src="{{ asset('/frontend/images') }}/paypal.png" alt="">
		 						</div>
		 					</div>
							 <div class="col-xs-8 active">
		 						<div class="itemVisa" style="width: 100%;" data-val="paytabs">
		 							<img style="width: 100%;" src="{{ asset('/frontend/images') }}/paytabsss.png" alt="">
		 						</div>
		 					</div>
		 				</div>
                         <input type="hidden" name="gateway" value="paytabs" />
						<div class="row">
							<div class="col-md-4">
							  <label style="margin-right:25%;float:right;line-height:50px;">الكود أو الكوبون</label>
							</div>
							<div class="col-md-6">
							  <input data-required="true" style="text-align:right;" type="text" name="coupon" class="inputStyle coupon" placeholder="اكتب هنا ان كان لديك كود خصم او كوبون" />
							</div>
							<div class="col-md-2">
							  <button style="padding: 20px;margin-right: 5px;" class="btn btn-success apply_coupon">تطبيق الكوبون</button>
							</div>
						</div> 
						<hr style="	border-top: 1px dashed #8c8b8b;" />
						<div class="row">
							<div class="col-md-4">
							  <label style="margin-right:25%;float:right;line-height:50px;">السعر</label>
							</div>
							<div class="col-md-8">
							  <h4 class="original_price" style="direction:rtl">{{$item->new_price}} {{ trans('currency.'.env('currency_code' , 'SAR')) }} </h4>
							</div>
						</div> 
						<div class="row coupon_price hidden">
							<div class="col-md-6">
							  <label style="margin-right:25%;float:right;line-height:50px;">السعر بعد اضافة الكوبون</label>
							</div>
							<div class="col-md-6">
							  <h4 style="text-align: right;line-height: 53px;font-size: 25px;">{{$item->price}}</h4>
							</div>
						</div>
						<input class="usedCoupon" type="hidden" name="coupon" value="" />
						<input type="hidden" name="type" value="{{ $type ?? 'package' }}" />
						<button class="btnForm">اشترك الآن</button>

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


@section('custom_javascript')	
 <script type="text/javascript">

     $('div.visaImages .itemVisa').click(function(){
        $('input[name="gateway"]').val( $(this).attr('data-val') ) ;
     });

	 $('.apply_coupon').on('click', function(){
		 let couponCode = $('.coupon').val();
		 $.get(`/cp/coupon/${couponCode}`,function(data){
			 if(data.coupon){
				let price = {{$item->new_price}};
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