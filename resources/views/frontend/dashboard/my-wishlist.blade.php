
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	  
   	    
	    <div class="askTeacher support minHeight600">
    		
    		<form method="get" class="sendAsk savedForm">
	    			<div class="row">	    		
	    				<div class="col-md-5">
	    					<span class="title">حدد المادة</span>
							<div class="selectStyle">
								<select name="subject" class="selectmenu" id="selectmenu">
									<option @if(!request()->has("subject") || !request()->get("subject") == "") @endif value="">حدد المادة</option>
                                @foreach ($subjects as $subject)
									<option @if(request()->has("subject") && request()->get("subject") == $subject->id) xxxxxx @endif value="{{$subject->id}}">{{$subject->name}}</option>
                                @endforeach
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>
	    				<div class="col-md-5">
	    					<span class="title">حدد المهارة</span>
							<div class="selectStyle">
								<select name="skill" class="selectmenu" id="selectmenu2">
									<option @if(!request()->has("skill") || !request()->get("skill") == "") @endif value="">حدد المهارة التي تناسبك</option>
									@foreach ($skills as $skill)
										{{--
										<option @if(request()->has("skill") && request()->get("skill") == $skill->id) selected @endif value="{{$skill->id}}">{{$skill->name}}</option>
										--}}
									@endforeach
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>
	    				
	    				<div class="col-md-2">
	    					<button class="btnForm">تعيين</button>
	    				</div>
	    			</div>
	    			
	    		</form>
	    		
	    		<div class="savedQues">
	    			<div class="headSaved clearfix">
	    				<h2 class="title">السؤال</h2>
	    				<ul class="listRemove">
	    					<li><span class="removeAll">اختيار الكل</span></li>
	    				</ul>
	    			</div>
	    			<div class="questionS">
                        @foreach ($wishlist as $element)
	    				<div class="quesS" data-id="{{$element->id}}">
                            <a href="{{ route('question' , ['id' => $element->question->id ]) }}" style="color:#000">{{ strip_tags( html_entity_decode( $element->question->title ) ) }}</a>
	    					<i class="iconSelect"></i>
	    				</div>
                        @endforeach
	    			</div>
	    		</div>
    		<form method="get" class="sendAsk savedForm wishlistAction" style="padding: 10px 10px;">
	    			<div class="row">	    	
	    				<div class="col-md-2" style="float:left;">
	    					<button class="btnForm">تنفيذ</button>
	    				</div>
	    				<div class="col-md-2" style="float:left;">
	    					<span class="title">حدد الفعل</span>
							<div class="selectStyle">
								<select name="action" class="selectmenu" id="selectmenu">
									<option value="delete">حذف</option>
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>	    				
	    			</div>
	    			
	    		</form>
	    		
	
    		<div class="pagiDiv clearfix">
    		    {{$wishlist->links()}}
    		</div>
    </div>
  @endsection
	@section('custom_javascript')
	<script>
	  $(function(){
         
		 $('.removeAll').on('click', function(){
			 if($(this).hasClass('selected')){
			  $(this).text("اختيار الكل");
			  $('.quesS').removeClass("active");
			  $(this).removeClass("selected");
			 }else{
			  $(this).addClass("selected");
			  $(this).text("الغاء التحديد");
			  $('.quesS').addClass("active");
			 }
			 return false;
		 });

		 $('#selectmenu').change() ;
		 
		 $('#selectmenu').on('selectmenuchange', function(vv , v){
				$.get('/api/taxonomy/skill?parent='+v.item.value , function(d){
					$('#selectmenu2').find('option').remove() ;
					d.map( function( kx ){
						$('#selectmenu2').append('<option value="'+kx.id+'">'+kx.name+'</option>') ;	
					} );
					$('#selectmenu2').selectmenu("refresh");
				});
		 }) ;

		 $('.wishlistAction').on('submit', function(){
			 let ids = [];
			 let getSelected = $('.quesS.active');
			 getSelected.each(function(){
				 ids.push($(this).attr("data-id"));
			 });
			 
			 $.post("{{route("deleteQuestionsFromWishlist")}}", {ids}, function(data){
				 if(data.result){
					getSelected.fadeOut(function(){
						$(this).remove();
					}); 
					$.toast({
						heading: 'المفضلة',
						text: "تم حذف ألاسئلة من المفضلة بنجاح",
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
	@stop
