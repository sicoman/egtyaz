<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Traits\Curly ;

class Profile extends TestCase
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
        
        $result = $this->fetch(  'PUT' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . 'api/v1/users/'.$this->id , [
            'name' => 'User Update Name '.time() ,
            'email' => 'u_u_e_'.time().'@ezuru.net' ,
            'mobile' => time() ,
            'password' => time()
        ] , [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]) ;

        $this->assertEquals( 200 , $result[0] );
        
        echo $this->del."Update Default Info  { name , email , mobile } ->  Ok." ;

        $result = $this->fetch(  'PUT' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/users/'.$this->id , [
            'name' => 'User Update Name '.time() ,
            'email' => 'u_u_e_'.time().'@ezuru.net' ,
            'mobile' => time() ,
            'password' => time()
        ] , [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Update Password ->  Ok.".time() ;

        $result = $this->fetch(  'PUT' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/users/'.$this->id , [
            'name' => 'User Update Name '.time() ,
            'email' => 'u_u_e_'.time().'@ezuru.net' ,
            'mobile' => time() ,
            'password' => getenv('TEST_LOGIN_PASS')
        ] , [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Re Update Password to Previous ->  Ok." ;


        $result = $this->fetch(  'PUT' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/users/'.$this->id , [
            'name' => 'User Update Name '.time() ,
            'email' => 'u_u_e_'.time().'@ezuru.net' ,
            'mobile' => time() ,
            'accept' => 1
        ] , [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Update Auto Confirm Booking ->  Ok." ;


        /* This should be upload avatar */  
        $result = $this->fetch(  'PUT' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/users/'.$this->id , [
            'accept' => 0
        ] , [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Update Avatar ->  Ok." ;

        /* Add Unit to WishList */  
        $result = $this->fetch(  'PUT' , getenv('APP_URL' , 'http://127.0.0.1:8000/') . '/api/v1/users/'.$this->id , [
            'accept' => 0
        ] , [
            'Authorization: Bearer '.$this->token ,
            'Accept: application/json'
        ]) ;
        $this->assertEquals( 200 , $result[0] );
        echo $this->del."Update Avatar ->  Ok." ;






    }

}
