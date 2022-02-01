<?php

namespace Tests\Feature\Admin;

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
        
        $response = $this->json('POST', '/api/auth/login', ["email" => "00".getenv('TEST_LOGIN_EMAIL') ,"password" => getenv('TEST_LOGIN_PASS').'D0D0D0D0' ]);
        $response->assertStatus(401)->assertJson(['error' => "login_error"]);
        echo $del."Failed Login ->  Ok." ;

        $response = $this->json('POST', '/api/auth/login', ["email" => getenv('TEST_LOGIN_EMAIL')  ,"password" => getenv('TEST_LOGIN_PASS') ]);
        $response->assertStatus(200)->assertJson(['token' => true]);
        echo $del."Success Login ->  Ok." ;

        $token = (array) json_decode($response->content()) ; 


        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token['token']
        ])->json('GET', '/api/auth/user', []);
        $response->assertStatus(200)->assertJson(['data' => true]) ;
        echo $del."Get User Details ->  Ok." ;


        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token['token']
        ])->json('POST', '/api/auth/logout', []);
        
        $response->assertStatus(200) ;
        echo $del."User Logout ->  Ok." ;

    }
}
