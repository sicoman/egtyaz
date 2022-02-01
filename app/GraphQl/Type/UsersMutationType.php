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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use JWTAuth ;
use Illuminate\Support\Facades\DB ;
use Illuminate\Http\Request ; 
use Carbon\Carbon ;
use App\Models\Accounts ;
use App\Models\UserLogin ;
use App\Http\Resources\UserResource as UserResource;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UNotify;
use Storage;
use finfo;
 


class ResetPassword{
    use ResetsPasswords;
    public function reset($args){

        $request = request(); 

        $request->merge(['token' => urldecode($args['token']), 'email' => $args['email'], 'password' => $args['password'], 'password_confirmation' => $args['password']]);

        try{
            $request->validate($this->rules(), $this->validationErrorMessages());
        }
        catch(Exception $e){ 
            return (['message' => $e->getMessage(), 'result' => false]);
        }
     
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {     
                $this->resetPassword($user, $password);
            }
        );

        $result =  $response == Password::PASSWORD_RESET
            ? ['message' => 'Congratulations your password has been reset successfully !.', 'result' => true]
            : ['message' => 'Unable to reset your password', 'result' => false];
        
        return $result;
    }
}



class UsersMutationType extends ResponseQueryType {

    use SendsPasswordResetEmails;

    protected $attributes = [
        'name' => 'UsersMutationType',
        'description' => 'Users api '
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
//        $response->isMulti = false ;
//        print_r($response->fields()); die ; 
        $response = GraphQL::type('UsersSingleResponse');
        $upload = new \Rebing\GraphQL\Support\UploadType();
        return [
            'Create' => [
                'type' => $response,
                'args' => [
                    'user' => ['name' => 'user', 'type' => GraphQL::type('UsersInput')]
                ]
            ],
            'Update' => [
                'type' => $response,
                'args' => [
                    'user' => ['name' => 'user', 'type' => GraphQL::type('UsersInput')],
                    'avatar' => [
                        'name' => 'avatar',
                        'type' => $upload,
                        'rules' => ['required', 'image', 'max:1500'] //Validatation rules xD 
                    ],
                ]
            ],
            'UpdatePhotoid' => [
                'type' => $response,
                'args' => [
                    'user' => ['name' => 'user', 'type' => GraphQL::type('UsersInput')],
                    'photoid' => [
                        'name' => 'photoid',
                        'type' => $upload,
                        'rules' => ['required', 'image', 'max:1500'] //Validatation rules xD 
                    ]
                ]
            ],
            'Login' => [
                'type' => $response,
                'args' => [
                    'email' => [
                        'name' => 'email',
                        'type' => Type::string(),
                    ],
                    'password' => [
                        'name' => 'password',
                        'type' => Type::string(),
                    ]
                ]
                    ],
            'GetSocialLoginUrl' => [
                'type' => Type::listOf(GraphQL::type('KeyValuePairType'))
            ],
            'ForgetPassword' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                    'email' => [
                        'name' => 'email',
                        'type' => Type::string(),
                    ]
                ]
            ],
            'ResendEmailVerification' => [
                'type' => GraphQL::type("BooleanReportSingleResponse")
            ], 
            'Verify' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                    'token' => [
                        'name' => 'token',
                        'type' => Type::string(),
                    ]
                ]
            ], 
            'CheckResetToken' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                    'token' => [
                        'name' => 'token',
                        'type' => Type::string(),
                    ],
                    'email' => [
                        'name' => 'email',
                        'type' => Type::string(),
                    ]
                ]
            ],     
            'ResetPassword' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                    'token' => [
                        'name' => 'token',
                        'type' => Type::string(),
                    ],
                    'email' => [
                        'name' => 'email',
                        'type' => Type::string(),
                    ],
                    'password' => [
                        'name' => 'password',
                        'type' => Type::string(),
                    ]
                ]
            ],
            'AuthViaSocial' => [
                'type' => $response,
                'args' => [
                    'token' => [
                        'name' => 'token',
                        'type' => Type::string()
                    ],
                    'type' => [
                        'name' => 'type',
                        'type' => Type::string()
                    ]
                ]
            ]     
        ];
    }

    public function resolveVerifyField($root, $data)
    {
 
        // if(!$this->isAuthorized())
        //     return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);

        // $user = $this->isAuthorized(); 

        $token = DB::table('password_resets')->where('token', $data['token']);
      
        if ($token->first()) {
            $user = User::where(['email' => $token->first()->email])->first();  
            $token->delete();
            $user->update(['email_verified_at' => Carbon::now()]);
            // event(new Verified($user));
            return $this->resolveResponse(["result" => true]);
        }
        return $this->resolveResponse(["result" => false]);
  
    }

    public function resolveResendEmailVerificationField($root, $data){

        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);
 
        $userObj = $this->isAuthorized(); 
         
        $userObj->sendEmailVerificationNotification();
        
        return $this->resolveResponse(["result" => true]);
    }      

    
    public function resolveCheckResetTokenField($root, $data){

        if(!isset($data['token']) || !isset($data['email'])){
            return $this->resolveResponse(['message' => 'Invalid token', 'result' => false]);
        }
        
        $token = DB::table('password_resets')->where('email', ($data['email']))->first();

        if($token){
            if (Hash::check($data['token'], $token->token))
            {
                return $this->resolveResponse(['message' => $token->email, 'result' => true]);
            }

        }else{
            return $this->resolveResponse(['message' => 'Invalid email', 'result' => false]);
        }
        
        return $this->resolveResponse(['message' => 'Invalid data', 'result' => false]);
    }  


    public function resolveResetPasswordField($root, $data){

        if(!isset($data['token'])){
            $data['token'] = '';
        }
        if(!isset($data['password'])){
            $data['password'] = '';
        }
        if(!isset($data['email'])){
            $data['email'] = '';
        }
        $result = (new ResetPassword)->reset($data);

        return $this->resolveResponse($result);
        
    }  
    
    public function resolveForgetPasswordField($root, $data){

        $request = request(); 

        $request->merge(['email' => $data['email']]);
           
        try{  
          $result = $this->validateEmail($request);
    
        }
        catch(Exception $e){ 
            throw  $this->resolveResponse(['message' => 'Invalid email', 'result' => false]);
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );


        $result = ['message' => 'Reset link sent to your email.', 'result' => true] ;
        
        return $this->resolveResponse($result);
        
    }  

    public function resolveUpdatePhotoidField($root, $data){
      
        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);
 
        $user = array_filter( $data['user']) ;

        $userObj = $this->isAuthorized(); 

        if(isset($data['photoid'])) {   
            $path = $data['photoid']->store('users', 'public') ;
            $user['photoid'] = $path; 
            $user['photoid_verified_at'] = null;
            $userObj->update($user);
            return $this->resolveResponse($userObj->fresh(),null,"User photoid has been updated");
        }else{
            return $this->resolveErrors(["No valid images"]);
        }

    }
    
    public function resolveUpdateField($root, $data){
      
        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);
 
        $user = array_filter( $data['user']) ;
 
        $validator = Validator::make($user, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|min:10',
            'password' => 'string',
            'description' => 'string'
        ]);
           
        if ($validator->fails()) {  
          return $this->resolveErrors($validator->errors()->all());
        }   

        $userObj = $this->isAuthorized(); 

        if(isset($data['avatar'])) {   
            $path = $data['avatar']->store('users', 'public') ;
            $user['avatar'] = $path; 
        }

        if( isset($user['password']) && $user['password'] != '' ){
            $user['password'] = Hash::make( $user['password'] ) ;
        }
  
       $userObj->update($user);

       $changes = $userObj->getChanges();
       
       if(!empty($changes)){
            if(isset($changes['mobile'])){
                $userObj->update(['mobile_verified_at' => null]);
            }
       }

       $userObj->rate = $userObj->rate ;

        return $this->resolveResponse($userObj->fresh(),null,"User has been updated");
    }

    public function getFbImg($url){
        $_img = "users/fb_image_".uniqid();
        $file_info = new finfo(FILEINFO_MIME_TYPE);
        $img = file_get_contents($url) ;
        $mime_type = $file_info->buffer($img);
        if($mime_type == "image/jpeg"){
            $_img .= ".jpg";
        }
         file_put_contents("storage/".$_img, $img);  
        return $_img;
        
    }

    public function resolveAuthViaSocialField($root, $data){
            $request = request();
            $res = $request->all();
            $provider = $data['type'] ;  
            
            if( $provider == 'apple' and isset( $request->email ) ){
                $user = ['id' => 'apple-'.rand(4,6).time() , 'avatar' => '' , 'email' => $request->email , 'name' => $request->name ?? $request->email ];
                $user = (object) $user ;
            }else{
                $user = Socialite::driver($provider)->userFromToken($data['token']);
            }
 
            if($user){
                if( !isset( $user->email[5] ) ) {
                    $user->email = $user->id.'@'.$provider.'.social' ;
                }

                $authUser = User::where( 'email' , $user->email )->first();
                if ($authUser) {
                    // Lets Check If user has provider with this credintals
                    $this->UserLogin( $authUser->id , $user->id , $provider ) ;
                    $userToken = JWTAuth::fromUser( $authUser ) ;
                    if(!isset($authUser->avatar[10])){  
                       $img =  $this->getFbImg($user->avatar);
                       $authUser->avatar = $img ;
                       $authUser->update();
                    }
                    $authUser->token = $userToken ;

                    return ( ["status" => 200, "message" => "logged in successfully!", "response" => $authUser, "errors" => []]);
                }
        //من اول هنا بيسجل المستخدم لو ما كانش موجود ف قاعدة البيانات 
                $authUser = $this->registerUser($user);
        
              //  $authUser->assignRole('user') ; No need 
                $userToken = JWTAuth::fromUser( $authUser ) ;
                if( !isset($authUser->avatar[10]) && isset($user->avatar[10]) ){  
                    $img =  $this->getFbImg($user->avatar);
                    $authUser->avatar = $img ;
                    $authUser->update();
                 }
                $authUser->token = $userToken ;
                return ( ["status" => 200, "message" => "logged in successfully!", "response" => $authUser, "erorrs" => []]);
            }
  
    }



    public function registerUser($user , $isSocial = true){

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        if($isSocial){

            $authUser = User::create([
                'name'     => $user->name,
                'email'    => $user->email,
                'email_verified_at' => date('Y-m-d h:i:s') ,
                'city'     => null ,
                'password' => \Hash::make( $user->id.rand(5599999,977779999).time() )
            ]);

        }else{
            $authUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'mobile' => isset($user['mobile']) ? $user['mobile'] : null,
                'description' => isset($user['description']) ? $user['description'] : '',
                'photoid' => isset($user['photoid']) ? $user['photoid'] : null,
                'city' => isset($user['city_id']) ? $user['city_id'] : null
           ]);
           $authUser->sendEmailVerificationNotification();
        }


        // Notification::send( $authUser , new UNotify( $authUser->toArray() , 'user_welcome' ,  $authUser->lang ) ) ;

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

        return $authUser ;
    }

    public function UserLogin($user_id  , $provider_id = '' , $provider = 'google') {
        $account = Accounts::firstOrCreate( [ 'user_id'=> $user_id , 'provider'=> $provider , 'provider_id'=> $provider_id ] ) ;
        UserLogin::create([
            'account_id' => $account->id
        ]);
    }

    public function resolveGetSocialLoginUrlField($root, $data){
        return [
            ["key" => "google", "value" => Socialite::driver("google")->stateless()->redirect()->getTargetUrl()]
        ];
    }
    
    public function resolveCreateField($root, $data) {

        $request = Request();
        $data = $data['user'] ; 
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|min:10|string|unique:users',
            'password' => 'required|string|min:6',
            'description' => 'string'
        ]);

 
         if ($validator->fails()) {   
            return $this->resolveErrors($validator->errors()->all());
        }
       
        $user = $this->registerUser($data, false);

        $userToken = JWTAuth::fromUser( $user ) ;
        $user->token = $userToken ;

        // if($request->file('image')) {
        //     $user->addImage($request->file('image')->store('users', 'public'), true);
        // }
 
        return $this->resolveResponse($user,null,"User has been created");
    }

    public function resolveLoginField($root, $args) { 
        
        if (! $token = JWTAuth::attempt(["email" => $args['email'], "password" => $args['password']])) {
            $status = false;
            $errors = [
                "login" => "Invalid username or password",
            ];
            $message = "Login Failed";
            return $this->resolveErrors($errors, null, "incorrect information",401);
        }else{ 
            $message = "Login Successfull";
            $data = [
                'access_token' => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ];      
            $user = Auth::user();
            $user->token = $token ;
            return $this->resolveResponse($user,null, "Token has been created successfully");
        }

        return null;
    }

    public function resolveUpdatePasswordField($root, $args) {
        $validator = Validator::make($args, [
            'oldPassword' => 'string|required',
            'newPassword' => 'string|required|min:5',
            'confirmPassword' => 'string|required|min:5',
        ]);
        if ($validator->fails()) {  
            return $this->resolveErrors($validator->errors()->all());
        }
        $user = User::where('id', $args['id'])->first();
        if($user) {
            $oldPasswordCheck = Hash::check($args['oldPassword'], $user->password);
            if($oldPasswordCheck) {
                if($args['newPassword'] == $args['confirmPassword']) {
                    $user->update(['password' => bcrypt($args['newPassword'])]);
                    return $this->resolveResponse($user, null, "Password Update Successfully");
                } else {
                    throw new Exception('Password does not match with confirmation password');
                }
            }else {
                throw new Exception('Old Password is wrong');
            }
        }else {
            throw new Exception('User with this ID is not exist!');
        }
        return null;
    }

}
