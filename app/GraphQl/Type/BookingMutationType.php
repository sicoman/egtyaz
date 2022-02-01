<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \App\Models\Units;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UNotify;
use \App\Laravue\Models\User;
use \App\Models\Booking ;
use \App\Models\BookingCancel ;
use \App\Models\Days ;

class BookingMutationType extends BookingQueryType {
    
    protected $modelName = '\App\Models\Booking';

    protected $attributes = [
        'name' => 'BookingMutationType',
        'description' => 'mutation Booking Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("BookingSingleResponse"),
                'args' => [
                  'Booking' => ['name' => 'Booking', 'type' => GraphQL::type('BookingInput')],
                  'PaymentMethod' => ['name' => 'PaymentMethod', 'type' => Type::string()]
                ]
            ],
            'SetBookingStatus' => [
                'type' => Type::boolean(),
                'args' => [
                  'BookingId' => ['name' => 'BookingId', 'type' => Type::int()],
                  'Status' => ['name' => 'Status', 'type' => GraphQL::type('BookingStatusEnumType')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("BookingSingleResponse"),
                'args' => [
                'Booking' => ['name' => 'Booking', 'type' => GraphQL::type('BookingInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('BookingInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Booking' => ['name' => 'Booking', 'type' => GraphQL::type('BookingInput')]
                ]
            ]       
        ];
    }

    public function resolveSetBookingStatusField($root, $args) {
        
        $booking = $this->modelName::find($args['BookingId']);

        $current = $this->isAuthorized();

        if(!$current){
            return $this->resolveErrors(["denied permission for this request"]);
        }

        $action = "" ;

        switch($args['Status']){

            case -3:

            $cancel  = BookingCancel::Calculate($booking ,  $booking->updated_at) ;
            
            BookingCancel::create(['unit_id' => $booking->unit_id,'price' => $cancel->price, 'cancel_id' => $booking->unit->cancel_policy]);
            
            Days::whereIn($cancel['make_days_avilable'])->where('unit_id', $booking->unit_id)->update(['status' => 1]);

            break;
            case 3:
                if($booking->owner_id != $current->id){
                 return $this->resolveErrors(["denied permission for this request"]);
                }
                $action = "confirm_unit" ;
                $user = User::find($booking->user_id) ;
               // Notification::send($user, new UNotify(['booking' => $booking , 'lang' => $user->lang] , $action));
            break;
            case -2:
            break;
            case 2:
            break;
            case 1:
            break;
            case 4:
            break;
            case 0:
                
            break;
            case -5:
            if($booking->owner_id != $current->id){
                return $this->resolveErrors(["denied permission for this request"]);
               }
                $action = "booking_request_reject" ;
                $user = User::find($booking->user_id) ;
               // Notification::send($user, new UNotify(['booking' => $booking , 'lang' => $user->lang] , $action));
            break;
            case -6:
            if($booking->user_id != $current->id){
                return $this->resolveErrors(["denied permission for this request"]);
               }
                $action = "booking_canceled" ;
                $user = User::find($booking->user_id) ;
             //   Notification::send($user, new UNotify(['booking' => $booking , 'lang' => $user->lang] , $action));
            break;

        }

        $booking->status = $args['Status'];
   
        $res = $booking->update();

        if($args['Status'] == 3){
            if( $args['gateway'] == 'paypal' ) {
                $paid = app('App\Http\Controllers\Payments\PaypalController')->payNow($booking->id , request() );
            }else{
                $paid = app('App\Http\Controllers\Payments\PayfortController')->payNow($booking->id , request() );
            }
        
          if($paid){
              $booking->status = 2;
            //  Notification::send($user, new UNotify(['booking' => $booking , 'lang' => $user->lang] , $action));
          }else{
            $booking->status = -2;
          }
  
          $res = $booking->update();
        }

        return $this->resolveResponse($res);

    } 
    public function resolveCreateField($root, $args) { //PaymentMethod
  
        if($this->isAuthorized()){ 
            $Booking = isset($args['Booking']) ? $args['Booking'] : false;

            if( $Booking['date_start'] == $Booking['date_end']  ){
                $dates = [ $Booking['date_start'] ];
            }else{
                $dates = getDatesFromRange($Booking['date_start'], $Booking['date_end']);
            }

            $isBooked = Days::whereIn('date', $dates)->where(['status' => 2, 'unit_id' => $Booking['unit_id']])->count() > 0;
            if($isBooked){
                return $this->resolveErrors(["already booked before"]);
            }else{
                $Booking['user_id'] = Auth::id();   
                $model = app($this->modelName); 

                $Booking['gateway'] = $args['PaymentMethod'] ;

                $res = $model::create($Booking);  
                
                $user = User::find($res->owner_id) ;
                if($args['PaymentMethod'] == "paypal"){
                    $result = app('App\Http\Controllers\Payments\PaypalController')->auth($res->id , 'USD' , request() ); 
                    if(is_array($result) && isset($result['paypal_link'])){
                      $res->paymentUrl = $result['paypal_link'] ;
                    }
                }else if($args['PaymentMethod'] == "payfort"){
                    
                }

                //Set Days as booked 
                Days::whereIn('date', $dates)->where(['status' => 1, 'unit_id' => $Booking['unit_id']])->update(['status' => 2]);
                return $this->resolveResponse($res);
            }

        }
        return $this->resolveErrors(["denied permission for this request"]);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Booking = isset($args['Booking']) ? $args['Booking'] : false;
            $objects = (array) BookingInput::getObjects();
            $filteredBooking = array_filter($Booking, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newBooking = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewBooking = array_filter($newBooking, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredBooking);
            $res = $obj->update($filteredNewBooking); 
              if(!empty($relatedNew)){  
                foreach($relatedNew as $t => $related){
                    $f = array_flip(array_keys($objects));   
                    if(array_key_exists($t, $f)){ 
                        switch($objects[$t]->type){
                            case '_mtm_':
                            $p = $objects[$t]->plural;  
                             $obj->first()->$p()->sync(collect($newPost[$related])->pluck('id'));
                            break;
                        }
                    }

                }
            }
            return $this->resolveResponse($obj->first());
        }
        return $this->resolveErrors(["denied permission for this request"]);
    }

    public function resolveDeleteField($root, $args) {  
        if($this->isAuthorized()){
            $Booking = isset($args['Booking']) ? $args['Booking'] : false;
            $newBooking = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Booking)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
