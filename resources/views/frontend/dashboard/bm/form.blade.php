<html>
    <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://banquemisr.gateway.mastercard.com/checkout/version/57/checkout.js"
                data-complete="completeCallback"
                data-error="errorCallback"
                data-cancel="cancelCallback"
        >
        </script>
        
    </head>
    <body style="display:none">
        
    <script type="text/javascript">
            function errorCallback(error) {
                  alert('error') ;
                  alert( JSON.stringify(error) );
            }
            function cancelCallback() {
                  alert('cancel') ;
                  alert('Payment cancelled');
            }
            function completeCallback(result , session){

                alert('Success');
                console.log([result , session]) ; 

                /*
                 window.href.location = "https://egtyaz.com" ;
                $('body').addClass('complete') ;

                alert('complete') ;
                alert('Payment Completed');
                */
            }

            Checkout.configure({
                session: { 
            	    id: '{{ $session->session->id }}'
       			},
                interaction: {
                    merchant: {
                        name: 'MERCHANT NAME',
                        address: {
                            line1: '200 Sample St',
                            line2: '1234 Example Town'            
                        }    
                    }
               }
            });
   
         $(document).ready(function(){
             setTimeout( function(){
                if( !$('body').hasClass('complete') ) {
                    Checkout.showPaymentPage();
                }
             });
         });
    </script>
    </body>
</html>