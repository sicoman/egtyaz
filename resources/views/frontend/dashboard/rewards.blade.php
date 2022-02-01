
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
	    <div class=" minHeight600">
	    	<div class="messageCopy">
	    		شكرا لك تم نسخ الرابط بنجاح
	    	</div>  
 			<div class="rewards">
 				<div class="banner">
 					<img src="{{$rewardData['rewards_page_image']}}" />
 				</div>
 				<div class="desc boxStyle">
 					{!!$rewardData['rewards_page_text']!!}
 				</div>
 				<div class="copyStyle boxStyle">
 					<h2 class="title">ابدأ بالربح الآن</h2>
 					<div class="linkDiv">
 						<span class="link" id="textC">{{$referalUrl}}</span>
 						<a class="copy" onclick="copyToClipboard('#textC')"><i class="flaticon-copy"></i> نسخ الرابط</a>
 					</div>
 				</div>
 				<div class="shareStyle clearfix boxStyle">
 					<h2 class="title">شارك الآن</h2>
					 {{--
						<ul class="listShare">
							<li><a href="#" data-sharer="facebook">Facbook <img src="{{ asset('/frontend/images') }}/Facebook.png" /></a></li>
							<li><a href="#" data-sharer="linkedin">linkedin <img src="{{ asset('/frontend/images') }}/LinkedIN.png" /></a></li>
							<li><a href="#" data-sharer="youtube">Youtube <img src="{{ asset('/frontend/images') }}/Youtube.png" /></a></li>
							<li><a href="#" data-sharer="snapchat">Snapchat <img src="{{ asset('/frontend/images') }}/Snapchat.png" /></a></li>
							<li><a href="#" data-sharer="twitter">Twitter <img src="{{ asset('/frontend/images') }}/Twitter.png" /></a></li>
						</ul>
					 --}}
					 <div class="sharethis-inline-share-buttons" data-url="{{ $referalUrl }}" data-title="سجل معنا الان"></div>
 				</div>
				@if($availablePkgs->isEmpty())
					<div class="alert alert-info alert-dismissible show" role="alert">
					 عفوا لا يوجد لديك باقات متاحة للشراء مقابل النقاط  	<strong>من فضلك قم باستخدام الرابط الخاص بك لكسب المزيد من النقاط</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					</div>
				@endif
				<form method="post">
				@csrf
				<div class="points clearfix boxStyle">
 					<div class="sectionRight">
	 					<h2 class="title">لديك من النقاط الآن</h2>
	 					<span class="pointsText">{{$myPoints}}</span>
 					</div>
 					<div class="sectionLeft">
 						<h3 class="subTitle">تجديد العضوية لمدة شهر</h3>
						<div class="selectStyle">
							<select name="package_id" class="selectmenu" id="selectmenu" @if($myPoints == 0) disabled @endif>
								<option>اختار الباقة</option>
                                @foreach ($availablePkgs as $pkg)
								<option value="{{$pkg->id}}">{{$pkg->name}}</option>
                                @endforeach
							</select>
							<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
						</div>
 					</div>
 					
 				</div>
 				<div class="clearfix">
 					<button href="#" class="btnForm" data-toggle="modal" data-target="#modalConfirm2">أشترك الأن</button>
 				</div>
				</form>
 				
 			</div>
    	</div>
	    
  @endsection
@section('custom_javascript')
	<script>
		$(function(){
			function openWindow(url){
				let winWidth = 520;
				let winHeight = 320;
				var winTop = (screen.height / 2) - (winHeight / 2);
				var winLeft = (screen.width / 2) - (winWidth / 2);
       			return window.open(url, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width='+winWidth+',height='+winHeight);
			}

				$('.listShare a').on('click', function(){
					console.log($(this).attr('data-sharer'));
					let title = "المكافئات";
					let desc = $('.desc').text();
					let url = "{{$referalUrl}}";
					let image = $('.banner').find('img').attr('src');
					switch($(this).attr('data-sharer')){
					  case "facebook":
					  return openWindow('https://www.facebook.com/sharer.php?u=' + url);
					  break;
					  case "linkedin":
					  return openWindow('https://www.linkedin.com/sharing/share-offsite/?' + url);
					  break;
					  case "twitter":
					  return openWindow('https://twitter.com/intent/tweet?url=' + url);
					  break;
					}
					return false;
				});
		});
	</script>	
@stop

