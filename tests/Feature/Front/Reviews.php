<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Traits\Curly ;

use App\Models\Booking ;

class Reviews extends TestCase
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testReviews()
    {
        $this->login();
        $this->checkToken() ;

        // 01 - Lets Check if we can Review Unit

        $response = $this->withHeaders([
               'Authorization' => 'Bearer '.$this->token ,
               'Accept' => 'application/json'
        ])->json('GET', '/api/v1/can-review-unit?unit_id='.getenv('TEST_UNIT_BOOKING' , 9).'&guest_id='.$this->id.'?locale=en', [] );
        $response->assertStatus( 200 );
        echo $this->del."Request {Can review unit} Sent ->  Ok." ;

        $parseRequest = (array) json_decode( $response->content() ) ;    

        $booking_id = $parseRequest['booking_id'] ; 
        
        // Get Bookin

        $booking = Booking::where('id' , $booking_id)->first() ;

        
        $can_review = $parseRequest['result'] ; 

        if( !$can_review ){
            dd(' Unable to Make Review for this unit .') ;
        }
        echo $this->del."Abillity to Review Unit ->  Ok." ;

        // Lets Get Unit Review List
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'
        ])->json('GET', '/api/v1/taxonomy?type=review_type&page=1&limit=100', [] );
        $response->assertStatus( 200 );
        echo $this->del."Request Unit Reviews List ->  Ok." ;

        $parseRequest = (array) json_decode( $response->content() ) ;

        $reviews_list = [] ;

        $reviews_items = $parseRequest['response']->data ;

        foreach( $reviews_items as $item ){
            $reviews_list[] = [ 'name' => $item->name , 'rating' => rand(1,5) , 'note' => 'New Note for Reviews' ] ;
        }


        $unit_id = $booking->unit_id ;
        $owner_id = $booking->owner_id ;
        $booking_id = $booking_id ;
        $type = 'review' ;
        $review = 'This is main review' ;

    
        $result = $this->fetch('POST' ,  getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/add-review' , 
            json_encode([
                'unit_id' => $unit_id ,
                'owner_id' => $owner_id ,
                'guest_id' => $booking->user_id ,
                'booking_id' => $booking_id ,
                'type' => $type ,
                'review' => $review,
                'Items' => $reviews_list
            ]) ,
            [
                'Authorization: Bearer '.$this->token ,
                'Accept: application/json',
                'Content-Type: application/json'
            ]
        ) ;

        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Add Unit Review ->  Ok." ;

        // Lets Get Unit Reviews
        $result = $this->fetch('GET' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/reviews?page=1&limit=2&unit_id='.getenv('TEST_UNIT_BOOKING' , 9) ,[] );
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Get Unit Reviews ->  Ok." ;


        // Lets Get Unit Reviews
        $result = $this->fetch('GET' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/reviews?page=1&limit=2&guest_id=94' ,[] );
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Get Guest Reviews ->  Ok." ;

    }
}
