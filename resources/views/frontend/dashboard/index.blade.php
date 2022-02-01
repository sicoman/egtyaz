
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
	    <div class="askTeacher support minHeight600">
			@foreach ($bankCategrories as $bankCategory)
				<div class="askDiv">
					<div class="imgDiv">
						<img class="iconImg"  style="width:130px;height:130px;" src="{{ $bankCategory->photo }}" />
					</div>
					<div class="details">
						<a href="{{route("category_subjects",["category_id" => $bankCategory->id])}}" class="title">{{$bankCategory->name}}</a>
						<div class="desc">
							{{$bankCategory->description}}
						</div>
						<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
					</div>
				</div>
			@endforeach
	    </div>


  @endsection