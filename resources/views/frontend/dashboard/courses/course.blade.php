
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	

	<div class="askTeacher courseStyle minHeight600">
			
			<div class="courseItem clearfix">
				<div class="col-md-4">
					<img src="{{ $course->photo }}" />
				</div>
				<div class="col-md-8">
					<div class="details">
						<span class="price">سعر الدورة  {{ $course->price }}  {{ trans('currency.'.env('currency_code' , 'SAR')) }} </span>
						<h2 class="title"> {{ $course->title }} </h2>
						<div class="desc">
							{!! nl2br($course->description) !!}
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
				<a href="{{ route('Joincourse' , [ 'id' => $course->id , 'title' => $course->title ] ) }}" class="btnForm">اشترك الآن</a>
			</div>
    		
    	</div>

  @endsection