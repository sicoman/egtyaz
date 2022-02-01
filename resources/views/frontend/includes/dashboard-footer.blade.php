	    <footer class="footer">
	    	<div class="container-fluid">
	    		<div class="sectionRight">
	    			
		    		<p class="copyFooter">تصميم وبرمجة : <a href="#">Egyptyhost.com</a></p>
		    		<ul class="listFooter clearfix">
		    			<li><a href="#">من نحن</a></li>
		    			<li><a href="#">شروط الاستخدام</a></li>
		    			<li><a href="#">الخصوصية</a></li>
		    			<li><a href="#">أسئلة وأجوبة</a></li>
		    			<li><a href="#">اتصل بنا</a></li>
		    		</ul>
	    			<ul class="listImgs clearfix">
	    			<li><img src="{{ asset('/frontend/images/') }}/payment1.png" /></li>
	    			<li><img src="{{ asset('/frontend/images/') }}/payment2.png" /></li>
	    			<li><img src="{{ asset('/frontend/images/') }}/payment3.png" /></li>
	    			<li><img src="{{ asset('/frontend/images/') }}/payment4.png" /></li>
	    			<li><img src="{{ asset('/frontend/images/') }}/payment5.png" /></li>
	    			<li><img src="{{ asset('/frontend/images/') }}/payment6.png" /></li>
	    		</ul>
				</div>
				<div class="sectionLeft">
					
				
					<ul class="socialFooter clearfix">
						<li><a href="#" class="fa fa-facebook"></a></li>
						<li><a href="#" class="fa fa-twitter"></a></li>
						<li><a href="#" class="fa fa-instagram"></a></li>
					</ul>
					
					<p class="copyrights">
					جميع الحقوق محفوظة لمنصة اجتياز التعليمية
					</p>
				</div>
	
	    	</div>
	    </footer>
	    
    </div>
    
    <div id="video" class="modal fade order-box" role="dialog">
          <div class="modal-dialog">
                        
            <div class="modal-content">
    
                <div class="modal-body">
    
                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/WKKyvZHWjrc" frameborder="0" allowfullscreen=""></iframe>
                    
                </div>
            </div>
        </div>
    </div>

	<style>
		.QuestionAttachments.titleQues {
			height:315px; overflow:hidden ; transition : all 1s ease;
		}
		.QuestionAttachments.titleQues:hover {
			height:auto;transition : all 1s ease;
		}
		.blink_me {
			animation: blinker 3s linear infinite;
			
		}
		.blink_me span:first-child{
			background:#cf9 !important;
		}

		@keyframes blinker {
			50% {
				opacity: 0;
			}
		}
	</style>

    
   
    <script src="{{ asset('/frontend/js/jquery-1.11.2.min.js') }}"></script>
    <script src=" {{ asset('/frontend/js/jquery.bxslider.min.js') }}"></script>
    <script src=" {{ asset('/frontend/js/jquery-ui.js') }}"></script>
	<script src=" {{ asset('/frontend/js/Chart.min.js') }}"></script>
    <script src=" {{ asset('/frontend/js/circle-progress.min.js') }}"></script>
    <script src=" {{ asset('/frontend/js/intlTelInput.js') }}"></script>
    <script src=" {{ asset('/frontend/js/bootstrap.min.js') }}"></script>
    <script src=" {{ asset('/frontend/js/owl.carousel.js') }}"></script>
    <script src=" {{ asset('/frontend/js/wow.min.js') }}"></script>
    <script src=" {{ asset('/frontend/js/jquery.inview.min.js') }}"></script>
    <script src=" {{ asset('/frontend/js/jquery.countTo.js') }}"></script>
    <script src=" {{ asset('/frontend/js/jquery.nicescroll.js') }}"></script>
    <script src=" {{ asset('/frontend/js/jquery.toast.min.js') }}"></script>

	<script> 
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	</script>					


    <script src=" {{ asset('/frontend/js/custom.js') }}"></script>

    @yield('custom_javascript')
	<script>
		$(function(){

			/*
			$('ul.checkList .checkItem.th1').click(function(){
                var ul = $(this).parents('ul').filter(':first') ;
                
                if( ul.find('.checkItem.th1.checked').length > 1 ){
                    var tthis = $(this) ;
                    ul.find('.checkItem.th1.checked').each(function(){
                        setTimeout( () => {
                            $(this).find('input').prop('checked' , false) ;
                            $(this).removeClass('checked') ;
                        }, 300 );
                    });
                    setTimeout( () => {
                            tthis.find('input').prop('checked' , true) ;
                            tthis.addClass('checked') ;
                    }, 350 );
                }
            });
			*/
			$('form ul.checkList .checkItem.th1').click(function(){
                var ul = $(this).parents('ul').filter(':first') ;
                
				ul.find('.checkItem.th1').removeClass('active checked').find('input').prop('checked' , false) ;
				ul.find('.checkItem.th1').parents('li').filter(':first').removeClass('active checked') ;

				$(this).addClass('active checked').find('input').prop('checked' , true) ;
                $(this).parents('li').filter(':first').addClass('active checked') ;

            });

			@if(session()->has('toast-success'))
				$.toast({
					heading: 'تنبيه',
					text: '{!! session("toast-success") !!}',
					showHideTransition: 'slide',
					icon: 'success',
					position : 'top-right',
					textAlign: 'right',
					hideAfter: {!! session("toast-time") ? session("toast-time") : 3000 !!}
				})
			@endif
			@if(session()->has('toast-error'))
				$.toast({
					heading: 'الأعضاء',
					text: '{!! session("toast-error") !!}',
					showHideTransition: 'slide',
					icon: 'error',
					position : 'top-right',
					textAlign: 'right',
					hideAfter: {!! session("toast-time") ? session("toast-time") : 3000 !!}
				})
			@endif
			@php request()->session()->forget(['toast-time', 'toast-success','toast-error']);   @endphp

		  	@if(session()->has('redirect_url'))
			    setTimeout(function(){ 
					window.location.replace("{!! session("redirect_url") !!}");
				 }, {!! session("redirect_after") !!});
			@endif
			@php request()->session()->forget(['redirect_url', 'redirect_after']);   @endphp

			$('ul.linksCpanel li ul').hide() ;
			$('ul.linksCpanel li a').click(function(){
				if( $(this).next('ul').length > 0 ){
					$(this).next('ul').slideToggle() ;
					return false;
				}
				return true ;
			})

		});
	</script>

<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=60687ef89269c20011a2a2af&product=sop' async='async'></script>
</body>

</html>