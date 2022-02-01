
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
    <div class="askTeacher pilotDetails materials minHeight600">
    		<div class="tableDiv">
    			<table class="tableQues">
    			<thead>
    				<tr class="head">
    					<th>السؤال</th>
    					<th>الاجابة</th>
						<th>كيفية الحل</th>
						<th class="text-center"> حفظ </th>
    				</tr>
    			</thead>
    			<tbody>
 
				 @foreach ($questions as $question)
				<tr>
						<td>{{$question->title}}</td>
						@if(isset( $question->Correct ))
							<td>{{$question->Correct->text ?? ''}}</td>
						@else 
							<td> </td>
						@endif
						<td> <a href="#"  class="btn btn-warning clicktoSeeDescription" data-href="#answer-{{$question->id}}" > عرض وصف الاجابة </a> <div id="answer-{{$question->id}}" class="answer hide hidden">{!! $question->description !!}</div> </td>
						<td class="text-center"> <a data-id="{{$question->id}}" class="iconStyle addOrRemoveInWishlist"><i class="flaticon-favourite @if($question->WishList()->first()) inWishlist @endif"></i></a> </td>
    			</tr>
				 @endforeach  
    			</tbody>
    		</table>
			</div>
			
    		<div class="pagiDiv clearfix">
    			 {{ $questions->links() }}
    		</div>
		</div>
		
        <div class="modal fade" id="ReviewAnswer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">مراجعة السؤال</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Answer description</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="CloseCustomModal"  data-dismiss="modal"> اغلاق </button>
                </div>
                </div>
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