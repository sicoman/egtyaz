<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Options ;

use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function admin(){
        return view('admin') ;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    { 
        $options = Options::where('status' , 1 )->pluck('option_value' , 'option_var') ;
        view::share('options' , $options ) ;

        return view('frontend/index');
    }
}
