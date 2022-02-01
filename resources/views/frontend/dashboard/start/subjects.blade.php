
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
	    <div class="askTeacher support minHeight600">
			@foreach ($subjects as $subject)
    		<div class="askDiv">
    			<div class="imgDiv">
	    			<img class="iconImg" style="width:130px;height:130px;" src="{{$subject->photo}}" />
    			</div>
    			<div class="details">
    				<a href="{{route("start_skills",['subject_id' => $subject->id, 'category_id' => $subject->parent])}}" class="title">{{$subject->name}}</a>
    				<div class="desc">
    				   {{$subject->description}}
    				</div>
    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
    			</div>
    		</div>
            @endforeach    		
	    </div>

  @endsection