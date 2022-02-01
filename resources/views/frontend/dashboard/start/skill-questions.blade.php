
  @extends('layouts.dashboard') 
  @section('Content')
	<style>
	  .inWishlist{
		  color: red;
	  }
	  .iconStyle{
		  cursor: pointer;
	  }
	</style>

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')
	{{csrf_field()}}

	<div class="incorparationQ minHeight600">
			@foreach ($questions as $question)
				<div class="ques">
					<a href="{{ route('elearning' , $question->id ) }}" class="title"> {!! $question->title !!} </a>
					<div class="desc">
	  					@php 
	  						$desc = json_decode( $question->description ) ;
						@endphp
						{!! $desc->title ?? '' !!}
					</div>
				</div>
			@endforeach
			<div class="pagiDiv clearfix">
    			 {{ $questions->links() }}
    		</div>
	</div>
		
  @endsection

 @section('custom_javascript')
 <script type="text/javascript">
   $(function(){

	   $('.clicktoSeeDescription').on('click', function(){   
		   let p = $( $(this).data('href') ).html();
		   $('.modal-body').html( p );
		   $('#ReviewAnswer').modal('show') ;
	   });


	  $('.addOrRemoveInWishlist').on('click', function(){
	    let obj = $(this);
		$.ajax({	
			url: "{{route('addOrRemoveInWishlist')}}",  
			dataType: 'json',  
			cache: false,
			data: {
				key_id: $(this).attr('data-id')
			},
			headers: {
				'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
			},                  
			type: 'post',
			success: function(data){ 
				if(data.action == "add"){
				  obj.find('.flaticon-favourite').addClass("inWishlist");
				}else{
				  obj.find('.flaticon-favourite').removeClass("inWishlist");
				}
				$.toast({
					heading: 'المفضلة',
					text: data.message,
					showHideTransition: 'slide',
					icon: 'success',
					position : 'top-right',
					textAlign: 'right',
					hideAfter: 1000
				})
			}
		 });
		  return false;
	  });

   });
 </script>  
 @endsection