
@extends('layouts.dashboard') 
@section('Content')

@include('frontend.includes.topPartDashboard')
@include('frontend.includes.breadCrumbDashboard')	  
@php
 if($course->price){
    $route = route("buy_package", ["id" => $course->id,"processor" => "paypal"]);  
 }else{
    $route = route("buy_package", ["id" => $course->id,"processor" => "free"]);  
 }

@endphp
	    
	    <div class="askTeacher courseStyle paymentStyle minHeight600">
		  <form action="{{$route}}">
			<div class="row">
				<div class="col-md-6">
					<img style="max-width:100%" class="img-responsive" src="{{ $course->photo }}">
					<div class="courseItem clearfix">
						<div class="details">
								<span class="price">سعر الدورة  {{ $course->price }}  {{ trans('currency.'.env('currency_code' , 'SAR')) }} </span>
								<h2 class="title">{{$course->title}}</h2>
								<div class="desc">
							     	{{$course->description}}
								</div>
								@if( count($course->items) > 0 )
									<hr />
									<div class="table-responsive">
										<table style="width:100%" class="table table-bordered table-striped">
											@php $i = 1; @endphp
											@foreach($course->items as $item)
												<tr> 
													<td>{{ $i++ }}</td>
													<td> {{ $item->title }} </td>
												</tr>
											@endforeach
										</table>
									</div>
								@endif
							</div>
					</div>
				</div>
				<div class="col-md-6">
					@include('frontend.dashboard.global.buy' , [ 'item' => $course , 'type' => 'course' ] ) 
				</div>
			</div>
	      </form>		
    	</div>


@endsection
