<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Options ;
use Newsletter;

class SettingsController extends Controller
{
    public function handle($type = 'site' , Request $request){
        $type = explode(',' , $type) ;
        $res  = Options::whereIn('type' , $type)->where('status' , 1)->select(['type' , 'option_var' , 'option_value'])->get();
        $result = [];
        foreach($res as $row){
            $result[$row->type][$row->option_var] = $row->option_value ;
        }
        return $result ;
    }

    public function subscribe(Request $request){

            $email = $request['email'] ;

            $name  = $request['name'] ?? "" ;
    
            if( !filter_var($email, FILTER_VALIDATE_EMAIL) ){
                return ['res' => 0 , 'msg' => __('Please Set Vaild Email')] ;
            }
    
            $subscribed = Newsletter::isSubscribed( $email );
    
            if( $subscribed ){
                return ['res' => 1 , 'msg' => __('Already Joined')] ;
            }
    
    
            $res = Newsletter::subscribe($email , ['firstName'=>  $name ]);
    
            if( isset($res['id']) ){
                return ['res' => 1 , 'msg' => __('Joined Succefully')] ;
            }else{
                return ['res' => 1 , 'msg' => __('Unable to Join')] ;
            }
    
    }
}
