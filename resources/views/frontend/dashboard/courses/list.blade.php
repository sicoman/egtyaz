
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	

	<div class="askTeacher support minHeight600">
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="{{ asset('/frontend/images') }}/Group2100.png" />
    			</div>
    			<div class="details">
    				<h2 class="title">تقدر تسجل فيها بمبالغ رمزية</h2>
    				<div class="desc">
هيكون فيها معلمين مختصين هيشرحوا التجميعات بطريقة سهلة ومميزة وكمان أونلاين بشكل تفاعلي ومش هتاخد منك وقت.
    				</div>
    			</div>
    		</div>
    		
    		<div class="AdvCourse">
    			<div class="row">
    				
    				@forelse($list as $course)
    				<div class="col-md-4">
    					<div class="item">
    						<a href="{{ route('course' , ['id' => $course->id , 'title' => $course->title ] ) }}" class="mask">
								<img src="{{ $course->photo }}" style="max-width:100%;height:200px;" />
							</a>
	    					<div class="details">
	    						<a href="{{ route('course' , ['id' => $course->id , 'title' => $course->title ] ) }}" class="title">{{ $course->title }}</a>
	    						<div class="desc" style="min-height:60px">
									{{ Illuminate\Support\Str::words($course->description , 10 ) }}
	    						</div>
	    						<a href="{{ route('Joincourse' , ['id' => $course->id , 'title' => $course->title ] ) }}" class="btnItem">اشترك الآن</a>
	    					</div>
    					</div>
    				</div>
					@empty 
						<div class="col-md-12">
							<p class="alert alert-warning">
								عفوا لا يوجد كورسات مضافه حاليا
							</p>
						</div>
					@endforelse
    				
    			</div>
    		</div>
    		
    		<div class="pagiDiv clearfix">
    			 {{ $list->links() }}
    		</div>
    </div>

  @endsection