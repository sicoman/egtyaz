<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use Auth ;

use App\Models\Payments;

use App\Traits\SiteMeta ;

use App\Models\Options ;

use Illuminate\Support\Facades\View ;

class BankMisrController extends FrontendController
{
    use SiteMeta;

    public function __construct( Options $options )
    {    

        parent::__construct();

        $this->setMeta('title', 'لوحة تحكم الطالب');

        $this->registerSiteMeta();

        $this->options = $options ;

        $default_settings   = $this->getSetting(['seo' , 'social']) ;

        View::share('options' , $default_settings ) ;

    }

    public function getSetting( $type ){
        if( is_array($type) ){
            $returnOptions = [] ;
            $options = $this->options->whereIn('type' , $type )->select([ 'type' ,'option_value' , 'option_var']) ;
            foreach($options as $option){
                $returnOptions[$option->type][ $option->option_var] =  $option->option_value ; 
            }
            return  $returnOptions ;
        }
        return  $this->options->where('type' , $type )->pluck('option_value' , 'option_var') ;
    }


    public function form($id , Request $request ){

        $payment = Payments::find($id) ;
        $model   = [] ; 

        if( $payment->type == 'course' ){
            $model = $payment->Course ;
        }else{
            $model = $payment->Package ;
        }

        $data = [] ;
        $data['id']             = $payment->id ;
        $data['description']    = $payment->id.'|'.($model->title ?? $model->name ?? $model->description ?? '' ) ;
        $data['amount']         = $payment->cost ;
        $data['currency']       = env('currency_code' , 'SAR') ;

        $id = 'Merchant.EGTEYAZ' ;

        $headData = [
            'apiOperation' => 'CREATE_CHECKOUT_SESSION' ,
            'interaction' => [ 'operation' => 'PURCHASE'  ] , // , 'returnUrl' => route('bm.callback')
            'order' => $data 
        ];

        $session = $this->curl(
            'https://banquemisr.gateway.mastercard.com/api/rest/version/57/merchant/EGTEYAZ/session' ,
            $headData  ,
            'Merchant.EGTEYAZ:9764f5cf4a08184edf0048fdc2418be9'
        ) ;

        $session = json_decode($session) ;

        return $this->view('frontend.dashboard.bm.form', compact('data' , 'inputs' , 'session' , 'id') ) ;

    }

    public function verify($indicator){
        $headData = [
            'apiOperation' => 'CREATE_CHECKOUT_SESSION' ,
            'interaction' => [ 'operation' => 'VERIFY' ] ,
            'order' => $data 
        ];

        $session = $this->curl(
            'https://banquemisr.gateway.mastercard.com/api/rest/version/57/merchant/EGTEYAZ/session' ,
            $headData  ,
            'Merchant.EGTEYAZ:9764f5cf4a08184edf0048fdc2418be9'
        ) ;
    }

    public function callback(Request $request){
        dd( $request->all() ) ;
        file_put_contents( public_path('bm_ipn.txt') , json_encode($request->all()) , FILE_APPEND );
        return 'OK' ;
    }

    public function success(Request $request){
        return view('frontend.dashboard.global.success') ;
    }

    public function cancel(Request $request){
        return view('frontend.dashboard.global.cancel') ;
    }

    public function curl($url = '' , $data , $auth = ''){
        $post = json_encode($data , true) ;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Length: " . strlen($post)));
        if( $auth ){
            curl_setopt( $ch, CURLOPT_USERPWD, $auth ); 
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return $response ;
    }

}
