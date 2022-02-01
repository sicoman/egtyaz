<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\FrontendController;
use App\Traits\SiteMeta;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends FrontendController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers; 
    use SiteMeta;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logoutUser');
        $this->setMeta('title', 'تسجيل الدخول');
        $this->registerSiteMeta();
    }

    public function showLoginForm(){
        return view('frontend/login');
    }

    protected function guard()
    {
        return Auth::guard("web");
    }


    protected function authenticated(Request $request, $user)
    {     
        $this->redirectTo = route("profile");
        $request->session()->flash('toast-success', ', تم تسجيل الدخول بنجاح مرحبا '. $user->name);
        if(!$user->Payments()->where('status', 1)->count()){
            $this->redirectTo = route("packages");
        }else{
            $this->redirectTo = route("cpanel") ;
        }

    }

    public function logoutUser(Request $request)
    {  

        $message = 'تم تسجيل الخروج بنجاح الى اللقاء '. Auth::user()->name;
    
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->flash('toast-success', $message);

        return $this->loggedOut($request) ?: redirect('/');
    }
}
