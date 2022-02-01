$(function(){
	$(window).scroll(function () {

		if ($(this).scrollTop() > 100) {

			$(".topBar").slideUp();

		} else {

			$(".topBar").slideDown();
		}

	});

	$(".iconMenu").click(function () {

		$("body").addClass("overflowHidden");
		$(".header").addClass("transform");
		$(".menuMobile").fadeIn();
		$(".transformPage,.menuMobile .menuContent").addClass("active");

	});

	$(".closeX,.BgClose").click(function () {

		$("body").removeClass("overflowHidden");
		$(".header").removeClass("transform");
		$(".menuMobile").fadeOut();
		$(".transformPage,.menuMobile .menuContent").removeClass("active");

	});

	/********************************************/

	$(".header .iconMenuPc").click(function () {

		$(".header .menuPc").slideToggle();

	});

	$('body,html').on('click', function (e) {
		var container = $(".header .iconMenuPc,.header .iconMenuPc *"),
			Sub = $(".header .menuPc");


		if (!$(e.target).is(container)) {
			Sub.slideUp();
		}

	});
	
	$('.the-slider-inner').bxSlider({
		useCSS: false,
		auto: true,
		controls: true,
		pager: true,
		autoHover: true,
		responsive: true,
		nextSelector: '#slider-next',
		prevSelector: '#slider-prev',
		nextText: '<i class="flaticon-arrows"></i>',
		prevText: '<i class="flaticon-arrows"></i>',
		onSlideAfter: function (currentSlideNumber, totalSlideQty, currentSlideHtmlObject) {
			$('.active-slide').removeClass('active-slide');
			$('.the-slider-inner > li').eq(currentSlideHtmlObject + 1).addClass('active-slide');
			$(".fade-In").addClass("animated fadeIn");
			$(".fade-In-Down").addClass("animated fadeInDown");
			$(".fade-In-DownBig").addClass("animated fadeInDownBig");
			$(".fade-In-Left").addClass("animated fadeInLeft");
			$(".fade-In-LeftBig").addClass("animated fadeInLeftBig");
			$(".fade-In-Right").addClass("animated fadeInRight");
			$(".fade-In-RightBig").addClass("animated fadeInRightBig");
			$(".fade-In-Up").addClass("animated fadeInUp");
			$(".fade-In-UpBig").addClass("animated fadeInUpBig");
		},
		onSlideBefore: function () {
			$(".fade-In").removeClass("animated fadeIn");
			$(".fade-In-Down").removeClass("animated fadeInDown");
			$(".fade-In-DownBig").removeClass("animated fadeInDownBig");
			$(".fade-In-Left").removeClass("animated fadeInLeft");
			$(".fade-In-LeftBig").removeClass("animated fadeInLeftBig");
			$(".fade-In-Right").removeClass("animated fadeInRight");
			$(".fade-In-RightBig").removeClass("animated fadeInRightBig");
			$(".fade-In-Up").removeClass("animated fadeInUp");
			$(".fade-In-UpBig").removeClass("animated fadeInUpBig");
		},
		onSliderLoad: function () {
			$('.the-slider-inner > li').eq(1).addClass('active-slide');
		}
	});
	
	var slider = $('.sliderVid');

	slider.owlCarousel({
		loop: false,
		nav: true,
		rtl: true,
		autoplay: true,
		responsive: {
			0: {
				items: 1,
				stagePadding: 0,
				margin: 0
			}
		}
	});


	/********************************************/


	var OwlTop = $('#OwlTop');

	OwlTop.owlCarousel({
		loop: false,
		nav: true,
		rtl: true,
		autoplay: false,
		responsive: {
			0: {
				items: 1,
				stagePadding: 0,
				margin: 0
			},
			700: {
				items: 2,
				stagePadding: 0,
				margin: 40
			},
			992: {
				items: 3,
				stagePadding: 0,
				margin: 40
			}
		}
	});

	$(".selectmenu").selectmenu();
	$("#selectYear").selectmenu().selectmenu( "menuWidget" ).addClass( "yearStyle" );


	/****** Start  countTo ******/

	$('.counts').one('inview', function (event, visible) {

		if (visible === true) {
			/****** Start  countTo******/
			$('.numb').countTo();
			/****** End  countTo******/
		}

	});

	/****** End  countTo ******/

	/* WOW Js */
	new WOW().init();


	$(".menuCpanel .iconMenuCpanel,.cpanelStyle.activeMenu .bgOpacity").click(function () {
		$(".menuCpanel").toggleClass("active");
		$(".cpanelStyle").toggleClass("activeMenu");
		$("body").toggleClass("overflowH");

	});


	/****** start nice scroll ******/

	$(".linksCpanel").niceScroll({
		cursorwidth: 6,
		cursorborder: 0,
		cursorcolor: '#707d94',
		zindex: 1500,
		horizrailenabled: false

	}).resize();

	/********************************************/

	// left: 37, up: 38, right: 39, down: 40,
	// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
	var keys = { 37: 1, 38: 1, 39: 1, 40: 1 };

	function preventDefault(e) {
		e.preventDefault();
	}

	function preventDefaultForScrollKeys(e) {
		if (keys[e.keyCode]) {
			preventDefault(e);
			return false;
		}
	}

	// modern Chrome requires { passive: false } when adding event
	var supportsPassive = false;
	try {
		window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
			get: function () { supportsPassive = true; }
		}));
	} catch (e) { }

	var wheelOpt = supportsPassive ? { passive: false } : false;
	var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';


	$(".menuCpanel .linksCpanel").hover(function () {
		window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
		window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
		window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
		window.addEventListener('keydown', preventDefaultForScrollKeys, false);
	}, function () {
		window.removeEventListener('DOMMouseScroll', preventDefault, false);
		window.removeEventListener(wheelEvent, preventDefault, wheelOpt);
		window.removeEventListener('touchmove', preventDefault, wheelOpt);
		window.removeEventListener('keydown', preventDefaultForScrollKeys, false);
	});

	/****** End nice scroll ******/

	$(".headProfile .settings .parentLi .iconStyle").click(function () {

		$(this).siblings().slideToggle();

	});


	$('body,html').on('click', function (e) {
		var container = $(".parentLi .listSettings,.settings .parentLi .iconStyle,.settings .parentLi .iconStyle *"),
			Sub = $(".headProfile .settings .parentLi .listSettings");


		if (!$(e.target).is(container)) {
			Sub.slideUp();
		}


	});

	/********************************************/

	$(window).load(resizeMenu);
	
	function resizeMenu() {

		if ($(window).width() < 1200) {

			$(".menuCpanel").removeClass("active");
			$(".cpanelStyle").removeClass("activeMenu");
			$("body").removeClass("overflowH");

		}

	}

	/********************************************/

	$('.first.circle').circleProgress({
		value: 1
	}).on('circle-animation-progress', function (event, progress) {
		$(this).find('strong').html('<i>%</i>' + Math.round($(this).attr('data-percent') * progress));
	});

	$('.second.circle').circleProgress({
		value: 0.7
	}).on('circle-animation-progress', function (event, progress) {
		$(this).find('strong').html('<i>%</i>' + Math.round(70 * progress));
	});

	$('.third.circle').circleProgress({
		value: 0.6
	}).on('circle-animation-progress', function (event, progress) {
		$(this).find('strong').html('<i>%</i>' + Math.round(60 * progress));
	});

	var data = {
		labels: [
			"Red",
			"Blue",
			"Yellow"
		],
		datasets: [
			{
				data: [52.4, 31.2, 16.4],
				backgroundColor: [
					"#4ED8DA",
					"#C04DD8",
					"#E06950"

				]
			}]
	};


	/********************************************/


	/********************************************/
	
	if ($("#phone").length > 0) {

		var input = document.querySelector(".phone");
		window.intlTelInput(input, {
			//allowDropdown: false,
			//autoHideDialCode: false,
			//autoPlaceholder: "off",
			//dropdownContainer: document.body,
			// excludeCountries: ["sn"],
			// formatOnDisplay: false,
			// geoIpLookup: function(callback) {
			//   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			//     var countryCode = (resp && resp.country) ? resp.country : "";
			//     callback(countryCode);
			//   });
			// },
			// hiddenInput: "full_number",
			// initialCountry: "auto",
			// localizedCountries: { 'de': 'Deutschland' },
			// nationalMode: false,
			// onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			//placeholderNumberType: "none",
			// preferredCountries: ['cn', 'jp'],
			// separateDialCode: true,
			utilsScript: "js/utils.js",
		});
	}

	/********************************************/

    

	$(".Count span.plus").click(function () {

		var x = parseInt( $(this).parents(".Count").filter(':first').find("strong").text() ) ;
		
		if( isNaN(x) ) {
			x = 10 ;
		}
		x = x+5;
		$(this).parents(".Count").filter(':first').find("strong").text(x);
		$(this).parents(".Count").filter(':first').prev("input").val(x);
	});

	$(".Count span.minus").click(function () {
		var x = parseInt( $(this).parents(".Count").filter(':first').find("strong").text() ) ;
		if( isNaN(x) ) {
			x = 10 ;
		}
		
		if (x > 5) {
			x = x-5;
			$(this).parents(".Count").filter(':first').find("strong").text(x);
			$(this).parents(".Count").filter(':first').prev("input").val(x);
		}
	});


	$(".question .answers .answer").click(function () {
		$(this).addClass("active").siblings().removeClass("active");
	});

	/********************************************/

	$('.modalQues .circleModal.circle').circleProgress({
		value: 0.36
	}).on('circle-animation-progress', function (event, progress) {
		$(this).find('strong').html('<i>%</i>' + Math.round(36 * progress));
	});

	/********************************************/

	if($('#Timer').length){

		var timeLeft = 30;
		    var elem = document.getElementById('Timer');
		    
		    var timerId = setInterval(countdown, 1000);
		    
		    function countdown() {
		      if (timeLeft === -1) {
		        clearTimeout(timerId);
		        
		      } else {
		      	if(timeLeft <= 9 ){
		      		elem.innerHTML = "0"+timeLeft;
		       } else {
		       	elem.innerHTML = timeLeft;
		       }
		        timeLeft--;
		      }
		    }
		    
	    }
	
		$(".modalStyle2 #openText").click(function() {
			var id = $(this).attr("id");
			$(this).parents(".details").slideUp();
			$("."+id).slideDown();
		});


		$('body,html').on('click', function (e) {
			var container = $(".modalStyle2 .details,.modalStyle2 .details *");
			if (!$(e.target).is(container)) {
				$(".modalStyle2 .details").slideDown();
				$(".openText").slideUp();
			}
	
		});
		
		
		$(".selectMaterial .textSelect").click(function() {
			$(".selectMaterial  .listSelect").slideUp();
			$(this).next().slideToggle();
		});
		
		$('body,html').on('click', function (e) {
			var container = $(".selectMaterial .textSelect,.selectMaterial .textSelect *,.selectMaterial  .listSelect,.selectMaterial  .listSelect ul *,.selectMaterial  .listSelect ul"),
				Sub = $(".selectMaterial  .listSelect");
	
	
			if (!$(e.target).is(container)) {
				Sub.slideUp();
			}
	
		});
		
		$(".selectMaterial  .listSelect ul li").click(function() {
			if($(this).hasClass("checked")) {
				$(this).removeClass("checked");
			} else {
				$(this).addClass("checked").siblings().removeClass("checked");
			}
		});
		
		
		$(".selectMaterial .listSelect .btn").click(function () {
			
			var id = $(this).parents(".selectMaterial").attr("id");
			var val = $("#"+id+ " .listSelect li.checked").attr("val");
			var defVal = $("#"+id+ " .textSelect").attr("defval");
			
			if ($("#"+id+ " .listSelect .checked").length > 0) {
				
				$("#"+id+ " .textSelect").text(val);
				$("#"+id+ " input").val(val);
				
			} else {
				$("#"+id+ " .textSelect").text(defVal);
				$("#"+id+ " input").val("");
			}
			
		});
		
		$(".materialAcc .content .selectSkill,.materialAcc .content .checkItem").click(function() {
			if($(this).children('input').attr('name') == "old"){
				$('.checkItem input[name="old"]').each(function(){
					$(this).parent().removeClass("checked");
					$(this).removeAttr('checked'); 
				});
			}
			if($(this).hasClass("checked")){
				$(this).removeClass("checked");
				$(this).children("input").removeAttr('checked'); 
			} else {
				$(this).addClass("checked");
				$(this).children("input").attr('checked', "checked");  
			}
		});
		
		$(".askDiv .openAcc").click(function () {
			
			$(this).parents(".askDiv").siblings(".content").slideToggle();
			
		});

	$(".visaImages .col-xs-4,.visaImages .col-xs-8").click(function () {
		$(this).addClass("active").siblings().removeClass("active");
	});

   $('#datepicker').datepicker( {changeYear: false, dateFormat: 'dd/mm'});
	
	$(".reward2 .openShare").click(function () {
		$(this).siblings().slideToggle();
	});
	  
	$('body,html').on('click', function(e) {
		var container = $(".reward2 .openShare,.listShare *,.reward2 .listShare"),
		Sub = $(".reward2 .listShare");
		

	    if( !$(e.target).is(container)  ){
	        Sub.slideUp();
	    }
	

	});
	
	
	$(".questionS .quesS").click(function() {
		$(this).toggleClass("active");
	});
	
	$(".selectPilot .item .selectList li").click(function() {
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	$('.rateBody .line').one('inview', function (event, visible) {

		if (visible === true) {
			/****** Start  countTo******/
			var  data  =  $(this).attr("progress-width");
			$(this).animate({
				
				width: data + "%"
				
			});
			
		}

	});
	


});


	/***********************Copy Text***************************************/
		function copyToClipboard(element) {
		  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val($(element).text()).select();
		  document.execCommand("copy");
		  $temp.remove();
		}
	/**************************************************************/

	$(".rewards .copyStyle .linkDiv .copy").click(function () {
		$(".messageCopy").addClass("active");
		setTimeout(function(){ $(".messageCopy").removeClass("active"); }, 2000);
		 
	});

	/**************************************************************/
	//By backend Gaber

	function readImageURL(input) {
		if (input.files && input.files[0]) {
		  var reader = new FileReader();
		  
		  reader.onload = function(e) {
			$('#avatar').attr('src', e.target.result);
		  }
		  
		  reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	  }
	$('.userAvatar').on('change', function(e){
		var files = e.target.files;
		var data = new FormData();
		var obj = this;
		data.append("avatar", files[0]);
		$.ajax({	
			url: $(this).attr('data-url'),  
			dataType: 'json',  
			cache: false,
			contentType: false,
			processData: false,
			data,  
			headers: {
				'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
			},                  
			type: 'post',
			complete: function(){ 
				readImageURL(obj);
			}
		 });
	});
   	














