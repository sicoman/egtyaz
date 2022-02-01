
@extends('layouts.dashboard') 
@section('Content')

@include('frontend.includes.topPartDashboard')
@include('frontend.includes.breadCrumbDashboard')	  

	    
	    <div class="Subscriptions  minHeight600">
    		<div class="clearfix plans">
            @foreach ($availablePkgs as $package)
				@php 
					$roles  = (array) json_decode( $package->roles ) ;
				@endphp
     			<div class="plan">
    				<div class="planHead">
    					<h2 class="title"><?php echo $package->name; ?></h2>
    					<span class="subTitle"><?php echo $package->description; ?></span>
						<h2 class="title text-center price">
						 @php 	
							$diffPrice = 0 ;
							if( $package->new_price != $package->price ){
								$diffPrice = 1 ;
							}
						 @endphp 
						 @if( $diffPrice == 1 )
							<s class="text-danger"> {{ $package->price }} </s> {{ $package->new_price }} {{ trans('currency.'.env('currency_code' , 'SAR')) }}
						 @else 
						 	{{ $package->price }} {{ trans('currency.'.env('currency_code' , 'SAR')) }}
						 @endif
						 
						</h2>
    				</div>
    				<div class="planBody">
	    				<ul class="listPlan">
							@foreach( $PackageRoles as $k=>$v )

	    					<li class="clearfix"> {{ $v }}
								@if( isset( $roles[$k]) && $roles[$k] == true )
							 		<img src="{{ asset('/frontend/images') }}/checkedPlan.png" /></li>
							 	@else 
								 	<img src="{{ asset('/frontend/images') }}/checkPlan2.png" /></li>
								@endif
	    					@endforeach
						</ul>
	    				<a href="{{route("buy_package_details", ["id" => $package->id])}}" class="btnPlan">اشترك الآن</a>
    				</div>
    			</div>
            @endforeach
    		</div>
			
	    </div>
	    


@endsection