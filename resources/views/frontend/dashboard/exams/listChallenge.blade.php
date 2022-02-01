
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	
	@include('frontend.includes.challenges-links')

		

        <div class="minHeight600">
		  @foreach ($list as $exam)
		
			@php 
				$has_results = $exam->MyResults->count() ;
			@endphp
   		    <div class="testStyle clearfix @if($has_results) active  @endif">
				@if( $has_results )   
    			<a href="{{route("ExamResult", ["id" => $exam->id])}}" class="title">
					@if( isset($exam->title[3]) )
						{{ $exam->title }}
					@else 
						ID#-{{$exam->id}} :: {{ $exam->created_at->format('M d Y')}}
					@endif
				</a>
				&nbsp;
				<a href="{{route("startExam", ["id" => $exam->id])}}" class="btn btn-sm btn-warning">اعادة الأختبار</a>
				@else 
				<a href="{{route("startExam", ["id" => $exam->id])}}" class="title">
					@if( isset($exam->title[3]) )
						{{ $exam->title }}
					@else 
						ID#-{{$exam->id}} :: {{ $exam->created_at->format('M d Y')}}
					@endif
				</a>
				@endif
				<ul class="date clearfix">
					<li><i class="flaticon-wall-clock"></i> زمن الإجابة : {{$exam->available_time / 60 }} دقيقة</li>
					<li><i class="flaticon-information"></i>عدد الأسئلة : {{$exam->questions_count}}  </li>
				</ul>
    		</div>          
          @endforeach	
    		<div class="pagiDiv clearfix">
                {{ $list->links() }}
    		</div>
    		
	    </div>
  @endsection