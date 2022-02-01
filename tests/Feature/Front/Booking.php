<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Traits\Curly ;

class Booking extends TestCase
{
    use Curly ;
    protected $del = "\r\n" ;
    protected $token = '' ;
    protected $id    = 0 ;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function login(){
        $del = $this->del ;
        echo $del.$del.' ==========================  User Login   ==========================='.$del.$del ; 
        $this->withoutExceptionHandling();

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/v1/users/login', ["email" => getenv('TEST_LOGIN_EMAIL')  ,"password" => getenv('TEST_LOGIN_PASS') ]);
        $response->assertStatus(200)->assertJson(['response' => true]) ;
        echo $del."Success Login ->  Ok." ;

        $json = (array) json_decode($response->content()) ; 

        $token = $json['response']->token ;

        $this->id = $json['response']->id ;

        $this->token = $token ;

    }

    public function checkToken(){
        if( $this->token == '' ){
            dd($this->del.' Test Failed Token Not Vaild') ;
        }
        return $this->token ;
    }

    public function testCreateBooking()
    {
        $this->login();
        $this->checkToken() ;
        
        // 01 - Get Unit Full Unit
        $response = $this->withHeaders([
            //   'Authorization' => 'Bearer '.$this->token ,
               'Accept' => 'application/json'
        ])->json('GET', 'api/v1/units/'.getenv('TEST_UNIT_BOOKING' , 9).'?locale=en', [] );
        $response->assertStatus( 200 );
        echo $this->del."Get Unit With Full Details ->  Ok." ;

        // 2 - Lets Get Unit Days  => days_list   
        $unit = (array) json_decode( $response->content() ) ;
        
        $days_list = $unit['days_list'] ;

        // Check Last Days it may be avilable
        // rsort( $days_list ) ;

        $mindays = $unit['min_days'] ;
        $days_selected = [] ;
        // 3 - Get Avilable { $mindays } Days
        if( !empty($days_list) ){
            foreach( $days_list as $day ){
                $day = (array) $day ;
                if( count($days_selected) == $mindays ){ break; }
                if( $day['status'] == 1 ){
                    $days_selected[$day['date']] = $day ;
                }else{
                    $days_selected = [] ;
                }
            } 
        }

        // If Days are less than min days Stop Checking
        if( count($days_selected) < $mindays ) {
            dd('Unable to find '.$mindays.' Avilable Days') ;
        }

        $first_date = '' ; $last_date = '' ;
        foreach($days_selected as $k => $v){
            if( $first_date == '' ){ $first_date = $k ;}
            $last_date = $k ;
        }

        // We have min days now Lets Check if it's Avilable and get Avg Between it
             
        $response = $this->withHeaders([
               'Authorization' => 'Bearer '.$this->token ,
               'Accept' => 'application/json'
        ])->json('GET', '/api/v1/is-booking-available?unit_id='.$unit['id'].'&start_date='.$first_date.'&end_date='.$last_date , [] );
        $response->assertStatus( 200 );
        echo $this->del." Check Min Days Avilable ->  Ok." ;
          
        $rent_all = (array) json_decode( $response->content() ) ;

        if( $rent_all['avilable'] == 0 ){
            dd( 'Days Not Avilable' ) ;
        }

        $seprate_response = (array) $rent_all['unit_tax_fee']->all_rent_seprate ;

    
        // Lets Send Booking For This Unit
        /*
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'
        ])->json('POST', '/api/v1/add-booking' , [
            'unit_id' => $unit['id'] ,
            'date_start' => $first_date ,
            'date_end' => $last_date ,
            'PaymentMethod' => 'payfort' ,
            'owner_id' => $unit['user_id'] ,
            'adults' => 2 ,
            'childrens' => 2 ,
            'price' => $seprate_response['rent'] , 
            'day_price' => $seprate_response['day_price'] ,
            'fee' => $seprate_response['fee'] ,
            'ezuru_fee' => $seprate_response['ezuru'] ,
            'tourism' => $seprate_response['tourism'] ,
            'tax' => $seprate_response['vat']
        ] );
        */

         $gateways = ['paypal' , 'payfort'] ;
         
         $gateway_1 = $gateways[rand(0,1)] ;
         $gateway_2 = '' ;
         if( $gateway_1 == 'paypal' ){
            $gateway_2 = 'payfort' ;
         }else{
            $gateway_2 = 'paypal' ;
         }


        $result = $this->fetch('POST' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/add-booking' , [
            'unit_id' => $unit['id'] ,
            'date_start' => $first_date ,
            'date_end' => $last_date ,
            'PaymentMethod' => $gateway_1  ,
            'owner_id' => $unit['user_id'] ,
            'adults' => 2 ,
            'childrens' => 2 ,
            'price' => $seprate_response['rent'] , 
            'day_price' => $seprate_response['day_price'] ,
            'fee' => $seprate_response['fee'] ,
            'ezuru_fee' => $seprate_response['ezuru'] ,
            'tourism' => $seprate_response['tourism'] ,
            'tax' => $seprate_response['vat']
        ],
        [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]);

        $this->assertEquals( 200 , $result[0] ) ;
        echo $this->del." Booking Request Response { $gateway_1 } ->  Ok." ;

        $content = (array) json_decode( $result[1] ) ;

        $this->assertEquals( 200 , $content['status'] ) ;
        echo $this->del." Booking Request Saved ->  Ok." ;

        sleep(1) ;

        /* Lets Cancel This Booking */
            $booking_id = $content['response']->id ;
            $result = $this->fetch('POST' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/set-booking-status?BookingId='.$booking_id.'&Status=-2' , [],
            [
                'Authorization: Bearer '.$this->token ,
                'Accept: application/json'
            ]);

            $this->assertEquals( 200 , $result[0] ) ;
            echo $this->del." Booking Cancel Request  ->  Ok." ;

            $content = (array) json_decode( $result[1] ) ;

            $this->assertEquals( 1 , $content['response'] ) ;
            echo $this->del." Booking Cancel Sent  ->  Ok." ;

        /*
            Send Response Again with Paypal
        */
        $result = $this->fetch('POST' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/add-booking' , [
            'unit_id' => $unit['id'] ,
            'date_start' => $first_date ,
            'date_end' => $last_date ,
            'PaymentMethod' => $gateway_2 ,
            'owner_id' => $unit['user_id'] ,
            'adults' => 2 ,
            'childrens' => 2 ,
            'price' => $seprate_response['rent'] , 
            'day_price' => $seprate_response['day_price'] ,
            'fee' => $seprate_response['fee'] ,
            'ezuru_fee' => $seprate_response['ezuru'] ,
            'tourism' => $seprate_response['tourism'] ,
            'tax' => $seprate_response['vat']
        ],
        [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]);

        $this->assertEquals( 200 , $result[0] ) ;
        echo $this->del." Booking Request Response  ->  Ok." ;

        $content = (array) json_decode( $result[1] ) ;

        $this->assertEquals( 1 , count($content['errors']) ) ;
        echo $this->del." Booking Request { $gateway_2 } Ignored Because of Booked Before ->  Ok." ;


    }
}
