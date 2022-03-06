<!DOCTYPE html>
<head>

   <meta charset="UTF-8" />
    <!-- IE Compatibility Meta -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- First Mobile Meta  -->
   <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$meta['title']}}</title>
    <meta name="description" content="{{$meta['description']}}">
    <meta name="keywords" content="{{$meta['keywords']}}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('/frontend/css/flaticon.css') }}" rel='stylesheet' type='text/css'>
    <link href="{{ asset('/frontend/css/font.css') }}" rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="{{ asset('/frontend/css/jquery-ui.css') }}" />
	<link rel="stylesheet" href="{{ asset('/frontend/css/jquery.bxslider.css') }}" />
	<link rel="stylesheet" href="{{ asset('/frontend/css/animate.css') }}" />
	<link rel="stylesheet" href="{{ asset('/frontend/css/intlTelInput.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/bootstrap-rtl.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/responisve.css') }}" />
    <link rel="stylesheet" href="{{ asset('/frontend/css/jquery.toast.min.css') }}" />

    @yield('custom_css')

   <style>
   .jq-toast-wrap{
       width: 500px;
   }

   .close-jq-toast-single{
    position: absolute;
    top: 8px;
    right: 10px;
    font-size: 25px;
    cursor: pointer;
   }

   .jq-toast-single h2{
       font-size: 18px;
       margin-right: 20px;
       font-family: "Tajawal-Bold";
   }
   .jq-toast-single{
    font-size: 16px;
    line-height: 30px;
    font-family: "Tajawal-Bold";
   }
     .is-invalid{
         border: 1px red solid;
     }
     .invalid-feedback{
        margin-top: -20px;
        display: block;
        line-height: 20px;
        font-size: 15px;
        padding: 10px 0;
        color: red;
}

.loader{
    border: 5px solid #f3f3f3;
    border-top-color: rgb(243, 243, 243);
    border-top-style: solid;
    border-top-width: 5px;
    -webkit-animation: spin 1s linear infinite;
    animation: spin 1s linear infinite;
    border-top: 5px solid #54678D;
    border-radius: 50%;
    width: 50px;
    height: 50px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
     }
   </style>

   <!--[if lt IE 9]>
       <script src="js/html5shiv.min.js"></script>
       <script src="js/respond.min.js"></script>
   <![endif]-->

    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '383574570164050');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=383574570164050&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Meta Pixel Code -->
</head>
