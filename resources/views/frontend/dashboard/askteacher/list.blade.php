
@extends('layouts.dashboard') 
@section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')


	<div class="askTeacher pilotDetails materials support minHeight600">
    		
    		<form class="sendAsk savedForm" method="get">
	    			<div class="row">	    		
	    				<div class="col-md-5">
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
	    				</div>
	    				<div class="col-md-5">
	    					<span class="title">حدد المهارة</span>
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
	    				
	    				<div class="col-md-2">
	    					<button class="btnForm">تعيين</button>
	    				</div>
	    			</div>
	    			
	    		</form>
	    		
	    		<div class="savedQues">
	    			<div class="headSaved clearfix hide hidden">
	    				<h2 class="title">السؤال</h2>
	    				<ul class="listRemove">
	    					<li><span class="remove">حذف الكل</span></li>
	    					<li><span class="remove">حذف</span></li>
	    				</ul>
					</div>
					
	    			<div class="questionS tableDiv">
						<table class="table tableQues table-bordered table-stirped">
								<thead>
									<tr class="head">
										<th>السؤال</th>
										<th>الطالب</th>
										<th style="width:200px">تاريخ الانشاء</th>
									</tr>
								</thead>
								<tbody>
									@foreach( $listAsk as $ques )
										<tr>
											<td><a href="{{ route('askTeacherShow' , [$ques->id] ) }}"> {{ $ques->question }} </a></td>
											<td>{{ $ques->user->name }}</td>
											<td>{{ $ques->created_at->diffForHumans() }}</td>
										</tr>
									@endforeach
								</tbody>
						</table>
	    			</div>
	    		</div>
	
    		<div class="pagiDiv clearfix">
	            {{ $listAsk->links() }}
    		</div>
    </div>

@endsection