<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Traits\Curly ;
class Wishlist extends TestCase
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

    public function testUpdateProfile()
    {
        $this->login() ;
        $this->checkToken();
        
        $result = $this->fetch(  'post' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/add-unit-to-wishlist?unit_id=9' , [] , [
            'Authorization : Bearer '.$this->token ,
            'Accept : application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Add to Wishlist ->  Ok." ;

        $result = $this->fetch(  'post' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/add-unit-to-wishlist?unit_id=9' , [] , [
            'Authorization : Bearer '.$this->token ,
            'Accept : application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Remove from Wishlist ->  Ok." ;

        $result = $this->fetch(  'get' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/my-wishlist' , [] , [
            'Authorization : Bearer '.$this->token ,
            'Accept : application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."List of Wishlist ->  Ok." ;


    }

}
