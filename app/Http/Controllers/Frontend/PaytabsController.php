<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use Auth ;

use App\Models\Payments;

use App\Traits\SiteMeta ;

use App\Models\Options ;

use Illuminate\Support\Facades\View ;

use Paytabscom\Laravel_paytabs\paypage ;

class PaytabsController extends FrontendController
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

        $user = auth()->user() ;

        $pay = new Paypage() ;
        $pt =  $pay->sendPaymentCode('all')
         ->sendTransaction('sale')
         ->sendCart($data['id'] , $data['amount']  , $data['description'] )
         ->sendCustomerDetails( $user->name ?? '' , $user->email ?? '' , $user->mobile ?? '' , 'test', '', '', '', '','')
         ->sendURLs( route('pt.success') , route('pt.callback') )
         ->sendLanguage('en')
         ->create_pay_page();

        if( isset($pt) ){
            return redirect( $pt->gettargetUrl() ) ;
        }

        return 'Unable to create paytabs page' ;

    }

    public function verify($indicator){
        
    }

    public function callback(Request $request){
        file_put_contents( public_path('bm_ipn.txt') , json_encode($request->all()) , FILE_APPEND );
        return 'OK' ;
    }

    public function success(Request $request){

        $d = $request->all() ;

        $payment = Payments::find($d['cartId'] ?? 0) ;

        

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
        
        $pay = new Paypage() ;

        $capture = $pay->capture( ($d['tranRef'] ?? '') , ($d['cartId'] ?? '') , $data['amount'] , $data['description'] ); 
        
        if( $d['respStatus'] == 'A' ) {
            $payment->update([
                'status' => 1 ,
                'is_paid' => 1 ,
            ]);
            return view('frontend.dashboard.global.success') ;
        }else{
            $payment->update([
                'status' => 0 ,
                'is_paid' => 0 ,
            ]);
            return view('frontend.dashboard.global.cancel') ;
        }

    }

    public function cancel(Request $request){
        return view('frontend.dashboard.global.cancel') ;
    }
}
