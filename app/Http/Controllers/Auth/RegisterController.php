<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Frontend\FrontendController;
use App\Services\PointsService;
use App\Services\TaxonomyService;
use App\Services\UserService;
use App\Traits\SiteMeta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request ;

use Session ;

class RegisterController extends FrontendController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use SiteMeta;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/cp/packages';


    /** @var UserService */
    protected $userService;

    protected $pointsService;

    protected $taxService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, PointsService $pointsService, TaxonomyService $taxService)
    {
        $this->middleware('guest');
        $this->setMeta('title', "تسجيل حساب جديد");
        $this->registerSiteMeta();
        $this->userService = $userService;
        $this->pointsService = $pointsService;
        $this->taxService = $taxService;
    }

    protected function guard()
    {
        return Auth::guard("web");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'gender' => [  'required', Rule::in(['male', 'female'])]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $data['password'] = Hash::make($data['password']);

        $user =   $this->userService->add($data);

        if(isset($data['referer'])){
            // $this->pointsService->NewRegisterPoint($this->userService, $data['referer'], $user);

        }

        $message = 'لقد تم تسجيل حسابك بنجاح شكرا لك و نتمنى لك وقت ممتع فى منصتنا ,' .$user->name;
        $message .= ', تم تسجيل دخولك بنجاح يمكنك الان البدء فى استخدام منصتنا';

        request()->session()->flash('toast-success', $message);
        request()->session()->flash('toast-time', 6000);


        return $user;

    }

    public function showRegistrationForm(Request $request){
        $stages = $this->taxService->byType('student_stage');

        if( isset($request->referer) ){
            // Cookie::queue('referer',$request->referer, 30);
            \Session::put('referer', $request->referer );
        }
        return view('frontend/register', compact('stages'));
    }
}
