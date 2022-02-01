
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	  
    <div class="askTeacher pilotDetails materials minHeight600">
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="{{ asset('/frontend/images') }}/022-mortarboard.png" />
    			</div>
    			<div class="details">
    				<h2 class="title">نسبة النجاح لدى اختبار القدرات</h2>
				    <div data-percent="@if($exam->Results->first()) {{(int)$exam->Results->first()->percent}} @else 0 @endif" data-size="120" data-fill="{&quot;color&quot;: &quot;#0047C2&quot;}" class="circle circleNormal first">
				      <strong></strong>
				    </div>
				    <a href="#" data-toggle="modal" data-target="#modalShare" class="flaticon-share icon"></a>
    			</div>
    			
    		</div>
    		
    		<div class="tableDiv">
    			<table class="tableQues">
    			<thead>
    				<tr class="head">
    					<th>السؤال</th>
    					<th>الاجابة</th>
    					<th>كيفية الحل</th>
    				</tr>
    			</thead>
    			<tbody>
 
			     @foreach ($examDetails as $question)
				<tr>
					<td>{{$question->question->title}}</td>
					<td>{{$question->answer->is_true == 1 ? "الاجابة صحيحة" : "الاجابة خاطئة"}}</td>
					<td><a class="reviewQuestion" href="#">راجع المفهوم</a>
					   <p class="hidden">{{$question->question->description}}</p>
					</td>
    			</tr>
				 @endforeach  
    			</tbody>
    		</table>
			</div>
			
    		<div class="pagiDiv clearfix">
    			 {{ $examDetails->links() }}
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
	   $('.reviewQuestion').on('click', function(){   
		   let p = $('#ReviewAnswer').find('.modal-body p');
		   p.replaceWith($(this).next('p'));
		   $('.modal-body p').removeClass('hidden');
		   $('#ReviewAnswer').modal('show') ;
	   });
   });
 </script>  
 @endsection