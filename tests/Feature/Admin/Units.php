<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class Units extends TestCase
{
    protected $del = "\r\n" ;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
        {
            $del = $this->del ;
            $this->withoutExceptionHandling();
            $this->withoutMiddleware();

            $response = $this->json('POST', '/api/auth/login', ["email" => getenv('TEST_LOGIN_EMAIL')  ,"password" => getenv('TEST_LOGIN_PASS') ]);
            $response->assertStatus(200)->assertJson(['token' => true]);
            $token = (array) json_decode($response->content()) ; 
            echo $del."Admin Login to Check => Units ->  Ok." ;

            echo $del.$del.' ==========================  Admin Units : Test  ==========================='.$del.$del ; 
            
            // Get  List
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('GET' , '/api/units' , []);
            $response->assertStatus(200)->assertJson(['current_page' => true]);

            echo $del."Units Show List ->  Ok." ;

            // Search $type

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('GET' , '/api/units' , []);
            $response->assertStatus(200)->assertJson(['current_page' => true]);

            echo $del."Units Search List ->  Ok." ;
    
            // Add UNit
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('POST' , '/api/units?create_update=true');
            $response->assertStatus(201)->assertJson(['status' => -10]); // -10 Is Draft

            $unit = (array)  json_decode( $response->content() ) ;
            
            echo $del."Units Add New ->  Ok." ;

            // Update Units
            $unit['title'] = 'Test Unit Test '.rand(55555,9999) ;
            $unit['status'] = 1 ;
            $unit['child_type'] = 0 ;
            
            $unit_id = $unit['id'] ;
            unset($unit['user'],$unit['owner'])  ;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('PUT' , '/api/units/'.$unit_id , (array) $unit );
            $response->assertStatus(200)->assertJson(['status' => 1]);

            echo $del."Edit Units ->  Ok." ;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('POST' , '/api/units/active/'.$unit_id , ['status' => 0] );
            $response->assertStatus(204) ;

            echo $del."Disable Units ->  Ok." ;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('POST' , '/api/units/active/'.$unit_id , ['status' => 1] );
            $response->assertStatus(204) ;

            echo $del."Active Units ->  Ok." ;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('POST' , '/api/units/feature/'.$unit_id , ['status' => 1] );
            $response->assertStatus(204) ;

            echo $del."make Feature Units ->  Ok." ;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('POST' , '/api/units/feature/'.$unit_id , ['status' => 0] );
            $response->assertStatus(204) ;

            echo $del."Disable Feature Units ->  Ok." ;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('DELETE' , '/api/units/'.$unit_id , [] );
            $response->assertStatus(204) ;

            echo $del."Delete Units ->  Ok." ;

            sleep(1) ;
            
        }

    protected $bookingStatus = [ 
        '6'=> 'Key Delivered',
        '5'=> 'Confirm Key Delivered',
        '4'=> 'Checkout',
        '3'=> 'Checkin',
        '2'=> 'Paid',
        '1'=> 'Approved',
        '0'=> 'Waiting  Approval',
        '-1'=> 'Closed - Expired',
    //  '-2'=> 'Cancel Request',
        '-3'=> 'Cancel',
        '-4'=> 'Booking rejected',
        '-5'=> 'UnPaid - Need Payment'
    ] ;

    public function testExample2() {
            $del = $this->del ;
            $this->withoutExceptionHandling();
            $this->withoutMiddleware();

            $response = $this->json('POST', '/api/auth/login', ["email" => getenv('TEST_LOGIN_EMAIL')  ,"password" => getenv('TEST_LOGIN_PASS') ]);
            $response->assertStatus(200)->assertJson(['token' => true]);
            $token = (array) json_decode($response->content()) ; 
            echo $del."Admin Login to Check => Booking ->  Ok." ;

            echo $del.$del.' ==========================  Units Booking  ==========================='.$del.$del ; 
            
            // Get  List
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('GET' , '/api/booking' , []);
            $response->assertStatus(200)->assertJson(['current_page' => true]);

            echo $del."Booking Show List ->  Ok." ;


            $booking_id = (int) getenv('TEST_ADMIN_BOOKING_ID' , 0) ;
            if( $booking_id == 0 ){
                // Lets Get Booking From Booking Request
                $list = ( array ) json_decode ( $response->content() )->data ;
                if( isset($list[0]) ){
                    $booking_id = $list[0]->id ; //  
                }else{
                    return false; // Stop Booking Test No edit on Booking
                }
            }

            foreach( $this->bookingStatus as $k=>$v ){
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/booking/active/'.$booking_id , ['status' => $k ] );
                $response->assertStatus(204) ;

                echo $del."Set booking Status to { $v } ->  Ok." ;
            }

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('DELETE' , '/api/booking/'.$booking_id , [] );
            $response->assertStatus(204) ;

            echo $del."Delete Booking ->  Ok." ;

            sleep(1) ;
            
    }


 
    
    public function testExample3() {
            $del = $this->del ;
            $this->withoutExceptionHandling();
            $this->withoutMiddleware();

            $response = $this->json('POST', '/api/auth/login', ["email" => getenv('TEST_LOGIN_EMAIL')  ,"password" => getenv('TEST_LOGIN_PASS') ]);
            $response->assertStatus(200)->assertJson(['token' => true]);
            $token = (array) json_decode($response->content()) ; 
            echo $del."Admin Login to Check => Reports ->  Ok." ;

            echo $del.$del.' ==========================  Units Reports  ==========================='.$del.$del ; 
            
            // Get  List
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('GET' , '/api/flags' , ['type' => 'unit']);
            $response->assertStatus(200)->assertJson(['current_page' => true]);

            echo $del."Reports Show List ->  Ok." ;


            $flags_id = (int) getenv('TEST_ADMIN_REPORTS_ID' , 0) ;
            if( $flags_id == 0 ){
                // Lets Get Booking From Booking Request
                $list = ( array ) json_decode ( $response->content() )->data ;
                if( isset($list[0]) ){
                    $flags_id = $list[0]->id ; //  
                }else{
                    return false; // Stop Booking Test No edit on Booking
                }
            }

            
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('POST' , '/api/flags/active/'.$flags_id , ['status' => 1 ] );
            $response->assertStatus(204) ;

            echo $del."Set Unit Reports Status to { Active } ->  Ok." ;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('POST' , '/api/flags/active/'.$flags_id , ['status' => 0 ] );
            $response->assertStatus(204) ;

            echo $del."Set Unit Reports Status to { Disable } ->  Ok." ;
            

            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',        
                'Referer' => '/'
            ])->json('DELETE' , '/api/flags/'.$flags_id , [] );
            $response->assertStatus(204) ;

            echo $del."Delete Unit Reports ->  Ok." ;

            sleep(1) ;
            

        }






  
  
  
}
