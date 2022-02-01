<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use Auth ;

use App\Models\Payments;

use App\Traits\SiteMeta ;

use App\Models\Options ;

use Illuminate\Support\Facades\View ;

class PaypalController extends FrontendController
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
        $data['description']    = $payment->id.'|'.($model->title ?? $model->name ?? $model->description ?? '') ;
        $data['amount']         = Xe($payment->cost ,  env('currency_code' , 'SAR') ) ;
        $data['currency']       = env('currency_code' , 'USD') ;

        return $this->view('frontend.dashboard.pp.form', compact('data' , 'inputs' , 'session' ) ) ;

    }

    public function callback(Request $request){

            file_put_contents( public_path('/').'ipn.txt' , $request->getContent() ) ;

            parse_str( $request->getContent() , $responde ) ;
     
            $paypal = Payments::find( $responde['item_number'] ?? 0 ) ;
     
            if( isset($paypal->id)  ){
                 // Paypal respode is of for now
                 $status = 0 ;
                 if( $responde['payment_status'] == 'Completed' ){
                     $status = 1 ;
                 }else{
                     $status = 0 ;
                 }
     
                 $paypal->update([
                     'status' => $status ,
                     'is_paid' => $status ,
                 ]);
            }
             

        return 'ok' ;
    }

    public function ipn(Request $request){
       return $this->callback($request) ;
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
