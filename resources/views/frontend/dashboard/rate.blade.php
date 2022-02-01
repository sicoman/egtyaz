
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')

    <div class="ratePage  minHeight600">


    		@foreach( $taxonomies as $taxonomy )
                @php if( $taxonomy['type'] != 'subject' ){ continue; } @endphp
			<div class="itemRate">
				<div class="rateHead clearfix">
					<div class="rightSection">
						<h2 class="title">{{ $taxonomy['name'] }}</h2>
						<ul class="listRate clearfix">
                            @php $counts = (5 * $percents[$taxonomy['id']]['percent'] ?? 0) / 100  ; @endphp
							@for( $i=1;$i<=$counts;$i++ )
                                <li><img src="{{ asset('/frontend/images') }}/medal-of-honor.png" /></li>
                            @endfor
						</ul>
					</div>
					<div class="leftSection">
					    <div data-size="120" data-value="{{ $percents[$taxonomy['id']]['percent'] / 100 }}" data-fill="{&quot;color&quot;: &quot;#00C29B&quot;}" class="firstx circleNormal circle">
					      <strong></strong>
					    </div>
	    				<span class="textRate">نسبة الانجاز</span>
				    </div>
				</div>
				<div class="rateBody">
                @foreach( $taxonomies as $taxonomy2 )
                    @php if( $taxonomy2['type'] != 'skill' || $taxonomy2['parent'] != $taxonomy['id'] ){ continue; } @endphp
					<span class="progressTitle">{{ $taxonomy2['name'] }}</span>
					<div class="progress clearfix"><div progress-width="{{ $percents[$taxonomy2['id']]['percent'] }}" class="color1 line"><span class="numb">{{ $percents[$taxonomy2['id']]['percent'] }} %</span></div></div>
                @endforeach
                </div>

				
			</div>
            @endforeach
    		
    		<div class="clearfix hide">
    			<a href="#" class="btnForm">إعادة جميع التقييمات</a>
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

