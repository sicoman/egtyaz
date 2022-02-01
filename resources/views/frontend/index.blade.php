
  @extends('layouts.app') 
  @section('Content')
	<div class="transformPage">
        <div class="slider">
            <ul id="slider" class="the-slider-inner">
				@if( !empty($first_slider) )
					@foreach( $first_slider as $slice )
						<li>
							<img src="{{ $slice->photo ?? '' }}" class="bg" alt="...">
							<div class="item clearfix active">
							<div class="details">
								<div class="title animated fadeInRight fade-In-Right">
										{{ $slice->title ?? '' }}
								</div>
								<div class="desc fadeInDown fade-In-Down">
										{{ strip_tags($slice->description) ?? '' }}
								</div>
							</div>
							</div>
						</li>
					@endforeach
				@endif
            </ul>
            <ul class="list-unstyled the-slider-control">
                <li><span id="slider-next"></span></li>
                <li><span id="slider-prev"></span></li>
            </ul> <!-- END the slider control-->            
            
        </div>
	    
	    <div class="detailsHome">
	    	<div class="container">
		    	<div class="areYouStudent wow fadeInUp">
		    		<img src="{{ asset('/frontend/images/') }}/012-graduate.png" />
		    		<div class="details">
		    			<h2 class="title">{{ $are_you_student_title ??  'هل أنت طالب' }}</h2>
		    			<div class="desc">
							{{ $are_you_student_desc ??  'هل أنت طالب' }}
		    			</div>
		    			<a href="{{ route('register') }}">اشترك الآن</a>
		    		</div>
		    	</div>
		    	<div class="vidDiv">
	            	<div class="sliderVid owl-carousel owl-theme">
						@if( !empty( $second_slider ) )
							@foreach( $second_slider as $slice )
								<div class="item">
									<div class="row">
										<div class="col-md-6">
												<div class="vidx">
												 {{-- 
													<img src="{{ $slice->photo ?? '' }}" />
													@if( isset($slice->file[5]) )
													<a href="{{ $slice->file ?? '' }}" class="openVid" data-toggle="modal" data-target="#video"><i class="fa fa-play"></i></a>
													@endif
												--}}
												{!! embed_code($slice->file , 'style="width:100%;height:300px;"') !!}
												</div>
										</div>
										<div class="col-md-6">
											<div class="details">
												<h2 class="title">{{ $slice->title ?? '' }}</h2>
												<div class="desc">
													{{ html_entity_decode ( strip_tags($slice->description) ) ?? '' }}
												</div>
												<a href="{{ route('register') }}"class="wow fadeInLeft">انضم الينا</a>
											</div>
										</div>
									</div>
								</div>  
							@endforeach
						@endif
		            </div>
		    	</div>
	    	</div>
	    </div>
	    
	    <div class="features">
	    	<div class="container">
	    		<div class="row">
						@if( !empty( $six_pages ) ) @php $tim = 0.3 ; @endphp
							@foreach( $six_pages as $slice )
								@php $tim = (int)$tim + 0.3 ; @endphp
								<div class="col-md-4">
									<div class="item wow fadeInUp" data-wow-duration="{{$tim}}s">
										<img src="{{ $slice->photo ?? '' }}" />
										<h2 class="title">{{ $slice->title ?? '' }}</h2>
										<div class="desc">
											{{ strip_tags($slice->description) ?? '' }}
										</div>
									</div>
								</div>
							@endforeach
						@endif
		    	
	    		</div>
	    		</div>
	   		</div>
	
	    </div>
	    
	    <div class="counts">
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-md-3">
	    				<div class="item">
	    					<h2 class="title">بنك الأسئلة</h2>
	    					<span class="numb" data-from="0" data-to="{{ $counts['questions'] }}" data-speed="3000" data-refresh-interval="50">{{ $counts['questions'] }}</span>
	    				</div>
	    			</div>
	    			<div class="col-md-3">
	    				<div class="item">
	    					<h2 class="title">النماذج</h2>
	    					<span class="numb" data-from="0" data-to="{{ $counts['forms'] }}" data-speed="3000" data-refresh-interval="50">{{ $counts['forms'] }}</span>
	    				</div>
	    			</div>
	    			<div class="col-md-3">
	    				<div class="item">
	    					<h2 class="title">الاختبارات</h2>
	    					<span class="numb" data-from="0" data-to="{{ $counts['exams'] }}" data-speed="3000" data-refresh-interval="50">{{ $counts['exams'] }}</span>
	    				</div>
	    			</div>
	    			<div class="col-md-3">
	    				<div class="item">
	    					<h2 class="title">الفيديوهات</h2>
	    					<span class="numb" data-from="0" data-to="{{ $counts['videos'] }}" data-speed="3000" data-refresh-interval="50">{{ $counts['videos'] }}</span>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	    
	    <div class="services">
	    	<div class="container">
	    		<h2 class="title">ماذا نقدم</h2>
	    		<div class="row">
						@if( !empty( $what_we_produce ) )
							@php 
								$i = -1 ;
								$svgs = [
									'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="357" height="384" viewBox="0 0 357 384">
							  <defs>
							    <linearGradient id="linear-gradient3" x1="0.5" x2="0.067" y2="0.833" gradientUnits="objectBoundingBox">
							      <stop offset="0" stop-color="#fff"></stop>
							      <stop offset="1" stop-color="#c8e7ff"></stop>
							    </linearGradient>
							  </defs>
							  <path id="Path_775" data-name="Path 775" d="M10,0H347a10,10,0,0,1,10,10V374a10,10,0,0,1-10,10H10A10,10,0,0,1,0,374V10A10,10,0,0,1,10,0Z" fill="url(#linear-gradient3)"></path>
							</svg>' ,
								'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="357" height="384" viewBox="0 0 357 384">
							  <defs>
							    <linearGradient id="linear-gradient2" x1="0.126" y1="0.901" x2="0.944" y2="0.016" gradientUnits="objectBoundingBox">
							      <stop offset="0" stop-color="#ffe3ab"></stop>
							      <stop offset="1" stop-color="#fff"></stop>
							    </linearGradient>
							  </defs>
							  <path id="Path_774" data-name="Path 774" d="M10,0H347a10,10,0,0,1,10,10V374a10,10,0,0,1-10,10H10A10,10,0,0,1,0,374V10A10,10,0,0,1,10,0Z" fill="url(#linear-gradient2)"></path>
							</svg>' ,
								'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="357" height="384" viewBox="0 0 357 384">
							  <defs>
							    <linearGradient id="linear-gradient" x1="0.807" y1="0.146" x2="-0.123" y2="1.159" gradientUnits="objectBoundingBox">
							      <stop offset="0" stop-color="#fff"></stop>
							      <stop offset="0.821" stop-color="#ffa6a6"></stop>
							      <stop offset="1" stop-color="#ff5b5b"></stop>
							    </linearGradient>
							  </defs>
							  <rect id="Rectangle_2277" data-name="Rectangle 2277" width="357" height="384" rx="10" fill="url(#linear-gradient)"></rect>
							</svg>'	
								] ;

							@endphp 
							@foreach( $what_we_produce as $slice )
								@php $i++ @endphp
								<div class="col-md-4">
									<div class="item wow fadeInLeft" data-wow-duration="0.3s">
										{!! $svgs[$i] !!}
										<img class="img-responsive" src="{{ $slice->photo ?? '' }}" />
										<hr class="hrStyle hrStyle1">
										<h2 class="titleItem">{{ $slice->title ?? '' }}</h2>
										<div class="desc">
											{{ strip_tags($slice->description) ?? '' }}
										</div>
									</div>
								</div>
							@endforeach
						@endif
	    			
	    			
	    		</div>
	    	</div>
	    </div>
	    
	    <div class="topTen">
	    	<div class="container wow flipInX">
	    		<h2 class="title">العشره الاوائل</h2>
	    		
				<div id="OwlTop" class="OwlTop owl-carousel owl-theme">
				
						@if( !empty( $first_students ) )
							@foreach( $first_students as $slice )
								<div class="item">
									<div class="itemTop">
										<img src="{{ $slice->photo ?? '' }}" />
										<div class="details">
											<h2 class="titleItem">{{ $slice->title ?? '' }}</h2>
											<ul class="listStudent">
												<li><i class="fa fa-user"></i>{{ $slice->mar7la ?? '' }}</li>
												<li><i class="fa fa-user"></i>{{ $slice->city->name ?? '' }}</li>
											</ul>
										</div>
									</div>
								</div>
							@endforeach
						@endif
					
				
				</div>
		
	    		
	    		<a href="{{ route('register') }}" class="btnTen">انضم الآن</a>
	    	</div>
	    </div>
	@endsection
