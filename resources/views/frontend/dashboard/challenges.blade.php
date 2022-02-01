
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	
	<style>
		.competitorsTitle{
			margin-left: 25px;
			font-size: 20px;
			font-family: "Tajawal-Bold";
			margin-bottom: 30px;
		}

		.competitorsList{
			display: flex;
			align-items: center;
			flex-flow: row wrap;
		}

		.competitorsList li{
			display: flex;
			background: #D7E6FF;
			margin: 10px 0px 10px 10px;
			padding: 5px;
		}

		.competitorsList li:after{
			content:"x";
			height: 20px;
			width: 20px;
			margin-left: 5px;
			text-align: left;
			cursor: pointer;
		}

	</style>

	   @include('frontend.includes.challenges-links')
	    
	    <div class="askTeacher competitions support minHeight600">
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="{{ asset('/frontend/images') }}/023-student.png" />
    			</div>
    			<div class="details">
    				<h2 class="title">الآن يمكنك أنت وصديقك المسابقة والتحدي</h2>
    				<div class="desc">
						اختار صديقك وحدد الأسئلة والمهارات المناسبة وابدا الآن
    				</div>
    				<a href="#" class="flaticon-share icon"></a>
    			</div>
    		</div>
    		<form method="post" class="createCompetition sendAsk">
			@csrf
			{{csrf_field()}}
			<input type="hidden" name="add" value="challenge" />
				<div class="selectPilot">
		                    <div class="row">
                        <div class="col-md-6">
                            <div class="item">
                                <h2 class="title"><i class="flaticon-equalizer"></i> حدد عدد الاسئلة</h2>
                                <div class="clearfix">
                                    <ul class="selectList">
                                        <li data-value="10" data-target="Count" class="active"> 10 <span>أسئلة</span></li>
                                        <li data-value="20" data-target="Count">20 <span>أسئلة</span></li>
                                        <li data-value="30" data-target="Count">30 <span>أسئلة</span></li>
                                        <li class="addSty"  data-target="Count"><a href="#" class="addSelect"><i class="fa fa-plus"></i></a></li>
                                    </ul>
                                    <input type="hidden" id="Count" name="count" value="10" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="item">
                            <h2 class="title"><i class="flaticon-equalizer"></i> حدد زمن الأختبار</h2>
                            <div class="clearfix">
                                    <ul class="selectList">
                                        
                                        <li data-value="5" data-target="Time" class="active"> 5 <span>دقيقة</span></li>
                                        <li data-value="15" data-target="Time"> 15 <span>دقيقة</span></li>
                                        <li data-value="30" data-target="Time">30 <span>دقيقة</span></li>
                                        <li class="addSty" data-target="Time"><a href="#" class="addSelect" ><i class="fa fa-plus"></i></a></li>
                                    </ul>
                                    <input type="hidden" id="Time" name="time" value="30" />
                            </div>
                        </div>
                    </div>
				</div>
	    			<div class="row">	    		
	    				<div class="col-md-3">
	    					<span class="title">ابحث برقم الهاتف او البريد الاكتروني :</span>
							<div class="selectStyle">
								<input class="inputStyle" id="phone_or_email" placeholder="أضف رقم الجوال أو البريد الالكترونى">
							</div>
	    				</div>
	    				<div class="col-md-3">
	    					<span class="title">حدد المادة :</span>
							<div class="selectStyle">
								<select name="subject" data-message="من فضلك قم باختيار المادة" class="selectmenu validatable" multiple="multiple" id="selectmenu">
								  <option selected value="">اختار المادة</option>
								  @foreach ($subjects as $subject)
									<option value="{{$subject->id}}">{{$subject->name}}</option>
								  @endforeach
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>
	    				<div class="col-md-3">
	    					<span class="title">حدد المهاراة :</span>	
	    					<div class="selectStyle">
								<select name="skill" data-message="من فضلك قم باختيار المهارة" class="selectmenu validatable" id="selectmenu2">
								  <option selected value="">اختار المهارة</option>
								  @foreach ($skills as $skill)
									<option value="{{$skill->id}}">{{$skill->name}}</option>
								  @endforeach
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
	    				</div>

						<div class="col-md-12 selectQues">
						 <span class="competitorsTitle">المتسابقين</span>
						 <div class="competitors">
						  <ul class="competitorsList">
						  </ul>
						 </div>
					</div>
	    			</div>
					<div class="col-md-12">
					 <div class="loader hidden" style="margin: 0 auto;"></div>
					</div>
	    			<button class="btnForm">ابدأ الآن</button>
	    			
	    		</form>
	    </div>

        <div class="modal fade" id="CustomNumberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> ضع القيمة التى تناسبك </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <input type="number" class="form-control input-lg" id="CustomNumber" min="1" max="500" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="CloseCustomModal"  data-dismiss="modal"> حفظ  </button>
                </div>
                </div>
            </div>
        </div>

 @endsection
