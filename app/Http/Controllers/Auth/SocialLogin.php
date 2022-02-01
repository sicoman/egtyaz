<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Socialite;
use JWTAuth ;
use Illuminate\Support\Facades\DB ;
use App\User ;
use Carbon\Carbon ;

use Illuminate\Support\Facades\Auth ;


use App\Models\Accounts ;
use App\Models\UserLogin ;
use App\Http\Resources\UserResource as UserResource;


class SocialLogin extends Controller
{

    protected $redirectTo = '/cp/packages' ;

    // Some methods which were generated with the app
    
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect() ;
    }

    
    // public function handleProviderCallback($provider)
    // {    
    //     if( $provider == 'twitter' ) {
    //         $user = Socialite::driver($provider)->user();
    //     }else{
    //         $user = Socialite::driver($provider)->stateless()->user();
    //     }
 
    //     return $this->LoginResult($user , $provider) ;
    // }


    public function handleProviderCallback($provider)
    {    
        if( $provider == 'twitter' ) {
            $user = Socialite::driver($provider)->user();
        }else{
            $user = Socialite::driver($provider)->stateless()->user();
        }
      
        $authUser = $this->findOrCreateUser($user, $provider , 'website');
    
        Auth::guard('web')->login($authUser, true);
  
        request()->session()->flash('toast-success', ', تم تسجيل الدخول بنجاح مرحبا '. $authUser->name);

        if(!$authUser->Payments()->where('status', 1)->count()){
            $this->redirectTo = route("packages");
        }else{
            $this->redirectTo = route("cpanel") ;
        }

        return redirect( $this->redirectTo );

    }



    /*
     * Enable Login By Access Token For Apps
     * 02-2019
     * @Momaiz
     */
    public function tokenLogin($provider = 'github' , Request $request ) {
     
        $input = $request->all();

        $token = $input['token'] ;

        if( !$token ){
            return response()->json([
                'user' => false
            ]);
        }

        $sec = '' ; if( isset($input['secret']) ){ $sec = $input['secret'] ; }

        if( $provider == 'twitter' and $sec != '' ) {
            $user = Socialite::driver($provider)->userFromTokenAndSecret( $token , $sec ) ;
        }else{
            $user = Socialite::driver($provider)->userFromToken( $token ) ;
        }

        // return (array)$user ;

        return $this->LoginResult($user , $provider) ;

    }

    protected function UserLogin($user_id  , $provider_id = '' , $provider = 'google') {
        $account = Accounts::firstOrCreate( [ 'user_id'=> $user_id , 'provider'=> $provider , 'provider_id'=> $provider_id ] ) ;
        UserLogin::create([
            'account_id' => $account->id
        ]);
    }

    protected function LoginResult($user , $provider ){
        $authUser  = $this->findOrCreateUser( $user , $provider );
        $userToken = JWTAuth::fromUser( $authUser ) ;
        $authUser->token = $userToken ;
        return response()->json( ["data" ] , Response::HTTP_OK)->header('Authorization', $userToken );
    }

   /**
     _ If a user has registered before using social auth, return the user
     _ else, create a new user object.
     _ @param  $user Socialite user object
     _ @param $provider Social auth provider
     _ @return  User
     */
    public function findOrCreateUser( $user , $provider , $register = 'website')
    {

        if( !isset( $user->email{5} ) ) {
            $user->email = $user->id.'@'.$provider.'.social' ;
        }

        $authUser = User::where( 'email' , $user->email  )->first();

        if ($authUser) {
            return $authUser;
        }



        $authUser = User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'image'    => ( $user->getAvatar() ?? "" ),
            'provider' => $provider,
            'provider_id' => $user->id,
            'registered_by' => $provider ,
            'registered_using' => $register ,
            'password' => \Hash::make( $user->id.rand(5599999,977779999).time() )
        ]);

        return $authUser ;
    }

}
