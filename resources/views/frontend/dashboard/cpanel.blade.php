
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')

    <div class="ratePage ratePage2  minHeight600">

			<div class="itemRate finishRate">
				<div class="rateHead clearfix">
					<div class="rightSection">
						<h2 class="title"> تقييم الطالب </h2>
					</div>
				</div>
				<div class="rateBody">
                @foreach( $taxonomies as $taxonomy2 )
                    @php if( $taxonomy2['type'] != 'subject' ){ continue; } @endphp
					<span class="progressTitle">{{ $taxonomy2['name'] }}</span>
					<div class="progress clearfix"><div progress-width="{{ $percents[$taxonomy2['id']]['percent'] }}" class="color1 line"><span class="numb">{{ $percents[$taxonomy2['id']]['percent'] }} %</span></div></div>
                @endforeach
                </div>

			
			</div>

			<div class="itemDetails">
    			<img src="{{ asset('/frontend/images') }}/imgRate2.png" alt="">
    			<h2 class="title">توجة الأن الى المذاكرة بشكل بسيط</h2>
    			<a href="{{ route('bank') }}">ابدأ الآن</a>
    		</div>

			<div class="row">
				@foreach ($dashBlocks as $post)
    			<div class="col-md-6">
    				<div class="itemBeta">
    					<img src="{{$post->photo}}">
    					<h2 class="title">{{$post->title}}</h2>
    					<div class="desc">
							{!!$post->description!!}
    					</div>
    					<div class="clearfix">
    						<a href="{{route("exam")}}">ابدأ الآن</a>
    					</div>
    				</div>
    			</div>
				@endforeach
    		</div>

			<div class="rewards">
 				<div class="copyStyle boxStyle">
 					<h2 class="title">ابدأ بالربح الآن</h2>
 					<div class="linkDiv">
 						<span class="link" id="textC">{{$referalUrl}}</span>
 						<a class="copy" onclick="copyToClipboard('#textC')"><i class="flaticon-copy"></i> نسخ الرابط</a>
 					</div>
 				</div>
 				<div class="shareStyle clearfix boxStyle">
 					<h2 class="title">شارك الآن</h2>
					 <div class="sharethis-inline-share-buttons" data-url="{{ $referalUrl }}" data-title="سجل معنا الان"></div>
 				</div>

				
				
 			</div>

    		
    		
    		
	    </div>
	    
	    
  @endsection
@section('custom_javascript')
	<script> 
        $(document).ready(function(){

            $('.firstx').each(function(){

                var p =  parseFloat( $(this).attr('data-value')) ;

                $(this).circleProgress({
		            value: p
                }).on('circle-animation-progress', function (event, progress) {
                    $(this).find('strong').html('<i>%</i>' + Math.round( (p * 100) * progress));

                });

            });

        });
    </script>
@stop

