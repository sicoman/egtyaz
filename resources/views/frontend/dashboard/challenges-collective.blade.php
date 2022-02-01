
@extends('layouts.dashboard') 
@section('Content')

@include('frontend.includes.topPartDashboard')
@include('frontend.includes.breadCrumbDashboard')	
@include('frontend.includes.challenges-links')

	{{--
	    <div class="ChallengesDiv minHeight600">
	    	<img src="{{ asset('/frontend/images') }}/BgCompetitions-3.png" class="BgCompetitions" />
			@foreach ($challenges as $challenge)
				<div class="Challenges">
					<h2 class="title">{{$challenge['status']}}</h2>
					<div class="chal">
						<div class="clearfix">
							<div class="chalUser color1">
								<img src="{{$challenge['user']['avatar']}}" />
								<span class="name">{{$challenge['user']['name']}}</span>
								@if(!empty($challenge['user']['results'])) <span class="numb">{{$challenge['user']['results']['percent']}}</span> @endif
							</div>
							<span class="vs">vs</span>
							<div class="chalUser color2">
							<img src="{{$challenge['challengers'][0]['user']['avatar']}}" />
							<span class="name">{{$challenge['challengers'][0]['user']['name']}}</span>
							@if(!empty($challenge['challengers'][0]['results'])) <span class="numb">{{$challenge['challengers'][0]['results']['percent']}}</span> @endif
						</div>
						</div>
						<div class="shareChal">
							<h2 class="titleShar">مشاركة</h2>
							<a href="#" class="btnShar">Facbook <img src="{{ asset('/frontend/images') }}/Facebook.png" /></a>
							<a href="#" class="btnShar">Youtube <img src="{{ asset('/frontend/images') }}/Youtube.png" /></a>
							<a href="#" class="btnShar">Snapchat <img src="{{ asset('/frontend/images') }}/Snapchat.png" /></a>
							<a href="#" class="btnShar">linkedin <img src="{{ asset('/frontend/images') }}/LinkedIN.png" /></a>
						</div>
					</div>
				</div>
			@endforeach

	    </div>
	--}}	

	<div class="askTeacher competitions minHeight600">
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="/frontend/images/023-student.png">
    			</div>
    			<div class="details">
    				<h2 class="title">أنت الآن في المسابقة الجماعية التحدي والحصول على 
					أكبر عدد من النقاط بجوائز قيمة شارك الآن</h2>
    			</div>
    		</div>
    		<div class="row">
				@foreach($posts as $post)
    			<div class="col-md-4">
		    		<div class="itemContent">
		    			<a href="{{ route('collectiveExams' , $post->id ) }}"><img src="{{ $post->photo }}" alt=""></a>
		    			<div class="details">
			    			<a href="{{ route('collectiveExams' , $post->id ) }}" class="title">{{ $post->title }}</a>
			    			<span class="date">{{ $post->description }}</span>
			    			<a href="{{ route('collectiveExams' , $post->id ) }}" class="btnForm">ابدأ الآن</a>
		    			</div>
		    		</div>
    			</div>
				@endforeach
    		
    		</div>

	    </div>    


@endsection