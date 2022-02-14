
  @extends('layouts.dashboard')
  @section('Content')
    @include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')
        <div class="minHeight600 quesPage">
            <form  class="question">
                <div id="RenderQuestion"></div>
            </form>
	    </div>
        <div id="modalQues" class="modalQues  modal fade order-box" role="dialog">
          <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">مراجعة المفهوم</h4>
                    </div>
                    <div class="modal-body" style="width: 600px">
                        {!! strip_tags($question[0]->description, "<img><sub><sup><br><p><span><iframe>") !!}
                    </div>
                </div>
            </div>
        </div>
 @endsection

  @section('custom_javascript')

    <script type="text/javascript">

        var Xm       = {!! json_encode( $question ) !!} ;

        var Exam     = {!! json_encode( [] ) !!} ;

        var Answered = [] ;

        var Current  = 0 ;

        var Times    = {} ;

        var CurrentQuestion ;

        function getFromQuestions(){
            CurrentQuestion = Xm[Current];
            $('#Current').text(Current) ;
            Current++ ;
            return CurrentQuestion ;
        }

        function RenderQuestion(){

            var question = getFromQuestions() ;

            var att =  question.attachment ;

            if( att && att.hasOwnProperty('description') && att.description &&  att.description.length > 4 ){
                var att_html = `<div class="QuestionAttachments titleQues"><p class="alert alert-warning">`+att.file+`</p><div class="">`+att.description+`</div></div>` ;
            }else{
                var att_html = '' ;
            }

            var answers = `` ;

            var i = 1 ;

            if( question.answers.length > 0 ){

                // If Has Chosed answer
                var chosen_answer = false ;

                question.answers.map( function( answer  ){
                    var acct = '' ; if( answer.is_true == 1 ){ acct = 'active' ; }
                    answers = answers+`<div class="answer `+acct+`" data-id="`+answer.id+`">
                            <span class="title">(`+i+`)</span>
                            <span class="content"> `+answer.text+` </span>
                        </div>` ;
                    i++ ;
                }) ;

            }else{
                answers = answers + `<p class="alert alert-danger"> عفوا لا يوجد أجابات فى هذا السؤال ( ! ) </p>` ;
            }
            var st = '<a href="#"  data-toggle="modal" data-target="#modalQues" > مراجعة المفهوم <i class="flaticon-help"></i></a><br />' ;

            $('#RenderQuestion').html(`
            <div class="headQues clearfix">
    				<div class="btnsHead">
    					`+st+`
    				</div>
    				<h1 class="titleHead"> &nbsp; </h1>
                </div>
                    `+att_html+`
    			<h2 class="titleQues" data-question="`+question.id+`">
    				<i class="flaticon-copy"></i>
    				`+question.title+`
    			</h2>
    			<div class="answers">
    				`+answers+`
    			</div>
            `) ;
        }


        $(document).ready(function(){
            RenderQuestion() ;
        });

    </script>

    <style>
        #stopTimer.btn-warning{
            background:#ec971f !important;
            color:#fff !important;
        }

        .getFinish{
            margin-left: 20px;
        }
    </style>

  @endsection
