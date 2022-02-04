
  @extends('layouts.dashboard')

  @section('Content')

    @include('frontend.includes.topPartDashboard')

	@include('frontend.includes.breadCrumbDashboard')

    <div class="minHeight600 quesPage">

    		 <img src="/frontend/images/bgQues.png" class="bgQues">

             <div class="headQues testStyle clearfix">
    				<h1 class="titleHead">
                        {{ $subjects->name ?? '' }}
                    </h1>
    		  </div>

    		<form class="question">

    			<div id="RenderQuestion"></div>

				<div class="btnsForm">
					<a class="btnForm getNext" >السؤال التالى</a>
                    <a class="btnForm btn btn-success getFinish" data-toggle="modal" data-target="#modalQues"> انهاء المذاكرة </a>
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
                            تم إتمام المذاكرة
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a class="hide hidden openYes" data-toggle="modal" data-target="#modalYes">  </a>
        <a class="hide hidden openNo" data-toggle="modal" data-target="#modalNo">  </a>

        <div id="modalYes" class="modalQues modalStyle2 modal fade order-box in" role="dialog" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                            <div class="details">
                                <img src="/frontend/images/048-trophy.png" />
                                <p class="message green">الإجابة صحيحة</p>
                                <a href="#" class="getNext mo btnStyle2">السؤال التالي</a>
                                <a href="#" class="getNext mo btnStyle2" style="display:none">انهاء المذاكرة</a>
                                <a href="#" class="btnStyle2" id="openText">راجع المفهوم</a>
                            </div>
                            <div class="openText" style="display: none;">راجع المفهوم من خلال لوحة التحكم يتم ارسال شرح  السؤال سواء تيكست او فيديو او عرض أي نوع ملفات  بي دي اف صورة فيديو وورد يرجى توفير جميع الفورمات  للعرض المدخل سابقاً لكي يتم عرضة للطالب</div>
                        </div>
                </div>
            </div>
        </div>

        <div id="modalNo" class="modalQues modalStyle2 modal fade order-box in" role="dialog" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="details">
                            <img src="/frontend/images/043-testing.png">
                            <p class="message orange">الإجابة خاطئة</p>
                            <a href="#" class="getPrev mo btnStyle2"> أعد المحاولة </a>
                            <a href="#" class="getNext xnext mo btnStyle2">السؤال التالي</a>
                            <a href="#" class="getPrev mo mo2 btnStyle2"> الأجابة الصحيحة </a>
                            <a href="#" class="btnStyle2" id="openText">راجع المفهوم</a>
                        </div>
                        <div class="openText" style="display: none;">راجع المفهوم من خلال لوحة التحكم يتم ارسال شرح  السؤال سواء تيكست او فيديو او عرض أي نوع ملفات  بي دي اف صورة فيديو وورد يرجى توفير جميع الفورمات  للعرض المدخل سابقاً لكي يتم عرضة للطالب</div>
                    </div>
                </div>
            </div>
        </div>

    </div>


 @endsection

  @section('custom_javascript')

    <script type="text/javascript">

        var Xm       = {!! json_encode( $questions ) !!} ;

        var Exam     = {!! json_encode( $request ) !!} ;

        var Answered = [] ;

        var Current  = 0 ;

        var Times    = {} ;

        var CurrentQuestion ;

        var isFinished = false ;




        function getFromQuestions(n = 1){
            if( isFinished ){ return false ; }
            CurrentQuestion = Xm[Current];
            $('#Current').text(Current) ;
            if( n == 1 ){
                Current++ ;
            }
            return CurrentQuestion ;
        }

        function nextQuestion(){
            Current = Current + 1 ;
            if( Current == 1 ){
                $('.getPrev.btnForm').hide();
            }
        }
        function prevQuestion(ret = 1){
            Current = Current - ret ;
            if( Current == 0 ){
                $('.getPrev.btnForm').hide();
            }

        }

        function AnswerThisQuestion(question , answer , is_true , timeUsed ){

            $('#modalNo').hide().modal('hide') ;
            $('#modalYes').hide().modal('hide') ;
            if( is_true == 1 ) {
                $('#modalYes').show().modal('show') ;
                if( isFinished ){
                    $('#modalYes a.getNext').hide() ;
                    $('#modalNo a.getNext').hide() ;
                    $('#modalYes a.getNext').filter(':last').show() ;
                }
            }else{
                $('#modalNo').show().modal('show') ;
            }

            // get Exam Description
            var qs = {} ;
            Xm.map(function(q){
                if( question == q.id ) {
                    qs = q ;
                }
            }) ;

            $('div.openText').html( qs.description ) ;

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

        function saveAnswer(isLast = false){
             let qq = $('h2.titleQues').data('question') ;
             var sel = 0 ; var tr = false ;
             var is_sel = $('div.answers div.answer.active').length ;
             if( is_sel > 0 ){
                var sel = $('div.answers div.answer.active').data('id') ;
                var tru = getTrue();
                if( sel == tru ){
                    tr = true ;
                }
                AnswerThisQuestion( qq , sel , tr , 0 ) ;
                if( isLast ) {
                    isFinished = true ;
                }
             }else{
                 return false ;
             }
        }

        function RenderQuestion(n = 1){

            if( isFinished ){
                return false ;
            }

            var question = getFromQuestions(n) ;

            if( !question ){
                return false ;
            }

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

            var is_in_wish = '' ;
            if(question.in_wish_list && question.in_wish_list.hasOwnProperty('id')){
                is_in_wish = 'active' ;
            }


            $('#RenderQuestion').html(`
            <div class="headQues clearfix">
    				<span class="numbOfQ">السؤال رقم:  `+Current+` /  `+{{ $request->count }}+` </span>
    				<div class="btnsHead">
    					<a href="#" class="hide hidden ">تخطي هذا السؤال <i class="flaticon-log-out"></i></a>
    					<a href="#" data-id="`+CurrentQuestion.id+`" id="stopTimer" class="addOrRemoveInWishlist `+is_in_wish+`"> <span> اضف للمفضلة</span> <i class="flaticon-favourite"></i></a>
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
                        obj.removeClass("btn-danger").addClass("btn-success");
                        obj.addClass('active');
                        obj.find('span').text("حذف من المفضلة");
                        }else{
                        obj.removeClass('btn-success').addClass('btn-danger') ;
                        obj.removeClass('active');
                        obj.find('span').text("اضف للمفضلة");
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
                    $('#Alltimer').removeClass('hide') ;
                    $('#timer').addClass('hide') ;
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

            $('.getPrev.btnForm,.getFinish.btnForm').hide() ;

            $('.getFinish').click(function(){

                if( $(this).is('.mo') ){
                    $('#modalNo').hide().modal('hide') ;
                    $('#modalYes').hide().modal('hide') ;
                    return false;
                }

                saveAnswer( true ) ;

                $('.getPrev.btnForm').show();
                $('.getNext.btnForm').hide() ;



                return false;

            });

            $('.getNext').click(function(){
                if( stopped == 1 ){
                    alert('الاختبار متوقف حاليا لحين الاستكمال') ;
                    return false;
                }

                if(!$('div.answers div.answer.active').length ){
                    if($('.select_answer_to_continue').length){
                        return false;
                    }
                    let alert = $(`<div style="margin-top: 20px;" class="select_answer_to_continue alert alert-danger" role="alert">من فضلك قم باختيار اجابة للسؤال قبل الاستمرار</div>`);
                    $('#RenderQuestion').append(alert);
                    setTimeout(function(){
                    alert.fadeOut(500,function(){
                      alert.remove();
                    });
                    }, 2000);

                    if( $(this).is('.mo') ){
                        $('#modalNo').hide().modal('hide') ;
                        $('#modalYes').hide().modal('hide') ;
                        alert.remove();
                        return false;
                    }


                    return false;
                }

                if( $(this).is('.mo') ){
                    $('#modalNo').hide().modal('hide') ;
                    $('#modalYes').hide().modal('hide') ;
                    if( !isFinished ){
                        return false;
                    }
                }

                saveAnswer() ;

                RenderQuestion() ;
                if( Current == 1 ){
                    $('.getPrev.btnForm').hide();
                }else{
                    $('.getPrev.btnForm').show() ;
                }


                if( Current == Xm.length  ){
                    $('.getFinish.btnForm').show();
                    $('.getNext.btnForm').not('.mo').hide();
                }else{
                    $('.getFinish.btnForm').hide() ;
                    $('.getNext.btnForm').not('.mo').show();
                }

                if( isFinished ) {
                    $('#modalQues').show().modal('show') ;

                    setTimeout(function(){
                        window.location.href = '{{ route('cpanel') }}' ;
                    }, 3000 ) ;

                }else if( !$(this).is('.btnForm') ) {
                    setTimeout( function(){
                        $('#modalNo').hide().modal('hide') ;
                        $('#modalYes').hide().modal('hide') ;
                    },500);

                }

                return false;
            });

            function hideThisAnswer(){
                setTimeout(function(){
                   // $('.answer.active').hide() ;
                },200);
                $('#modalNo').hide().modal('hide') ;
            }

            function ShowValidAnswer(){
                 var q = Xm[Current]  ;

                 if( !q ){
                    q = Xm[Current-1]  ;
                 }

                 var valid = 0 ;
                 if( q.itrue.hasOwnProperty('id') ){
                     valid = q.itrue.id ;
                     console.log(valid) ;
                     setTimeout(function(){
                        $('.answer[data-id="'+valid+'"]').addClass('blink_me') ;
                     }, 300 );

                 }

                 $('#modalNo').hide().modal('hide') ;
            }


            $('.getPrev').click(function(){

                if( isFinished ){
                    prevQuestion(0);
                }else{
                    prevQuestion(2);
                }


                if( $(this).is('.mo2') ){
                    ShowValidAnswer() ;
                }else{
                    hideThisAnswer() ;
                }
                RenderQuestion() ;
                if( Current == Xm.length  ){
                    $('.getFinish.btnForm').show();
                    $('.getNext.btnForm').not('.mo').hide();
                }else{
                    $('.getFinish.btnForm').hide() ;
                    $('.getNext.btnForm').not('.mo').show();
                }

                isFinished = false ;

                return false;
            });


            var time =  500 , display = $('#timer') ;
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

        #stopTimer.active{
            background: #1F3EB4;
            color: #fff;
        }

        .getFinish{
            margin-left: 20px;
        }

        h2 img , .QuestionAttachments img {
            max-width:100% !important;
        }

    </style>




  @endsection
