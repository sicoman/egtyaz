<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Options ;
use App\Models\Units ;
use App\Models\Booking ;
use App\Models\Days ;
use App\Models\Search;
use App\User ;
use Carbon\Carbon ;
use DB ;

use Notification ;
use App\Notifications\UNotify ;



class CronJobs extends Controller
{


    public function currency(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://xecdapi.xe.com/v1/convert_to.json/?to=USD&from=EGP&amount=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_USERPWD, getenv('XE_API_KEY') . ':' . getenv('XE_API_PASSWORD')) ;

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $convertJsonToArray = (array) json_decode($result) ;

        if( is_array($convertJsonToArray) && isset($convertJsonToArray['documentation_url']) ){
            dd( $convertJsonToArray ) ;
        }

        $getResult = $convertJsonToArray['from'][0]->mid ;


        if(  $getResult > 0 ){

        }else{
            dd('0') ;
        }


        Options::where('option_var' , 'USD_EGP')->where('type' , 'currency')->update(['option_value' => $getResult ]) ;
       

        return $getResult ;
    }

    public function currency2(){
        $usd_egp = (array) json_decode( file_get_contents('https://free.currconv.com/api/v7/convert?q=USD_EGP&compact=ultra&apiKey=21cde713d3f11c195178') ) ;
        Options::where('option_var' , 'USD_EGP')->where('type' , 'currency')->update(['option_value' => $usd_egp['USD_EGP']]) ;
        return $usd_egp ;
    }

    public function setPrice(){
        // Date 
        $date = date('Y-m-d') ;
        // Get Days When date = $date
        $days = Days::where('date' , $date)->select('unit_id','price')->pluck('price' , 'unit_id');

        foreach($days as $unit=>$price){
          Units::where('id' , $unit)->update(['price' => $price]) ;
        }
    }


    /*
        '4': 'Checkout' ,           // System
        '3': 'Checkin'  ,           // System
        '2': 'Paid'     ,           // Payment  
        '1': 'Approved' ,           // Owner
        '0': 'Waiting  Approval',   // Guest
        '-1': 'Closed - Expired',   // System
        '-2': 'Cancel Request',     // Guest
        '-3': 'Cancel Accepted',    // Owner
        '-4': 'Booking rejected',   // Owner
    */

    public function setBooking(){
        // Date 
        $date = date('Y-m-d') ;
        $yesterday = date('Y-m-d' , strtotime('-1 day')  ) ;
        $tommrrow = date('Y-m-d' , strtotime('+1 day')  ) ;

        $yesterday_1_hour = date('Y-m-d h:i:s' , strtotime('-1 day')) ;

        $yesterday_2_hour = date('Y-m-d h:i:s' , strtotime('-1 day +1 hour')) ;

        // From key delivered || confirm  to Checkin  [ every day ]
        $Booking = Booking::where('date_start' , $date)
                ->whereIn('status',[4,5])->update(['status' => '3']); // Update Status

        // From CheckIn to Checkout [ every day ]
        $Booking = Booking::where('date_end' , $date )
                ->where('status','3')->update(['status' => '4']); // Update Status

        // From Checkout to Closed - Expired [ every day ]
        $Booking = Booking::where('date_end' ,'>', $date )->where('date_end' ,'<=', $tommrrow )
                ->where('status','4')->update(['status' => '-1']); // Update Status

        
    }

    public function setBookingHour(){
        // Date 
        $date = date('Y-m-d') ;
        $yesterday = date('Y-m-d' , strtotime('-1 day')  ) ;
        $tommrrow = date('Y-m-d' , strtotime('+1 day')  ) ;

        $yesterday_1_hour = date('Y-m-d h:i:s' , strtotime('-1 day')) ;

        $yesterday_2_hour = date('Y-m-d h:i:s' , strtotime('-1 day +1 hour')) ;

        // From Waiting Approve to Booking Rejected   [ every Hour ]      
        $Booking = Booking::whereBetween('created_at' ,  [$yesterday_1_hour , $yesterday_2_hour])
        ->where('status','0')->update(['status' => '-4']); // Update Status
        
    }

    public function lastSearch(){
        $search = Search::whereDate('updated_at','>', Carbon::now()->subDays(7))->limit(2)->orderBy('updated_at','desc')->get() ;
        $units = Units::where('status' , 1)->get();

        print_r($search); die;
        $user = User::where('id' , 1)->first();

        $data = [
            'list' => $units ,
            'user' => $user
        ] ;

        \Notification::send( $user , new UNotify( $data , 'last_2_search' , $user->lang ) ) ;
    }


}
