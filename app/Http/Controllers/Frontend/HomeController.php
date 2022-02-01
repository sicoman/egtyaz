<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\SiteMeta;

use Illuminate\Http\Request;

use App\Repositories\FrontEndRepository ;
use App\Models\Posts ;

class HomeController extends Controller
{
    use SiteMeta;

    public $frontend ;

    public function __construct(FrontEndRepository $frontend)
    {
        $this->frontend = $frontend ;
        $this->setMeta('title', '');
        $this->registerSiteMeta();
    }

    public function index()
    {
        $data = $this->frontend->homePage() ;
        
        return view('frontend.index' , $data );
    }

    public function page($name = 'about')
    {
        $ids = [
            'about' => 462 ,
            'terms' => 463 ,
            'privacy' => 464 ,
            'faq' => 465
        ];
        $data = Posts::where('id' , $ids[$name] ?? $ids['about'])->first() ;
        if(!isset($data->id)){
            return redirect('/') ;
        }
        return view('frontend.page' , ['page' => $data ] );
    }

    public function contact()
    {
        $data = $this->frontend->contact() ;
        return view('frontend.pages.contact' , $data );
    }

    public function Postcontact(Request $request)
    {
        $this->validate( $request , [
            'name'    => 'required' ,
            'email'   => 'required|email',
            'message' => 'required'  
        ]);

    
        $data = $this->frontend->postContact($request) ;

        if ( $data['success'] === true ){
           return redirect()->back()->with('toast-success', $data['message']  );
        }else{
           return redirect()->back()->with('toast-error', 'حدث خطا ما');
        }

    }



}
