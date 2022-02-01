
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	  
        <div class="minHeight600">
    	  @foreach ($myExams as $exam)
   		    <div class="testStyle clearfix @if($exam->results->count()) active  @endif">
    			<a href="{{route("my-exam", ["id" => $exam->id])}}" class="title">ID#-{{$exam->id}} :: {{$exam->created_at->format('M d Y')}}</a>
				<ul class="date clearfix">
					<li><i class="flaticon-wall-clock"></i> زمن الإجابة : {{$exam->available_time}} دقيقة</li>
					<li><i class="flaticon-information"></i>عدد الأسئلة : {{$exam->questions_count}}  </li>
				</ul>
    		</div>          
          @endforeach	
    		<div class="pagiDiv clearfix">
                {{ $myExams->links() }}
    			
    		</div>
    		
	    </div>
  @endsection