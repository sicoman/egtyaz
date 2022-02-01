<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth ;

use App\User ;

use Notification ;

use App\Notifications\UNotify ;

use DB ;

class FrontController extends Controller
{
    public function confirm(Request $request){
        $code = rand(10000 , 99999) ;

        $mobile = Auth::user()->mobile ;

        $data = [
            'user' => Auth::user() ,
            'code' => $code ,
        ] ;
        
        \Notification::send( Auth::user() , new UNotify( $data , 'user_active_mobile' , Auth::user()->lang ) ) ;

        #$send =  sms( $mobile , 'Your Code Is '. $code ) ;

        return $code ;
        
    }

    public function code_validate($code){
        User::where('id' , Auth::user()->id )->update(['mobile_verified_at' =>  date('Y-m-d h:i:s') ] ) ;
        return 1 ;
    }


    public function Notifications(){
        // I will Not Use Default Laravel Notification  $user->unreadNotifications
        $notifications = DB::table('notifications')->where('notifiable_id' , Auth::user()->id )->orderBy('created_at' ,'DESC')->limit(3)->whereNull('read_at')->get() ; // 

        if(!empty($notifications)){
            $ids = [] ;
            foreach($notifications as $notification){
                $ids[] = $notification->id ;
            }
            DB::table('notifications')->whereIn('id' , $ids)->update(['read_at' => date('Y-m-d h:i:s')]) ;
        }

        return $notifications ;

    }


}
