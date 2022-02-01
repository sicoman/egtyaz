
  @extends('layouts.dashboard') 

  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
	   

    <div class="askTeacher materials minHeight600">

            <form class="pilotForm" action="{{ route('Makeexam') }} " method="post">
                @csrf

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
                                    <input type="hidden" id="Time" name="time" value="5" />
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
    		
    			
                <h2 class="titlePilot">حدد المواد</h2>
                
                @php $i = 1 ; @endphp
                @foreach( $subjects as $subject )
                
	    		<div class="materialAcc active">
	    			
		    		<div class="askDiv">
		    			<div class="imgDiv">
                            <img class="iconImg" style="width:130px;height:130px;" src="{{ $subject->photo }}" />
		    			</div>
		    			<div class="details">
		    				<h2 class="title openAcc"> {{ $subject->name }} </h2>
		    				<div class="desc">
                                {{ $subject->description }}
                            </div>
		    				<a href="#" style="display:none" class="btnASk">تحديد</a>
		    				<a href="#"data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
		    			</div>
		    		</div>
			    		
		    		<div class="content">
		    			<h2 class="titleContent">حدد المهارات المطلوبة لك</h2>
		    			<div class="row total">
                            @foreach( $subject->childrens as $skill )
		    				<div class="col-md-4">
                                <div class="selectSkill">
                                     {{ $skill->name }}   <input type="checkbox" value="1" name="subjects[{{ $subject->id }}][skills][{{ $skill->id }}]" />
                                </div>
                            </div>
                            @endforeach
		    			</div>
						<h2 class="titleContent">حدد الطريقة المناسبة لعرض السؤال</h2>
						<ul class="checkList clearfix">
                            {{-- 
							<li><span class="titleCount">عدد الأسئلة المراد مذاكرتها</span></li>
							<li>
			    				<div class="selectQues clearfix">
									<input type="hidden" value="10" />
			                        <div class="Count clearfix">
			                            <span class="plus">+</span>
			                            <strong>10</strong>
			                            <span class="minus">-</span>
			                        </div>
			    				</div>
                            </li>
                            --}}
							
							<li><div class="checkItem"><input name="subjects[{{ $subject->id }}][random]" value="1" type="checkbox" />اختيار أسئلة عشوائة</div></li>
							<li><div class="checkItem th1"><input name="subjects[{{ $subject->id }}][old]" value="1" type="radio" class="thisorthis" />اسئلة سابقة</div></li>
							<li><div class="checkItem th1"><input name="subjects[{{ $subject->id }}][old]" value="0" type="radio" class="thisorthis" />لا أريد تكرار الأسئلة السابقة</div></li>
						</ul>
	
		    		</div>
		    		
                </div>
                @php $i++ ; @endphp
                @endforeach
	    		
				<div class="clearfix">
					<button type="submit" class="btnForm">ابدأ المذاكرة</button>
				</div>
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

    <script type="text/javascript">

        $(document).ready(function(){

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

            $('.openAcc').filter(':not(:first)').click() ;

            $('#CloseCustomModal').click(function(){
                    var v = $('#CustomNumberModal').find('input').val() ;
                    
                    if( v || parseInt(v) > 0 ){
                        $( '#'+$('#CloseCustomModal').attr('data-target') ).val( v ) ;
                    }
            }) ;

            $('form.pilotForm').submit( function(){

                console.log( $(this).serialize() ) ;
                
                $.post( $(this).attr('action') , $(this).serialize() , function(d){
                    
                    if( d > 0 || parseInt(d) > 0 ){
                        setTimeout ( () =>{ window.location.href = '{{ route('startExam' , ['exam' => '' ] ) }}/'+d ; } , 4000 ) ;

                        $.toast({
                            heading: 'رسالة تنبية',
                            text: 'تم انشاء الأختبار بنجاح',
                            showHideTransition: 'slide',
                            icon: 'success',
                            position : 'top-right',
                            textAlign: 'right',
                            hideAfter: 3000
                        }) ;
                    }else{
                        $.toast({
                            heading: 'رسالة تنبية',
                            text: 'عفوا لم نستطع انشاء الأختبار الخاص بك',
                            showHideTransition: 'slide',
                            icon: 'error',
                            position : 'top-right',
                            textAlign: 'right',
                            hideAfter: 3000
                        }) ;
                    }
                }) ;


                return false ; 
            }) ;
            
            

        });

        


    </script>


  @endsection