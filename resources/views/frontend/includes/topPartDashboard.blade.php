	<div class="cpanelStyle activeMenu">
		<div class="bgOpacity"></div>
		@php
		 	if( !isset($CURRENT_USER) ){
				$CURRENT_USER = Auth::user() ;

			}
             $stage= \App\Models\Taxonomy::where('id',$CURRENT_USER->stage_id)->first();

			$notificationNum = $CURRENT_USER->unreadNotifications()->count() ;
		@endphp
	    <div class="headProfile clearfix">
	    	<center>
		    	<ul class="settings clearfix">
		    		<li class="parentLi">
		    			<a class="iconStyle"><i class="flaticon-settings-1"></i></a>
		    			<ul class="listSettings">
		    				<li><a href="{{route("profile")}}"><i class="flaticon-user"></i> الملف الشخصي</a></li>
		    				<li><a href="{{route("logout")}}"><i class="flaticon-sign-out"></i> تسجيل الخروج</a></li>
		    			</ul>
		    		</li>
		    		<li class="parentLi"><a href="{{route("notifications")}}" class="iconStyle"><i class="flaticon-notification"></i>@if($notificationNum) <span class="numb">{{$notificationNum}}</span> @endif</a></li>
		    		<li class="parentLi"><a href="{{route("wishlist")}}" class="iconStyle"><i class="flaticon-favourite"></i></a></li>
		    	</ul>
	    	</center>

	    	<div class="detailsUser">
	    		<div class="uploadImage">
	    			<img id="avatar" src="{{$CURRENT_USER->avatar ?? ''}}" />
					<form class="avatarForm" method="post" enctype="multipart/form-data">
					@csrf
						<label class="fileImage">
							<i class="fa fa-camera"></i>
							<input data-url="{{route("updateAvatar")}}" name="avatar" class="userAvatar" type="file" />
						</label>
					</form>
	    		</div>
	    		<h2 class="titleUser">مرحباً بك: {{$CURRENT_USER->name}}</h2>
	    		<span class="desc">الصف ال{{$stage->name}}</span>
	    	</div>

	    </div>
