
  @extends('layouts.share') 

  @section('Content')
    
	@include('frontend.includes.breadCrumbDashboard')
		    
    <div class="askTeacher pilotDetails materials minHeight600">
    		<div class="askDiv">
    			<div class="imgDiv">
    				<img class="iconImg" src="/frontend/images/022-mortarboard.png" />
    			</div>
    			<div class="details">
    				<h2 class="title">نسبة النجاح لدى / {{ $ShareUserName ?? '' }}</h2>
				    <div data-size="120" data-fill="{&quot;color&quot;: &quot;#0047C2&quot;}" class="examCircle  circle circleNormal ">
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
						<th>الاجابة الصحيحة </th>
    					<th>كيفية الحل</th>
    				</tr>
    			</thead>
    			<tbody>
					@php 
						$answer_res = [] ;
						foreach($answers as $ans){
							$answer_res[$ans->question_id] = $ans->is_true ;
						}
					@endphp
					@foreach( $answers as $question )
					
						<tr>
							<td>{!! strip_tags( html_entity_decode ( $question->question->title) , "<img>" ) !!}</td>
							<td> 
								@if( $question->is_true == 1 )
									<i class="fa fa-check text-success"></i>
								@elseif( $question->is_true == -1 )
									لم يتم الاجابة عنه
								@else
									<i class="fa fa-times text-danger"></i> 
								@endif 
								 {!! strip_tags(html_entity_decode($question->question->text), "<img>") ?? '' !!}
							</td>
							<td> 
								 <p>{{ isset($question->question->itrue) && $question->question->itrue->text ? strip_tags(html_entity_decode($question->question->itrue->text)) : '' }}</p>
							</td>
							<td> <a href="{{ route('question' , $question->question->id ) }}" target="_blank"  class="btn btn-warning clicktoSeeDescriptionxxx" data-href="#answer-{{$question->question->id}}" > مراجعه السؤال </a> <div id="answer-{{$question->question->id}}" class="answer hide hidden">{!! $question->question->description !!}</div> </td>
						</tr>
					@endforeach
    			</tbody>
    		</table>
			</div>
			
    		<div class="pagiDiv clearfix">
    			 {{ $answers->links() }}
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
    <script>
		$(document).ready(function(){
			var res = {{ $results->percent / 100 }} ;
			$('.examCircle').circleProgress({
            value: res ,
			}).on('circle-animation-progress', function (event, progress) {
				$(this).find('strong').html('<i>{{ $results->percent  }} %</i>');
			});

			$('.clicktoSeeDescription').on('click', function(){   
				let p = $( $(this).data('href') ).html();
				$('.modal-body').html( p );
				$('#ReviewAnswer').modal('show') ;
				return false ;
			});
		});
        
    </script>
  @endsection