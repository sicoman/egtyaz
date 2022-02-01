<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\GraphQL\Type\UsersMutationType;
use \App\GraphQL\Type\UsersQueryType;
USE \EzuruCustom\Core\Traits\FlatParametersToObjs;
use JWTAuth;

use Hash ;

class AuthController extends UsersMutationType
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
        return (new UsersQueryType())->resolveGetAllField($root = true, $this->convert($request->toArray())->packParam($params, 'User')->mergeParams());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { 
         //resolveAuthViaSocialField
         return $this->resolveCreateField($root = true, $request->toArray());
    }

    /**  
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->resolveCreateField($root = true, $request->toArray());
    }

    public function authViaSocialLogin(Request $request)
    {  
        return $this->resolveAuthViaSocialField($root = true, $request->toArray());
    }

    public function login(Request $request)
    {   
        return $this->resolveLoginField($root = true, $request->toArray());
    }

    public function forget(Request $request)
    {   
        return $this->resolveForgetPasswordField($root = true, $request->toArray());
    }

    public function resend(Request $request)
    {   
        return $this->resolveResendEmailVerificationField($root = true, $request->toArray());
    }

    public function updateIdPhoto(Request $request)
    {   
        $params = [];
        $params['photoid'] = $request->file('photoid');
        $user = false ;
        $hasToken = (bool) app('tymon.jwt.parser')->hasToken(request()) ;
        if($hasToken){
          $user = JWTAuth::parseToken()->authenticate();
        }
        $params['user'] = (array) $user;

         

        return $this->resolveUpdatePhotoidField($root = true, $params);
    }   

    public function verify(Request $request)
    {   
        return $this->resolveVerifyField($root = true, $request->toArray());
    }   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
        $params = array_keys((new \App\GraphQL\Type\UsersType())->fields());  
        $params = $this->convert($request->toArray())->packParam($params, 'user')->mergeParams();
        if($request->hasFile('avatar')){
            $params['avatar'] = $request->file('avatar');
        }

        /*    
            if( isset($request['password'][5]) ){
                $params['password'] = Hash::make( $request['password'] ) ;
                $params['user']['password'] = $params['password'] ;
            }elseif( isset($request['password'][0]) ){
                $params['user']['password'] = auth()->user()->password ;
            }
        */

        return $this->resolveUpdateField($root = true, $params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
