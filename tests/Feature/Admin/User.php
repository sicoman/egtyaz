<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class User extends TestCase
{
    protected $del = "\r\n" ;

    protected $types = [ 'manager' => 'Manager' , 'areamanager' => 'Area Manager'  , 'agent'  => 'Agent'  , 'editor'  => 'Editor'  , '' => 'User' ] ;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $del = $this->del ;
        $this->withoutExceptionHandling() ;
        $this->withoutMiddleware() ;

        $response = $this->json('POST', '/api/auth/login', ["email" => getenv('TEST_LOGIN_EMAIL')  ,"password" => getenv('TEST_LOGIN_PASS') ]);
            $response->assertStatus(200)->assertJson(['token' => true]);
            $token = (array) json_decode($response->content()) ; 
            echo $del."Admin Login to Check => Users ->  Ok." ;

        // Lets Get All Administrators
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token['token'] ,
            'Referer' => '/'
        ])->json('GET' , '/api/users/?type=' , []);
        $response->assertStatus(200);

        echo $del."Admin Users Show List ->  Ok." ;  
        
        // Search Administrators By Type
        $i = time() ; 
        foreach( $this->types as $type => $Groupname ){
            $i++;
            // Lets Get All Administrators
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('GET' , '/api/users/?role='.$type , []);
            $response->assertStatus(200);

            echo $del."Admin Users { $Groupname } Show List ->  Ok." ;

            // Add New User From This Types    
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('POST' , '/api/users/' , [
                'name' => $type.' No '.$i ,
                'email' => $type.$i.'@ezuru.net' ,
                'password' => 'XXXXXXXX'.$i ,
                'confirmPassword' => 'XXXXXXXX'.$i , 
                'role' => $type
            ]);

            $us = (array) json_decode($response->content()) ;

            $user = $us['data']->id ;

            $response->assertStatus(201);

            echo $del."Admin Users { $Groupname } Add New ->  Ok." ;


            // Edit User From This Types    
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('PUT' , '/api/users/'.$user , [
                'id' => $user ,
                'name' => $type.' No '.$i.' _Update' ,
                'email' => $type.$i.'@ezuru.net' ,
                'password' => 'XXXXXXXXx'.$i ,
                'password2' => 'XXXXXXXXx'.$i
            ]); 
            
            $response->assertStatus(200);
            echo $del."Admin Users { $Groupname } Edit User ->  Ok." ;

            // Delete User From This Types    
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token['token'] ,
                'Referer' => '/'
            ])->json('DELETE' , '/api/users/'.$user , []);
            $response->assertStatus(204);
            echo $del."Admin Users { $Groupname } Delete User ->  Ok." ;

        }

    }
}
