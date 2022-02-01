<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class Posts extends TestCase
{
        protected $del = "\r\n" ;

        protected $types = ['news' , 'about' , 'checkin' , 'contact' , 'faq' , 'how_work' , 'partiners' , 'payment_methods' , 'trust' , 'cancle' ,'why_ezuru' , 'blog' ,'careers' , 'slider' , 'tourist_places' , 'popular_places'] ;


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
            echo $del."Admin Login to Check => Posts ->  Ok." ;

            foreach( $this->types as $type ) {

                echo $del.$del.' ==========================  Admin Posts : '.$type.' Test  ==========================='.$del.$del ; 
            
                // Get $type List
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Referer' => '/'
                ])->json('GET' , '/api/admin/post/?type='.$type , []);
                $response->assertStatus(200)->assertJson(['current_page' => true]);

                echo $del."Posts { $type } Show List ->  Ok." ;

                // Search $type

                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Referer' => '/'
                ])->json('GET' , '/api/admin/post/?type='.$type.'&s=Khalifa' , []);
                $response->assertStatus(200)->assertJson(['current_page' => true]);

                echo $del."Posts { $type } Search List ->  Ok." ;
        
                // Add $type

                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/post/?type='.$type.'&s=Khalifa' , [ 
                    'title' =>  $type." 0 Title",
                    'title_en' => $type." 0 Title",
                    'description' => $type." 0 Test Description",
                    'description_en' => $type." 0 Test Description",
                    'status' => 1
                    
                ]);
                $response->assertStatus(200)->assertJson(['status' => 1]);

                // Lets Get THis Post Id
                $post_id = json_decode( $response->content() )->id ;
                
                echo $del."Posts { $type } Add New ->  Ok." ;

                // Update $type Post
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/post/?type='.$type , [
                    'id'    => $post_id , 
                    'title' =>  $type." 0 Title After Update",
                    'title_en' => $type." 0 Title After Update",
                    'description' => $type." 0 Test Description After Update",
                    'description_en' => $type." 0 Test Description After Update",
                    'status' => 1
                ]);
                $response->assertStatus(200)->assertJson(['status' => 1]);

                echo $del."Posts { $type } Edit Post ->  Ok." ;

                // Set Status $type [  Disable Post ]
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/post/active/'.$post_id , [
                    'status' => 0
                ]);
                $response->assertStatus(200)->assertJson(['res' => true]);
                echo $del."Posts { $type } Disable Post ->  Ok." ;

                // Set Status $type [  Enable Post ]
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('POST' , '/api/admin/post/active/'.$post_id , [
                    'status' => 1
                ]);
                $response->assertStatus(200)->assertJson(['res' => true]);
                echo $del."Posts { $type } Enable Post ->  Ok." ;

                // Delete $type
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer '.$token['token'] ,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',        
                    'Referer' => '/'
                ])->json('DELETE' , '/api/admin/post/'.$post_id , [
                    'status' => 1
                ]);
                $response->assertStatus(200)->assertJson(['res' => true]);
                echo $del."Posts { $type } Delete Post ->  Ok." ;


                sleep(2) ;

            }
    
            
        }
    }
