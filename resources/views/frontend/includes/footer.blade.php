
	    <footer class="footer">
	    	<div class="container-fluid">
	    		<div class="sectionRight">
		    		<p class="copyFooter">تصميم وبرمجة : <a href="http://egyptyhost.com">Egyptyhost.com</a></p>
		    		<ul class="listFooter clearfix">
		    			<li><a href="{{ route('page' , 'about') }}">من نحن</a></li>
		    			<li><a href="{{ route('page' , 'terms') }}">شروط الاستخدام</a></li>
		    			<li><a href="{{ route('page' , 'privacy') }}">الخصوصية</a></li>
		    			<li><a href="{{ route('page' , 'faq') }}">أسئلة وأجوبة</a></li>
		    			<li><a href="{{ route('contact') }}">اتصل بنا</a></li>
		    		</ul>
	    			<ul class="listImgs clearfix">
	    			<li><img src="{{ asset('/frontend/images') }}/payment1.png" /></li>
	    			<li><img src="{{ asset('/frontend/images') }}/payment2.png" /></li>
	    			<li><img src="{{ asset('/frontend/images') }}/payment3.png" /></li>
	    			<li><img src="{{ asset('/frontend/images') }}/payment4.png" /></li>
	    			<li><img src="{{ asset('/frontend/images') }}/payment5.png" /></li>
	    			<li><img src="{{ asset('/frontend/images') }}/payment6.png" /></li>
	    		</ul>
				</div>
				<div class="sectionLeft">

					<ul class="socialFooter clearfix">
						<li><a href="{{ $options['social']['facebook'] ?? '' }}" class="fa fa-facebook"></a></li>
						<li><a href="{{ $options['social']['twitter'] ?? '' }}" class="fa fa-twitter"></a></li>
						<li><a href="{{ $options['social']['instagram'] ?? '' }}" class="fa fa-instagram"></a></li>
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

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<a href="https://wa.link/qejili" class="float" target="_blank">
			<i class="fa fa-whatsapp my-float"></i>
		</a>

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
    <script src=" {{ asset('/frontend/js/custom.js') }}"></script>
    @yield('custom_javascript')
	<script>
		$(function(){
			@if(session()->has('toast-success'))
				$.toast({
					heading: 'رسالة تنبية',
					text: '{!! session("toast-success") !!}',
					showHideTransition: 'slide',
					icon: 'success',
					position : 'top-right',
					textAlign: 'right',
					hideAfter: {!! session("toast-time") ? session("toast-time") : 3000 !!}
				})
			@endif

			@if(session()->has('errors'))
				@foreach( session('errors') as $error )
					$.toast({
						heading: 'رسالة تنبية',
						text: '{!! $error !!}',
						showHideTransition: 'slide',
						icon: 'error',
						position : 'top-right',
						textAlign: 'right',
						hideAfter: {!! session("toast-time") ? session("toast-time") : 3000 !!}
					});
				@endforeach
			@endif
		});
	</script>

	<style>
		.pagiDiv .pagination li span {
			display: inline-block;
			width: 40px;
			height: 40px;
			line-height: 40px;
			text-align: center;
			color: #000;
			border: none;
			padding: 0;
			font-family: "Tajawal-Bold";
			background: none;
			border-radius: 5px;
		}
		.float{
			position:fixed;
			width:60px;
			height:60px;
			bottom:40px;
			right:40px;
			background-color:#25d366;
			color:#FFF;
			border-radius:50px;
			text-align:center;
			font-size:30px;
			box-shadow: 2px 2px 3px #999;
			z-index:100;
		}

		.my-float{
			margin-top:16px;
		}
	</style>

</body>

</html>
