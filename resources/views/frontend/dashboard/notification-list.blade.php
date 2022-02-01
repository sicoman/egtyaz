
@extends('layouts.dashboard') 
@section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')


	<div class="askTeacher pilotDetails materials support minHeight600">
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
										<th colspan="2">الاشعارات</th>
									</tr>
									@if (!$notificationsWithUsers->isEmpty())
									<tr class="head">
										<th style="font-size: 18px !important;font-weight: normal;">العنوان</th>
										<th style="font-size: 18px !important;font-weight: normal;">المحتوى</th>
									</tr>	
									@endif
								</thead>
								<tbody>
									@foreach( $notificationsWithUsers as $notification)
										<tr>
											<td>{{ $notification->data['title'] }}</td>
											<td>{{ $notification->data['message'] }}</td>
										</tr>
									@endforeach
									@if($notificationsWithUsers->isEmpty())
									 <tr>
									 	<td colspan="2">
											<div class="alert alert-info alert-dismissible show" role="alert">
											   لا يوجد لديك اشعارات بعد
											</div>
										 </td>
									 </tr>
									@endif
								</tbody>
						</table>
	    			</div>
	    		</div>
    		<div class="pagiDiv clearfix">
	            {{ $notificationsWithUsers->links() }}
    		</div>
    </div>

@endsection