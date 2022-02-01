<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\FrontendController ;
use App\Traits\SiteMeta;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends FrontendController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    use SiteMeta;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {  
        $this->setMeta('title', "استرجاع كلمة المرور");
        $this->registerSiteMeta();
        $this->middleware('guest');
    }

    protected function sendResetResponse(Request $request, $response)
    {

        $message = trans($response);
        $message .= " بامكانك تسجيل الدخول بكلمة المرور الجديدة من خلال هذه الصفحة ";
        request()->session()->flash('toast-success', $message);
        request()->session()->flash('toast-time', 6000); 
        return redirect($this->redirectPath());
    }


}
