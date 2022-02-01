<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class Taxonomy extends TestCase
{
        protected $del = "\r\n" ;

        protected $types = ['aminites','area','badge','careers','category','city','contact','country','cpolicy','faq','fee','government','news','policy','rest','review_type','unit_badge','user_badge','views'] ;

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
            echo $del."Admin Login to Check => Taxonomy ->  Ok." ;

            foreach( $this->types as $type ) {

                echo $del.$del.' ==========================  Admin Taxonomies : '.$type.' Test  ==========================='.$del.$del ; 
            
                // Get $type List
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Referer' => '/'
                ])->json('GET' , '/api/admin/taxonomy/?type='.$type , []);
                $response->assertStatus(200)->assertJson(['current_page' => true]);

                echo $del."Taxonomy { $type } Show List ->  Ok." ;

                // Search $type

                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Referer' => '/'
                ])->json('GET' , '/api/admin/taxonomy/?type='.$type.'&s=Khalifa' , []);
                $response->assertStatus(200)->assertJson(['current_page' => true]);

                echo $del."Taxonomy { $type } Search List ->  Ok." ;
        
                // Add $type

                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/taxonomy/?type='.$type.'&s=Khalifa' , [ 
                    'name' =>  $type." 0 Title",
                    'name_en' => $type." 0 Title",
                    'description' => $type." 0 Test Description",
                    'description_en' => $type." 0 Test Description",
                    'status' => 1
                    
                ]);
                $response->assertStatus(200)->assertJson(['status' => 1]);

                // Lets Get THis Post Id
                $post_id = json_decode( $response->content() )->id ;
                
                echo $del."Taxonomy { $type } Add New ->  Ok." ;

                // Update $type Post
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/taxonomy/?type='.$type , [
                    'id'    => $post_id , 
                    'name' =>  $type." 0 Title After Update",
                    'name_en' => $type." 0 Title After Update",
                    'description' => $type." 0 Test Description After Update",
                    'description_en' => $type." 0 Test Description After Update",
                    'status' => 1
                ]);
                $response->assertStatus(200)->assertJson(['status' => 1]);

                echo $del."Taxonomy { $type } Edit Taxonomy ->  Ok." ;

                // Set Status $type [  Disable Post ]
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/taxonomy/active/'.$post_id , [
                    'status' => 0
                ]);
                $response->assertStatus(200)->assertJson(['res' => true]);
                echo $del."Taxonomy { $type } Disable Post ->  Ok." ;

                // Set Status $type [  Enable Post ]
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/taxonomy/active/'.$post_id , [
                    'status' => 1
                ]);
                $response->assertStatus(200)->assertJson(['res' => true]);
                echo $del."Taxonomy { $type } Enable Taxonomy ->  Ok." ;

                // Delete $type
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('DELETE' , '/api/admin/taxonomy/'.$post_id , [
                    'status' => 1
                ]);
                $response->assertStatus(200)->assertJson(['res' => true]);
                echo $del."Taxonomy { $type } Delete Taxonomy ->  Ok." ;


                sleep(2) ;

            }
    
            
        }
    }
