<?php

namespace App\Http\Controllers\Frontend\Api;
use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\AskTeacherRepository ;

use App\Repositories\AskAnswersRepository ;

use App\Services\TaxonomyService ;

use App\Traits\SiteMeta;

use App\Models\Options ;

use DB ;
use Illuminate\Support\Facades\Auth;
use View ;

class ApiController extends FrontendController
{

    protected $isApi = true;

    public function __construct( )
    {

    }

    public function getUser(){
      return $this->guard()->user();
  }

  public function checkIsUser(){
      return $this->guard()->check();
  }

  private function guard(){
      return Auth::guard('api');
  }


    
}