@section('custom_javascript')
	<script>
	   $(function(){


  			$('ul.selectList li').click(function(){

                $(this).parents('ul').filter(':first').removeClass('active') ;

                $(this).addClass('active') ;

                if( $(this).is('.addSty') ){
                        $('#CloseCustomModal').attr('data-target' , $(this).attr('data-target') ) ;
                        $('#CustomNumberModal').modal('show') ;
                }else{
                    $( '#'+$(this).attr('data-target') ).val( $(this).attr('data-value') ) ;
                }  
                
                return false ;
            });

     	   $('#CloseCustomModal').click(function(){
                    var v = $('#CustomNumberModal').find('input').val() ;
                    
                    if( v || parseInt(v) > 0 ){
                        $( '#'+$('#CloseCustomModal').attr('data-target') ).val( v ) ;
                    }
            }) ;


			$( "#phone_or_email" ).autocomplete({
			source: function( request, response ) {
				$.ajax( {
				url: "{{route('searchUsers')}}",
				dataType: "json",
				data: {
					query: request.term
				},
				success: function( data ) {
					response( data );
				}
				} );
			},
			minLength: 1,
			select: function( event, ui ) {
				$(this).val("");
				if(!$('.competitorsList').find('li[data-id="'+ui.item.id+'"]').length){
		     		$('.competitorsList').append('<li data-id="'+ui.item.id+'">'+ui.item.value+'</li>');
				}
			    event.preventDefault();
			}
			} );

			$('.competitorsList li').on('click', function(){
				 $(this).remove();
			});

	   });

	   $('.createCompetition').on('submit', function(){
		 $('.loader').removeClass('hidden');  
		 let errors = [];
		   $('.validatable').each(function(){  
			  if($(this).prop('tagName').toLowerCase() == "select"){
				 if($(this).children("option:selected").attr('value').length == 0){
					errors.push(true);
					$.toast({
						heading: 'المسابقة',
						text: $(this).attr('data-message'),
						showHideTransition: 'slide',
						icon: 'error',
						position : 'top-right',
						textAlign: 'right',
						hideAfter: 2500
					})
				 }
			  }
		   });

		   if(!$('.competitorsList').find('li').length){
					errors.push(true);
					$.toast({
						heading: 'المسابقة',
						text: 'من فضلك قم باختيار المتسابقين',
						showHideTransition: 'slide',
						icon: 'error',
						position : 'top-right',
						textAlign: 'right',
						hideAfter: 2500
					})
		   }

		   if(!errors.length){
			   let formData = $(this).serializeArray();
			   let competitors = $('.competitorsList').find('li');
			   competitors.each(function(){
				   formData.push({
					   name: "competitors[]",
					   value: $(this).attr('data-id')
				   });
			   });

			 $.ajax({
                type: 'POST',
                url : "{{ route('createChallenge') }}",
                data: formData,
                dataType: 'json',
				xhrFields: {
				withCredentials: true
				},
                success: function(data){
					if(data.id){
					$.toast({
						heading: 'المسابقة',
						text: 'تم انشاء المسابقة بنجاح شكرا لك',
						showHideTransition: 'slide',
						icon: 'success',
						position : 'top-right',
						textAlign: 'right',
						hideAfter: 2500
					})
					}
					 $('.loader').addClass('hidden');  
				}
				});
			   
		   }else{
			   $('.loader').addClass('hidden'); 
		   }

		   return false;
	   });

	 
	</script>
@endsection