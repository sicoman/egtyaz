<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Frontend\Api\ApiController;
use Illuminate\Http\Request;

use Auth ;
use stdClass;

class BankMisrController extends ApiController
{
    public function form(Request $request){

        $data = [] ;
        $data['id'] = time() ;
        $data['description'] = 'course name' ;
        $data['amount'] = '10.00' ;
        $data['currency'] = 'EGP' ;

        $id = 'merchant.TESTMERCHTST_EGP' ;

        $headData = [
            'apiOperation' => 'CREATE_CHECKOUT_SESSION' ,
            'interaction' => [ 'operation' => 'PURCHASE' ] ,
            'order' => $data 
        ];

        $session = $this->curl(
            'https://banquemisr.gateway.mastercard.com/api/rest/version/57/merchant/TESTMERCHTST_EGP/session' ,
            $headData  ,
            'merchant.TESTMERCHTST_EGP:26c176246ea2389bea43649c5e1d426e'
        ) ;

        $session = json_decode($session) ;

        return $this->view('frontend.dashboard.bm.form', compact('data' , 'inputs' , 'session') ) ;

    }

    public function callback(Request $request){
        dd($request->all()) ;
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
