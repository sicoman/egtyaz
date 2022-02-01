<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\GraphQL\Type\UsersQueryType;
USE \EzuruCustom\Core\Traits\FlatParametersToObjs;

use JWTAuth ;

class UsersController extends UsersQueryType
{

    use FlatParametersToObjs;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
        $params = array_keys((new \App\GraphQL\Type\UsersType())->fields());  
        return $this->resolveGetAllField($root = true, $this->convert($request->toArray())->packParam($params, 'User')->mergeParams());
    }

    public function currentUser(Request $request)
    {
        if( isset($request->refresh_token) && $request->refresh_token == true ){
            
            JWTAuth::parseToken();
            $token = JWTAuth::getToken();
            
            $token = JWTAuth::refresh($token);
            JWTAuth::setToken($token);    

            // Authenticate with new token, save user on request
            $request->user = JWTAuth::authenticate($token);

            return $token ;

        }     

        $user = $this->resolveGetCurrentField($root = true, $request->toArray());


        $per = 10 ;

        if( !is_null($user['response']->email_verified_at) ){
            $per = $per + 30 ;
        }

        if( !is_null($user['response']->mobile_verified_at) ){
            $per = $per + 30 ;
        }

        if( !is_null($user['response']->photoid_verified_at) ){
            $per = $per + 30 ;
        }

        $user['response']->verify_percent = $per ;
        // $user->verify_percent = $per ;

        return $user ;
    }


    public function getNotifications(Request $request)
    {     
        return $this->resolveGetNotificationField($root = true, $request->toArray());
    }

    
}
