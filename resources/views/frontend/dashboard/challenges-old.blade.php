
@extends('layouts.dashboard') 
@section('Content')

@include('frontend.includes.topPartDashboard')
@include('frontend.includes.breadCrumbDashboard')	
@include('frontend.includes.challenges-links')

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
	    


@endsection