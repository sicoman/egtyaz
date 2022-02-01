<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Traits\Curly ;

class Units extends TestCase
{
    use Curly ;
    protected $del = "\r\n" ;
    protected $token = '' ;
    protected $unit   = 0 ;
    protected $options = [] ;


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
     *  PreCreate Unit
     *  Update Unit
     *  
     */
    public function testCreateUnit()
    {

        $this->login();
        $this->checkToken();

        // PreCreate Unit
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'

        ])->json('GET', 'api/front/units/0?locale=en', []);

        $response->assertStatus( 200 );

        $unit = (array) json_decode( $response->content() ) ;

        $this->unit = (array) $unit['unit'] ; $this->options = (array)  $unit['options'] ;

        unset( $this->unit['options'] ) ;
        
        echo $this->del."Create Unit { pre Create unit } ->  Ok." ;

        // Update Unit as Draft 
        $this->unit['status'] = -10 ;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'
        ])->json('PUT', 'api/front/units/'.$this->unit['id'].'?locale=en', $this->unit );
        
        $response->assertStatus( 200 );

        echo $this->del."Set Unit Status { Draft } ->  Ok." ;


        // Update Unit Set Images , Days , etc
        $this->unit['dates'] = [] ;
        $this->unit['attachments'] = [
            [
                'unit_id' => $this->unit['id'] ,
                'image' => 'https://i.pravatar.cc/780' ,
                'title' => 'New IMage' ,
                'ordr' => 1
            ],
            [
                'unit_id' => $this->unit['id'] ,
                'image' => 'https://i.pravatar.cc/780' ,
                'title' => 'New IMage' ,
                'ordr' => 1
            ],
            [
                'unit_id' => $this->unit['id'] ,
                'image' => 'https://i.pravatar.cc/780' ,
                'title' => 'New IMage' ,
                'ordr' => 1
            ],
            [
                'unit_id' => $this->unit['id'] ,
                'image' => 'https://i.pravatar.cc/780' ,
                'title' => 'New IMage' ,
                'ordr' => 1
            ],
            [
                'unit_id' => $this->unit['id'] ,
                'image' => 'https://i.pravatar.cc/780' ,
                'title' => 'New IMage' ,
                'ordr' => 1
            ]
        ] ;
        $this->unit['title'] = time().' Unit title' ;
        $this->unit['dates'] = [
            [
                'unit_id' => $this->unit['id'] ,
                'date' => date('Y-m-25') ,
                'status' => 1 ,
                'price' => 500 ,
                'price_before' => 350 ,
            ],
            [
                'unit_id' => $this->unit['id'] ,
                'date' => date('Y-m-26') ,
                'status' => 0 ,
                'price' => 500 ,
                'price_before' => 500 ,
            ],
            [
                'unit_id' => $this->unit['id'] ,
                'date' => date('Y-m-27') ,
                'status' => 2 ,
                'price' => 500 ,
                'price_before' => 350 ,
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'
        ])->json('PUT', 'api/front/units/'.$this->unit['id'].'?locale=en', $this->unit );
        
        $response->assertStatus( 200 );

        echo $this->del."Update Unit With Images and Dates ->  Ok." ;

        // Update Unit Set Status Updated
        $this->unit['status'] = 2 ;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'
        ])->json('PUT', 'api/front/units/'.$this->unit['id'].'?locale=en', $this->unit );
        
        $response->assertStatus( 200 );

        echo $this->del."Set Unit Status { Updated } ->  Ok." ;

        // Get Unit Page 
        $response = $this->withHeaders([
         //   'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'
        ])->json('GET', 'api/v1/units/'.$this->unit['id'].'?locale=en', [] );

        $response->assertStatus( 200 );
        echo $this->del."Get Unit With Full Details { unit , images , owner , dates ... } ->  Ok." ;

        // User Flag { Report } Unit With Custom Description
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token ,
            'Accept' => 'application/json'
        ])->json('POST', '/api/v1/flag-unit', [
            'flagged_id' => $this->unit['id'] ,
            'type' => 'unit' ,
            'description' => time().' Report Description'
        ] );

        $response->assertStatus( 200 );
        echo $this->del."Report Unit And Set Description ->  Ok." ;

    }
}
