<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Traits\Curly ;

class Messages extends TestCase
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
    public function testMessages()
    {
        $this->login();
        $this->checkToken();


        $result = $this->fetch('GET' , getenv('APP_URL' , 'http://127.0.0.1:8000/') .'/api/v1/messages?unit_id='.getenv('TEST_UNIT_BOOKING' , 9).'&limit=1&page=1' , [] ,
        [
               'Authorization: Bearer '.$this->token ,
               'Accept: application/json'
        ]);
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Get Messages for unit only ->  Ok." ;

        $result = $this->fetch('GET' , getenv('APP_URL' , 'http://127.0.0.1:8000/') .'/api/v1/messages?owner_id=1&limit=1&page=1' , [] ,
        [
               'Authorization: Bearer '.$this->token ,
               'Accept: application/json'
        ]);
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Get Messages for User As {Owner} ->  Ok." ;

        $result = $this->fetch('GET' , getenv('APP_URL' , 'http://127.0.0.1:8000/') .'/api/v1/messages?guest_id=1&limit=1&page=1' , [] ,
        [
               'Authorization: Bearer '.$this->token ,
               'Accept: application/json'
        ]);
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Get Messages for User As {Guest} ->  Ok." ;

            

        /*
             Send Message 
         */    

        $result = $this->fetch('POST' , getenv('APP_URL' , 'http://127.0.0.1:8000/') .'/api/v1/messages' , 
        [
            'unit_id' => getenv('TEST_UNIT_BOOKING' , 9) ,
            'owner_id' => 1 ,
            'user_id' => 1 ,
            'message' => 'This is New Message Or Chat Answer if Unit Has Chat Between Owner And Guest'
        ] ,
        [
               'Authorization: Bearer '.$this->token ,
               'Accept: application/json'
        ]);

        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Send Message ->  Ok." ;


        $result = $this->fetch('POST' , getenv('APP_URL' , 'http://127.0.0.1:8000/') .'/api/v1/messages' , 
        [
            'unit_id' => getenv('TEST_UNIT_BOOKING' , 9) ,
            'owner_id' => 1 ,
            'user_id' => 1 ,
            'message' => 'This is Message Answer'
        ] ,
        [
               'Authorization: Bearer '.$this->token ,
               'Accept: application/json'
        ]);

        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Answer Message ->  Ok." ;



    }
}
