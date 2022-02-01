<?php
/**
 * File AuthController.php
 *
 * @version 1.0
 */
namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Laravue\JsonResponse;
use App\Services\UserService;
use App\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Socialite;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class UserController extends ApiController
{

    /** @var UserService */
    protected $userService; 

    public function __construct(UserService $userService)
    {
        $this->userService = $userService ;
    }

    public function getAuthenticatedUser(){
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('token', 'user'));
    }

    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials', "status" => 400], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', "status" => 500], 500);
        }

        $user = Auth::user(); 

        return response()->json(compact('token', 'user'));
    }

    public function validator($data){

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
    }

    public function register(Request $request){
        
        $data = $request->all();

        $validator = $this->validator($data);

        if ($validator->fails()) {    
            $errors = $validator->messages() ;
            return response()->json(["errors" => $errors, "status" => 400], 200);
        }

        $user =   $this->userService->add([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(["user" => $user,'token' => $token, "status" => 20], 201);

    }

    public function loginOrRegisterBySocial(Request $request){

        $input = $request->all();

        $provider = $input['provider'];

        if( !$request->has('token') ){
            return response()->json([
                'user' => false,
                'error'=> "No token provided"
            ]);
        }

        $token = $input['token'] ;

        $sec = '' ; if( isset($input['secret']) ){ $sec = $input['secret'] ; }

        try{
            if( $provider == 'twitter' and $sec != '' ) {
                $user = Socialite::driver($provider)->userFromTokenAndSecret( $token , $sec ) ;
            }else{
                $user = Socialite::driver($provider)->userFromToken( $token ) ;
            }
        }catch(Exception $exception){
            return response()->json([
                'user' => false,
                'error'=> "Invalid token",
                'status'=> 400
            ]);
        }

        return $this->LoginResult($user , $provider) ;
    }

    protected function LoginResult($user , $provider ){
        $data  = $this->findOrCreateUser( $user , $provider );
        $userToken = JWTAuth::fromUser( $data['user'] ) ;
        return response()->json( ["user" => $data['user'], "token" => $userToken, 'action' => $data['action'] ] , Response::HTTP_OK)->header('Authorization', $userToken );
    }

    public function findOrCreateUser( $user , $provider , $register = 'website')
    {

        if( !isset( $user->email{5} ) ) {
            $user->email = $user->id.'@'.$provider.'.social' ;
        }

        $authUser = User::where( 'email' , $user->email  )->first();

        if ($authUser) {
            return ['user' => $authUser, 'action' => 'login'];
        }

        $user =   $this->userService->add([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make( $user->id.rand(5599999,977779999).time() ),
            'avatar'    => ( $user->getAvatar() ?? "" ),
        ]);

        return ['user' => $user, 'action' => 'register'] ;
    }




     
}
