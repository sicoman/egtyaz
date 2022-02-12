
  @extends('layouts.dashboard')

  @section('Content')

    @include('frontend.includes.topPartDashboard')

	@include('frontend.includes.breadCrumbDashboard')

    <div class="minHeight600 quesPage">
    		 <img src="/frontend/images/bgQues.png" class="bgQues">
    		<div class="testStyle clearfix">
    			<a href="#" class="title"> <span id="timer" class="text-success">00:00</span> <span id="Alltimer" class="hide text-danger">00:00</span> </a>
				<ul class="date clearfix">
					<li><i class="flaticon-wall-clock"></i> زمن الإجابة :  {{ $exam->available_time / 60 }} دقيقة </li>
					<li><i class="flaticon-information"></i>عدد الأسئلة : {{ $exam->questions_count }}  </li>
				</ul>
    		</div>

    		<form class="question">

    			<div id="RenderQuestion"></div>

				<div class="btnsForm">
					<a class="btnForm getNext" >السؤال التالى</a>
                    <a class="btnForm btn btn-success getFinish" data-toggle="modal" data-target="#modalQues"> انهاء الاختبار </a>
					<button class="btnForm prevBtn getPrev">السؤال السابق</button>
				</div>


    		</form>

	    </div>


        <div id="modalQues" class="modalQues modalStyle3 modal fade order-box" role="dialog">
          <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="/frontend/images/038-rocket.png" />
                        <div class="desc">
                            تم إتمام الاختبار بنجاح
                        </div>
                        <a href="#" class="btnForm examResult">اظهر النتيجة</a>
                    </div>
                </div>
            </div>
        </div>


 @endsection

  @section('custom_javascript')

    <script type="text/javascript">

        var Xm       = {!! json_encode( $questions ) !!} ;

        var Exam     = {!! json_encode( $exam ) !!} ;

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

        function nextQuestion(){
            Current = Current + 1 ;
            if( Current == 1 ){
                $('.getPrev').hide();
            }
        }
        function prevQuestion(ret = 1){
            Current = Current - ret ;
            if( Current == 0 ){
                $('.getPrev').hide();
            }
        }

        function AnswerThisQuestion(question , answer , is_true , timeUsed ){

            Answered.push({
                'id' : question ,
                'answer' : answer ,
                'is_true'  : is_true ,
                'time' : timeUsed
            }) ;
        }

        function getTrue(){
            var Cur  ;
            if( !CurrentQuestion ){ console.log( [CurrentQuestion , Current , isFinished] ); return false;  }
            Xm.map(function(qu){
                if( CurrentQuestion.id == qu.id ){
                    if( qu.itrue && qu.itrue.hasOwnProperty('id') ){
                        Cur = qu.itrue.id ;
                    }
                }
            });

            return Cur ;
        }

        function getAnswer(qq){
            var Ans  ;
            Answered.map(function(qu){
                if( qq == qu.id ){
                    Ans = qu.answer ;
                }
            });
            return Ans ;
        }

        function saveAnswer(){
             let qq = $('h2.titleQues').data('question') ;
             var sel = 0 ; var tr = false ;
             var is_sel = $('div.answers div.answer.active').length ;
             if( is_sel > 0 ){
                var sel = parseInt( $('div.answers div.answer.active').data('id') ) ;
                var tru = getTrue();
                if( sel == tru ){
                    tr = true ;
                }else{
                    tr = false ;
                }
                AnswerThisQuestion( qq , sel , tr , 0 ) ;
             }else{
                 return false ;
             }
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
                var chosen_answer = getAnswer( question.id ) ;

                question.answers.map( function( answer  ){
                    var acct = '' ; if( chosen_answer == answer.id ){ acct = 'active' ; }
                    answers = answers+`<div class="answer `+acct+`" data-id="`+answer.id+`">
                            <span class="title">(`+i+`)</span>
                            <span class="content"> `+answer.text+` </span>
                        </div>` ;
                    i++ ;
                }) ;

            }else{
                answers = answers + `<p class="alert alert-danger"> عفوا لا يوجد أجابات فى هذا السؤال ( ! ) </p>` ;
            }
            var st = '<a href="#" id="stopTimer">إيقاف الاختبار <i class="flaticon-stop"></i></a>' ;
            if( Exam.type != 'free'){
                st = '' ;
            }

            $('#RenderQuestion').html(`
            <div class="headQues clearfix">
    				<span class="numbOfQ">السؤال رقم:  `+Current+` /  `+Exam.questions_count+` </span>
    				<div class="btnsHead">
    					<a href="#" class="hide hidden ">تخطي هذا السؤال <i class="flaticon-log-out"></i></a>
    					`+st+`
    				</div>
    				<h1 class="titleHead"> </h1>
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

        var ExamSeconds = 0 ; var interval ; var stopped = 0 ;

        $(document).on( 'click' , '#stopTimer' , function(){
                if( $(this).is('.btn-warning') ){
                     stopped = 0 ;
                     $(this).html('إيقاف الاختبار <i class="flaticon-stop"></i>');
                }else{
                     stopped = 1 ;
                     $(this).text('استكمال الاختبار');
                }
                $(this).toggleClass('btn btn-warning');
                return false ;
        });


        function AllTimer(dis){
            var min, sec;
            setInterval(function () {
                if( stopped == 1 ){
                     return false ;
                }
                min = parseInt(ExamSeconds / 60, 10);
                sec = parseInt(ExamSeconds % 60, 10);

                min = min < 10 ? "0" + min : min;
                sec = sec < 10 ? "0" + sec : sec;
                dis.text(min + ":" + sec);

            }, 1000);
        }

        function goFinish(){

                var already_answered = [] ;

                Answered.map( function( q ){
                    already_answered.push( q.id ) ;
                }) ;

                //console.log( 'already_answered' ) ;
                //console.log( already_answered ) ;


                Xm.map(function(q){
                    if( !already_answered.includes(q.id) ){
                        Answered.push({
                            'id' : q.id ,
                            'answer' : 0 ,
                            'is_true'  : -1 ,
                            'time' : 0
                        })  ;
                    }
                }) ;


                //console.log(Answered) ;
                //return false ;

                $.post( "{{ route('saveExam' , $exam->id ) }}" , { 'answers' : Answered } , function(d){
                    $('#modalQues').modal('show') ;
                    $('.examResult').attr('href' , "{{ route('ExamResult' , '') }}/"+d  ) ;
                    setTimeout( () => {
                       // window.location.href = "{{ route('ExamResult' , '') }}/"+d  ;
                    } , 1500) ;
                    $('.getNext,.getPrev,.getFinish').remove() ;
                }) ;
        }

        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
                interval = setInterval(function () {

                 if( stopped == 1 ){
                     return false ;
                 }

                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                if( timer >= 0 ){
                    display.text(minutes + ":" + seconds);
                }else{

                    // Timer is finished  Lets finish this exam
                    if( Exam.type == 'free' && 1 == 0 ) {
                        $('#Alltimer').removeClass('hide') ;
                        $('#timer').addClass('hide') ;

                    }else{
                        stopped = 1 ;
                        $.toast({
                            heading: 'وقت الاختبار',
                            text: ' عفوا / أنتهى وقت الأختبار ',
                            showHideTransition: 'slide',
                            icon: 'error',
                            position : 'top-right',
                            textAlign: 'right',
                            hideAfter: 2500
                        }) ;
                        goFinish() ;
                        return false ;
                    }

                }

                ExamSeconds++ ;

                if (--timer < 0) {
                    //timer = duration;
                    //clearInterval(interval) ;
                }
            }, 1000);
        }

        $(document).ready(function(){

            AllTimer( $('#Alltimer') ) ;
            RenderQuestion() ;

            $('.getPrev,.getFinish').hide() ;

            $('.getNext').click(function(){
                if( stopped == 1 ){
                    alert('الاختبار متوقف حاليا لحين الاستكمال') ;
                    return false;
                }

                saveAnswer() ;

                RenderQuestion() ;
                if( Current == 1 ){
                    $('.getPrev').hide();
                }else{
                    $('.getPrev').show() ;
                }


                if( Current == Xm.length  ){
                    $('.getFinish').show();
                    $('.getNext').hide();
                }else{
                    $('.getFinish').hide() ;
                    $('.getNext').show();
                }

                return false;

            });

            $('.getPrev').click(function(){
                if( stopped == 1 ){
                    alert('الاختبار متوقف حاليا لحين الاستكمال') ;
                    return false;
                }
                prevQuestion(2);
                saveAnswer() ;
                RenderQuestion() ;

                if( Current == Xm.length  ){
                    $('.getFinish').show();
                    $('.getNext').hide();
                }else{
                    $('.getFinish').hide() ;
                    $('.getNext').show();
                }

                return false;
            });



            $('.getFinish').click(function(){

                saveAnswer() ;
                // Save to Database

                goFinish() ;

                return false ;
            });

            var time = {{ $exam->available_time ?? 300 }} , display = $('#timer') ;
            startTimer( time , display);

            $(document).on('click' , 'div.answer' , function(){
                $('div.answers div.answer').removeClass('active') ;
                $(this).addClass('active') ;
                return false ;
            }) ;



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

        h2 img , .QuestionAttachments img {
            max-width:100% !important;
        }
    </style>




  @endsection
