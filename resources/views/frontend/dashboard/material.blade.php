
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	
        <div class="askTeacher materials minHeight600">
    	
    		<form class="materialAcc active">
    			
	    		<div class="askDiv">
	    			<div class="imgDiv">
		    			<img class="iconImg" src="images/036-reading.png" />
		    			<img class="check" src="images/check.png" />
	    			</div>
	    			<div class="details">
	    				<h2 class="title openAcc">المادة اللفظية</h2>
	    				<div class="desc">
	    					تحتوي المادة اللفظية على كم هائل من الأسئلة مع احصائيات النتيجة النهائية
	    				</div>
	    				<a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
	    			</div>
	    		</div>
	    		
	    		<div class="content">
	    			<h2 class="titleContent">حدد المهارات المطلوبة لك</h2>
	    			<div class="row total">
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">أكمل الآتي <input type="checkbox" /></div>
	    				</div>
	    				
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">اكمل الإجابات التالية <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				<div class="col-md-4">
	    					<div class="selectSkill">بم تفسر <input type="checkbox" /></div>
	    				</div>
	    				
	    				
	    			</div>
					<h2 class="titleContent">حدد الطريقة المناسبة لعرض السؤال</h2>
					<ul class="checkList clearfix">
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
						
						<li><div class="checkItem"><input type="checkbox" />اختيار أسئلة عشوائة</div></li>
						<li><div class="checkItem"><input type="checkbox" />اسئلة سابقة</div></li>
						<li><div class="checkItem"><input type="checkbox" />لا أريد تكرار الأسئلة السابقة</div></li>
					</ul>

					<button class="btnForm">ابدأ المذاكرة</button>
	    		</div>
	    		
    		</form>
    		
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="images/044-test tube.png" />
    			</div>
    			<div class="details">
    				<a href="#" class="title">مادة الكمي</a>
    				<div class="desc">
    					مادة الكمي يمكنك الإجابة على جميع الأسئلة وعدد لا حصر له
    				</div>
    				<a href="#"data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
    			</div>
    		</div>
    		
	    </div>
 @endsection