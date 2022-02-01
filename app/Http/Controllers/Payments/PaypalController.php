<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Srmklive\PayPal\Facades\PayPal ;

use App\Models\Booking ;
use App\Models\Options ;
use App\Models\Payments ;

use Redirect ;


class PaypalController extends Controller
{
    protected $provider ; 
    protected $egp_usd = 0 ;
    protected $booking ;

    public function __construct(){
        $this->start() ;
    }

    protected function start(){
        $this->provider = PayPal::setProvider('express_checkout');
    }

    protected function getData($bookingId , $currency = 'EGP' , $auth = ''){
        $data = [];

        $booking = Booking::find($bookingId) ;

        if( isset($booking[0]) ){
            $booking = $booking[0] ; 
        }

        $this->booking = $booking ;

        $data['items'] = [
            [
                'name' => $booking->unit->title ,
                'price' => $this->egp_usd($booking->price, $currency ) ,
                'desc'  => $booking->unit->title.' New Booking' ,
                'qty' => 1
            ]
        ];
        
        $data['invoice_id'] = $booking->id.'-'.time();
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = url('/api/payment/'.$booking->id.$auth.'/success');
        $data['cancel_url'] = url('/api/payment/'.$booking->id.'/cancel');
                
        $data['total'] = $this->egp_usd($booking->price , $currency ) ;
        
        return $data ;
    }

    public function auth($bookingId = 0 , $currency = 'USD' , Request $request){

        $data     = $this->getData($bookingId , $currency , $auth = '/auth') ;

        $provider = $this->provider ;

        $config = [
            'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'username'    => env('PAYPAL_SANDBOX_API_USERNAME', 'admin_api1.tshelat.com'),
                'password'    => env('PAYPAL_SANDBOX_API_PASSWORD', 'ZDQ7NTF6G3WBSLJB'),
                'secret'      => env('PAYPAL_SANDBOX_API_SECRET', 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-Az5pKBs-tcDFmulCVk031FMinwkN'),
                'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
                'app_id'      => '', // Used for testing Adaptive Payments API in sandbox mode
            ],
            'live' => [
                'username'    => env('PAYPAL_LIVE_API_USERNAME', ''),
                'password'    => env('PAYPAL_LIVE_API_PASSWORD', ''),
                'secret'      => env('PAYPAL_LIVE_API_SECRET', ''),
                'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
                'app_id'      => '', // Used for Adaptive Payments API
            ],
        
            'payment_action' => 'Authorization', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => $currency ,
            'billing_type'   => 'MerchantInitiatedBilling',
            'notify_url'     => 'api/payment/'.$bookingId.'/ipn',
            'locale'         => '', 
            'validate_ssl'   => false , 
        ] ;

        $provider->setApiCredentials($config);

        $options = [
            'BRANDNAME' => 'Ezuru',
            'LOGOIMG' => 'https://ezuru.net/images/logo.png',
            'CHANNELTYPE' => 'Merchant'
        ];
        
        $provider->addOptions($options) ;

        $response = $provider->setExpressCheckout($data);
        // Set Token And Gateway
        $this->booking->gateway = 'paypal' ;
        $this->booking->token   = $response['TOKEN'] ;
        $this->booking->auth    = json_encode($response) ;


        $this->booking->save() ;

        return $response ;

    }

    public function cancel($bookingId = 0 ,Request $request){

    }

    public function successAuth($bookingId = 0 ,Request $request){
        $request->request->add(['auth' => 1]);
        $this->success( $bookingId , $request ) ;
        return Redirect::away( env('VUE' , 'https://127.0.0.1:3000/').'user/payments/'.$bookingId );
    }

    public function success($bookingId = 0 ,Request $request){

        $provider = $this->provider ;

        $data     = $this->getData($bookingId , 'USD') ;

        $authy = $request->input('auth') ;

        if( isset( $authy ) and $authy == 1 ) {
            $auth = $provider->getExpressCheckoutDetails( $request['token'] );
            $this->booking->payerid = $request['PayerID'] ;
            $this->booking->save() ;
            // If This Booking Was -5 Mean Un Paid It Should Pay Directly
            if( $this->booking->status == -5 ){
                if( isset($request->auth) ){ $request->request->add(['auth' => 0]); }
                
                return $this->payNow( $this->booking->id , $request ) ;
            }elseif($this->booking->status == 0 && $this->booking->owner->accept == 1 ){
                try{
                    $this->booking->status = 1 ;
                    $this->booking->save() ;
                } catch(Exception $e){

                }

            }

            return $auth ;
        }else{
        
            $pay = $provider->doExpressCheckoutPayment( $data , $this->booking->token , $this->booking->payerid ) ;

            
            // Lets Create Payment For This Booking 
            $paid = 0 ;
            
            if( isset($pay['PAYMENTINFO_0_PAYMENTSTATUS']) &&  isset($pay['PAYMENTINFO_0_ACK']) && $pay['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed' && $pay['PAYMENTINFO_0_ACK'] == 'Success' ){
                $paid = 1 ;
                // Lets Change Booking Status to Paid 
                $this->booking->status = 2 ; $this->booking->update(); $this->booking->save() ;
            }



            Payments::create([
                'gateway' => 'paypal' ,
                'booking_id' => $this->booking->id ,
                'unit_id' => $this->booking->unit_id ,
                'user_id' => $this->booking->user_id ,
                'owner_id' => $this->booking->owner_id ,
                'cost' => $this->booking->price ,
                'fee' => $this->booking->fee ,
                'total' => $this->booking->price,
                'is_paid' => $paid ,
                'status' => 1
            ]) ;
            return $paid ;
        }




        
    }

    public function payNow($bookingId , Request $request){
        return $this->success($bookingId , $request) ;
    }

    public function ipn($bookingId , Request $request){
        file_put_contents( public_path('ipn.txt') , json_decode( $request->all() ) ) ;
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
