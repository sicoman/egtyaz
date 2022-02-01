<?php 

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\FrontendAuth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

use App\Laravue\Models\User as Usery ;

use Illuminate\Support\Facades\Auth ;

class FrontendController extends Controller {

    use FrontendAuth;
    
    protected $breadCrumbs = [];

    protected $isApi = false ; 

    public function __construct()
    {

    }

    public function view($template, $data = []){       
        if($this->isApi){
            return response()->json($data);
        }
        $route = Route::current();
        $action= explode('@', $route->action['controller'])[1];
        $method= $action.'BreadCrumb';
        if(method_exists($this, $method)){
            $this->$method();
            $this->breadCrumbs = array_map(function($item) use($data){
                $item['name'] = preg_replace_callback('/\{\$(.*)\}/', function($matches) use($data){
                     return $data[$matches[1]] ?? null;
                },$item['name']);
                return $item;
            }, $this->breadCrumbs);
            View::share('BREADCRUMBS', $this->breadCrumbs);
        }
        if( Auth::check() ){
            $this->shareUser();
        }
        

        return view($template, $data);
    }

    public function addBreadCrumbLevel($name, $url){
        array_push($this->breadCrumbs, ['name' => $name, 'url' => $url]);
    }

    public function shareUser(){
         View::share('CURRENT_USER', $this->getUser());
         View::share('IS_USER_AVAILABLE', $this->checkIsUser());


         $user = Usery::find( Auth::user()->id ) ;
         $role = $user->roles[0]->name ?? 'student' ;
         View::share('CURRENT_USER_ROLE', $role );

    }

}