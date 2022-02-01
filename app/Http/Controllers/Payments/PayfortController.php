<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Payfort ;

use App\Models\Booking ;
use App\Models\Options ;
use App\Models\Payments ;

use Redirect ;

use LaravelPayfort\Traits\PayfortResponse as PayfortResponse;


class PayfortController extends Controller
{
    use PayfortResponse;

    protected $egp_usd = 0 ;
    protected $booking ;

    public function __construct(){
        
    }

    protected function getData($bookingId , $currency = 'EGP' , $auth = ''){
        $data = [];

        $booking = Booking::find($bookingId) ;

        $this->booking = $booking ;

        $data['items'] = [
            [
                'name' => $booking->unit->title ,
                'price' => $this->egp_usd($booking->price, $currency ) ,
                'desc'  => $booking->unit->description ,
                'qty' => 1
            ]
        ];
        
        $data['invoice_id'] = $bookingId.'-'.time();
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
                
        $data['total'] = $this->egp_usd( $booking->price , $currency ) ;
        
        return $data ;
    }

    public function auth($bookingId = 0 , $currency = 'USD' , Request $request){

        $data     = $this->getData($bookingId , $currency , $auth = '/auth') ;

        return Payfort::redirection()->displayRedirectionPage([
            'command' => 'AUTHORIZATION',              # AUTHORIZATION/PURCHASE according to your operation.
            'merchant_reference' => $data['invoice_id'],   # You reference id for this operation (Order id for example).
            'amount' => $data['total'],                           # The operation amount.
            'currency' => $currency ,                       # Optional if you need to use another currenct than set in config.
            'customer_email' => $this->booking->user->email  #Customer  email.
        ] , 0 );

    }

    public function cancel($bookingId = 0 ,Request $request){

    }

    public function successAuth($bookingId = 0 ,Request $request){
        $request->request->add(['auth' => 1]);
        $this->success( $bookingId , $request ) ;
        return Redirect::away( env('VUE' , 'http://127.0.0.1:3000/').'user/payments/'.$bookingId );
    }

    public function calculateSignature($arrData, $signType = 'request')
    {
        $shaString             = '';
        ksort($arrData);
        foreach ($arrData as $k => $v) {
            $shaString .= "$k=$v";
        }

        if ($signType == 'request') {
            $shaString = env('PAYFORT_SHA_REQUEST_PHRASE') . $shaString . env('PAYFORT_SHA_REQUEST_PHRASE') ;
        }
        else {
            $shaString = env('PAYFORT_SHA_REQUEST_PHRASE') . $shaString . env('PAYFORT_SHA_REQUEST_PHRASE') ;
        }
        $signature = hash( env('PAYFORT_SHA_TYPE' , 'sha256') , $shaString);

        return $signature;
    }

    public function success(Request $request){

        $payfort_return = $this->handlePayfortCallback($request);


        $ref = explode('-',$payfort_return['merchant_reference']) ;

        $booking = $ref[0] ;

        if( $booking == 0 ) {
            return 0 ;
        }

        $booking = Booking::findOrFail( $booking ) ;

        $booking->auth = json_encode( $payfort_return ) ;

        $booking->update() ;

        $booking->save() ;

        if($booking->status == 0 && $booking->owner->accept == 1 ){
            try{
                $booking->status = 1 ;
                $booking->save() ;
            } catch(Exception $e){

            }
        }else{
            
        }

        $add = '' ;
        if( $payfort_return['response_message'] != 'Success' ){
            $add = '?failed=true' ;
        }

        return Redirect::away( env('VUE' , 'https://127.0.0.1:3000/').'user/payments/'.$booking->id.$add );
        
    }


    public function payNow($bookingId , Request $request){

        $booking = Booking::findOrFail( $bookingId ) ;

        if( isset($booking[0]) ){
            $booking = $booking[0] ; 
        }

        $auth = json_decode( $booking->auth ) ;
        
        if( getenv('PAYFORT_USE_SANDBOX') == true || getenv('PAYFORT_USE_SANDBOX') == 'true' ){
            $url = 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi';
        }else{
            $url = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
        }

        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $data = array(
            'command' => 'CAPTURE',
            'access_code' => $auth->access_code ,
            'merchant_identifier' => $auth->merchant_identifier,
            'merchant_reference' => $auth->merchant_reference ,
            'amount' => $auth->amount ,
            'currency' => $auth->currency ,
            'language' => $auth->language ,
            'fort_id' => $auth->fort_id ,
        );

        $data['signature'] = $this->calculateSignature( $data , 'request' ) ;

        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);

        curl_close($ch);

        $json = (array)json_decode( $result ) ;

        $paid = 0 ;

        if( $json['response_message'] == 'Success' ) {
            $paid = 1 ;
            // Lets Change Booking Status to Paid 
            $booking->status = 2 ; $booking->save() ;
        }

        Payments::create([
            'gateway' => 'payfort' ,
            'booking_id' => $booking->id ,
            'unit_id' => $booking->unit_id ,
            'user_id' => $booking->user_id ,
            'owner_id' => $booking->owner_id ,
            'cost' => $booking->price ,
            'fee' => $booking->fee ,
            'total' => $booking->price,
            'is_paid' => $paid ,
            'status' => 1
        ]) ;

        return $paid ;
    }

    public function egp_usd($mount = 1, $currency = 'USD' ){

        if( $currency == 'EGP' ) {
            return $mount ;
        }

        if( $this->egp_usd == 0 ){
            $egp = Options::where('type' , 'currency')->limit(1)->pluck('option_value' , 'option_var') ;
            $this->egp_usd = $egp['USD_EGP'] ;
        }

        return round( ($mount / $this->egp_usd ) , 2) ;

    }

}
