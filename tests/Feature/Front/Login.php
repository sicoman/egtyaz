<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Login extends TestCase
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
        echo $del.$del.' ==========================  Admin Login Test  ==========================='.$del.$del ; 
        $this->withoutExceptionHandling();

        /* Register */
        $code = time();
        $response = $this->withHeaders([
            'Accept' => 'application/json' ,
        ])->json('POST', '/api/v1/users?user[name]='.$code.'&user[password]='.$code.'&user[email]='.$code.'@ezuru.net&user[mobile]='.$code, []);
        $response->assertStatus(200)->assertJson(['response' => true]) ;
        echo $del."Success Register ->  Ok." ;
        

        /* Forget Password */
        $response = $this->withHeaders([
            'Accept' => 'application/json' ,
        ])->json('POST', '/api/v1/users/forget', ['email' => $code.'@ezuru.net']);
        $response->assertStatus(200)->assertJson(['response' => true]) ;
        echo $del."Forget Password ->  Ok." ;

    
        /* Login */
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/v1/users/login', ["email" => getenv('TEST_LOGIN_EMAIL')  ,"password" => getenv('TEST_LOGIN_PASS') ]);
        $response->assertStatus(200)->assertJson(['response' => true]) ;
        echo $del."Success Login ->  Ok." ;

        $json = (array) json_decode($response->content()) ; 

        $token = $json['response']->token ;

        /* Resend Activation Code */
        $response = $this->withHeaders([
            'Accept' => 'application/json' ,
            'Authorization' => 'Bearer '.$token 
        ])->json('POST', '/api/v1/users/resend', []);
        $response->assertStatus(200)->assertJson(['response' => true]) ;
        echo $del."Resend Activation Code ->  Ok." ;

        /* Verify Email */
        $response = $this->withHeaders([
            'Accept' => 'application/json' ,
            'Authorization' => 'Bearer '.$token 
        ])->json('POST', '/api/v1/users/verify', ['token' => $code]);
        $response->assertStatus(200)->assertJson(['response' => true]) ;
        echo $del."Verify Email Code ->  Ok." ;

        /* Send Mobile Activation */
        $response = $this->withHeaders([
            'Accept' => 'application/json' ,
            'Authorization' => 'Bearer '.$token 
        ])->json('GET', '/api/front/mobile/confirm', []);
        $response->assertStatus(200) ;
        // get code from Validate
        echo $del."Send Mobile Activation ->  Ok." ;
        $mobileCode = $response->content();

        /* Validate Mobile Code */
        $response = $this->withHeaders([
            'Accept' => 'application/json' ,
            'Authorization' => 'Bearer '.$token 
        ])->json('GET', '/api/front/mobile/validate/'.$mobileCode , []);
        $response->assertStatus(200)->assertSeeText(1) ;
        echo $del."Validate Mobile Code ->  Ok." ;

        /* Who Am I */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token ,
            'Accept' => 'application/json'

        ])->json('GET', '/api/v1/whoami', []);
        $response->assertStatus(200)->assertJson(['response' => true]) ;
        echo $del."Get User Details ->  Ok." ;

    }
}
