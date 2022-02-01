
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
	    <div class="askTeacher support minHeight600">
			@foreach ($subjects as $subject)
    		<form class="materialAcc active" name="form{{$subject->id}}" id="form{{$subject->id}}" method="get" >
	    		<div class="askDiv">
	    			<div class="imgDiv">
		    			<img class="iconImg"   style="width:130px;height:130px;" src="{{ $subject->photo }}">
	    			</div>
	    			<div class="details">
	    				<h2 class="title openAcc"> {{ $subject->name }} </h2>
	    				<div class="desc">
							{{ $subject->description }}
	    				</div>
	    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
	    			</div>
	    		</div>
	    		
	    		<div class="content" style="display: none;">
	    			<h2 class="titleContent">حدد المهارات المطلوبة لك</h2>
	    			<div class="row total">
					    <input type="hidden" name="subject" value="{{ $subject->id }}" />
						@foreach( $subject->childrens as $skill )
						 
	    				<div class="col-md-4">
	    					<div class="selectSkill"> {{ $skill->name }} <input class="skl" type="checkbox" value="1" name="skills[{{ $skill->id }}]" ></div>
	    				</div>
	    				@endforeach
	    			</div>
					<h2 class="titleContent">حدد الطريقة المناسبة لعرض السؤال</h2>
					<ul class="checkList clearfix">
						<li><span class="titleCount">عدد الأسئلة المراد مذاكرتها</span></li>
						<li>
		    				<div class="selectQues clearfix">
								<input type="hidden" name="count" value="10">
		                        <div class="Count clearfix">
		                            <span class="plus">+</span>
		                            <strong>10</strong>
		                            <span class="minus">-</span>
		                        </div>
		    				</div>
						</li>
						
						<li>
							<div class="checkItem">
								<input type="checkbox" name="random" value="1" />
								اختيار أسئلة عشوائية
							</div>
						</li>
						<li>
							<div class="checkItem th1">
								<input type="checkbox" name="old" value="1" />
								اسئلة سابقة
							</div>
						</li>
						<li class="active">
							<div class="checkItem th1 checked">
								<input type="checkbox" name="repeat" checked value="1" />
								لا أريد تكرار الأسئلة السابقة
							</div>
						</li>
					</ul>

					<button type="submit" class="btnForm">ابدأ المذاكرة</button>
	    		</div>
	    		
    		</form>
            @endforeach    		
	    </div>

  @endsection


@section('custom_javascript')	
 <script type="text/javascript"> 
	$(document).ready(function(){
		$('form').submit(function(){
			var skl = $(this).find('.skl:checked').length ;

			if( skl == 0 ){
				$.toast({
					heading: 'حطا',
					text: 'برجاء أختيار مهارة واحده على الأقل',
					showHideTransition: 'slide',
					icon: 'error',
					position : 'top-right',
					textAlign: 'right',
					hideAfter: 3000
				}) ;
				return false ;
			}

			// console.log( $(this).serialize() ); return false ;

			return true ;	
		});
	});
 </script>
@endsection