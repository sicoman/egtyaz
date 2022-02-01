
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')

	<div class="askTeacher support minHeight600">
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="/frontend/images/042-test.png">
    			</div>
    			<div class="details">
    				<h2 class="title">اسئل المعلم</h2>
    				<div class="desc">
					حدد اسم المادة ونوع المهارة وعلي الفور سيرد عليك احد معلمينا المختصين بشكل مرئي وواضح
    				</div>
    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon hide"></a>
    			</div>
    		</div>
    		<form class="sendAsk" method="post" action="{{ route('goAskTeacher') }}">
					@csrf
	    			<div class="row">
	    				<div class="col-md-4">
	    					<span class="title">حدد المادة</span>
							<div class="selectStyle">
								<select class="selectmenu" name="subject_id" required id="selectmenu">
									<option>حدد المادة التي تناسبك</option>
									@if( !empty($subjects) )
										@foreach($subjects as $id => $subject)
											<option value="{{ $id }}">{{ $subject }}</option>
										@endforeach
									@endif
								</select>
							</div>
	    					<span class="title">حدد نوع المهارة</span>
							<div class="selectStyle">
								<select class="selectmenu " name="skill_id" required id="selectmenu2">
									<option>حدد المهارة التي تناسبك</option>
									@if( !empty($skills) )
										@foreach($skills as $id => $skill)
											<option value="{{ $id }}">{{ $skill }}</option>
										@endforeach
									@endif
								</select>
							</div>
	    				</div>
	    				<div class="col-md-8">
	    					<span class="title">تفاصيل السؤال</span>
							<textarea name="question" placeholder="اكتب تفاصيل السؤال" class="inputStyle"></textarea>
	    				</div>
					</div>
					
	    			<button type="submit" class="btnForm">ارسل الآن</button>
	    			
	    		</form>
	
	    </div>


  @endsection