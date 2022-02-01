
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
										<th colspan="2">{{$category->name}}</th>
									</tr>
								</thead>
								<tbody>
									@foreach( $posts as $post )
										<tr>
											<td><a href="{{route("community_post", ['post' => $post->id, 'category' => $post->taxonomy_id])}}">{{ $post->title }}</a></td>
											<td>{{ $post->created_at->diffForHumans() }}</td>
										</tr>
									@endforeach
									@if($posts->isEmpty())
									 <tr>
									 	<td colspan="2">
											<div class="alert alert-info alert-dismissible show" role="alert">
												هذا القسم لا يوجد به مقالات بعد
											</div>
										 </td>
									 </tr>
									@endif
								</tbody>
						</table>
	    			</div>
	    		</div>
    		<div class="pagiDiv clearfix">
	            {{ $posts->links() }}
    		</div>
    </div>

@endsection