<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Http\Parser ;
use JWTAuth;

class ResponseQueryType extends GraphQLType {

    protected $message = 'Your request has been processed';
    protected $responseLimit  = 10;
    protected $attributes = [
        'name' => 'deeeeeeeee',
        'description' => 'Responses api '
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function resolveResponse($response, $pagination = null, string $message = null, int $status = 200) {

        $statusCode = app('Illuminate\Http\Response')->status();

        return array_filter(['response' => $response, 'pagination' => $pagination, 'message' => is_null($message) ? $this->message : $message, 'status' => is_null($status) ? $statusCode : $status]);
    }
    
    public function resolveErrors($errors, $pagination = null, string $message = null, int $status = 200) {

        return ['response' => null, 'errors' => $errors,'pagination' => $pagination, 'message' => $message, 'status' => is_null($status) ? $statusCode : $status];
        
    }

    public function resolveDeniedAccess(){

    }
    
    
    public function isAuthorized()
    {  
        $user = false ;
        $hasToken = (bool) app('tymon.jwt.parser')->hasToken(request()) ;
        if($hasToken){
          $user = JWTAuth::parseToken()->authenticate();
        }
        return $user ;
    }
    

}
