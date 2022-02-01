<?php

namespace App\Services;

use Srmklive\PayPal\Facades\PayPal ;
use Exception;

class PaypalService extends BaseService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = PayPal::setProvider('express_checkout');
    }

    public function init($currency, $package_id){
        $this->setPaypalCredentials($this->getProvider(), $currency, $package_id);
    }

    protected function setPaypalCredentials($provider, $currency, $package_id){
  
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
        
            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => $currency ,
            'billing_type'   => 'MerchantInitiatedBilling',
            'notify_url'     => 'api/payment/'.$package_id.'/ipn',
            'locale'         => '', 
            'validate_ssl'   => false , 
        ] ;

        $provider->setApiCredentials($config);

        $options = [
            'BRANDNAME' => env('APP_NAME' , 'Egtyaz'),
            'LOGOIMG' => '',
            'CHANNELTYPE' => 'Merchant'
        ];
        
        $provider->addOptions($options) ;

    }

    public function buyByPaypal( $package , $type = 'course'){

        $response = $this->getProvider()->setExpressCheckout($this->itemData($package , $type));

        return $response;

    }

    public function itemData($package , $type = 'package'){
        $data = [];
        if( $type == 'course' ){
            $data['items'] = [
                [
                    'name'  => $package->title,
                    'price' => $package->PayPrice ,
                    'desc'  => $package->description." - ". "Purchase" ,
                    'qty' => 1
                ]
            ];
        }else{
            $data['items'] = [
                [
                    'name'  => $package->name,
                    'price' => $package->PayPrice ,
                    'desc'  => $package->name." - ". "Purchase" ,
                    'qty' => 1
                ]
            ];
        }
        
        
        $data['invoice_id'] = $package->id.'-'.time().'-'.$type;
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route("package_purchased", ["id" => $package->id,"invoice_id" => $data['invoice_id'], "processor" => "paypal"]);
        $data['cancel_url'] = route("package_purchased", ["id" => $package->id,"invoice_id" => $data['invoice_id'], "processor" => "paypal"]);
        $data['total'] = $package->PayPrice ;

        return $data;
    }

    public function doPayment($package, $token, $payerId = '3T5GGNEZ8T33S'){
       return $this->getProvider()->doExpressCheckoutPayment($this->itemData($package), $token , $payerId );
    }

    public function getProvider()
    {
        return $this->provider;
    }
}