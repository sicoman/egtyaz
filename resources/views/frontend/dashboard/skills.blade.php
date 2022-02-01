
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
	    <div class="askTeacher support minHeight600">
			@foreach ($skills as $skill)
    		<div class="askDiv">
    			<div class="imgDiv">
	    			<img class="iconImg" src="{{$skill->photo}}" />
	    			<img class="check" src="images/check.png" />
    			</div>
    			<div class="details">
    				<a href="{{route("skill_questions",["category_id" => $category_id, "subject_id" => $subject_id, "skill_id" => $skill->id])}}" class="title">{{$skill->name}}</a>
    				<div class="desc">
    				   {{$skill->description}}
    				</div>
    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
    			</div>
    		</div>
            @endforeach    		
	    </div>

  @endsection