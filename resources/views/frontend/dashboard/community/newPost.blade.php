
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
    				<h2 class="title">اضافة موضوع جديد</h2>
    				<div class="desc">
    				   يمكنك اضافة المحتوى من خلال هذه الصفحة
    				</div>
    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon hide"></a>
    			</div>
    		</div>
    		<form class="sendAsk" method="post" action="{{ route('add_community_post') }}">
					@csrf
	    			<div class="row">
	    				<div class="col-md-4">
	    					<span class="title">حدد القسم</span>
							<div class="selectStyle">
								<select class="selectmenu" name="taxonomy_id" required id="selectmenu">
									<option>حدد القسم الذي تناسبك</option>
									@if( !empty($categories) )
										@foreach($categories as  $category)
											<option value="{{ $category->id }}">{{ $category->name }}</option>
										@endforeach
									@endif
								</select>
							</div>
	    				</div>
	    				<div class="col-md-8">
	    					<span class="title">عنوان الموضوع</span>
							<input type="text" name="title" placeholder="عنوان الموضوع" class="inputStyle">
	    					<span class="title">تفاصيل الموضوع</span>
							<textarea name="description" placeholder="اكتب تفاصيل الموضوع" class="inputStyle"></textarea>
	    				</div>
					</div>
					
	    			<button type="submit" class="btnForm">ارسل الآن</button>
	    			
	    		</form>
	
	    </div>


  @endsection