<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Frontend\FrontendController;
use App\Services\PostsService;
use App\Services\TaxonomyService;
use App\Traits\SiteMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ElearningController extends ApiController
{

    use SiteMeta;

    protected $postsService;
    protected $taxonomyService;


    public function __construct(PostsService $postsService, TaxonomyService $taxonomyService)
    {    
        parent::__construct();
        $this->setMeta('title', 'التأسيس');
        $this->registerSiteMeta();
        $this->postsService = $postsService;
        $this->taxonomyService = $taxonomyService;
        $this->addBreadCrumbLevel('الرئيسية', Route('cpanel'));
    }

    public function indexBreadCrumb(){
        $this->addBreadCrumbLevel('التأسيس', Route('my-exams'));
    }

    public function singleAjax(Request $request){
        $post = $this->postsService->find(["id" => $request->get('id')]);
        return response()->json($post);
    }


    public function index($parent = null , int $id = null){ 
        $params = [];
        if($id != null){
            $params['taxonomy_id'] = $id;
        }
        if($parent != null){
            $params['parent'] = $parent;
        }
        $posts = $this->postsService->byType("elearn", $params);
        $cats = $this->taxonomyService->getElearn();
        return $this->view('frontend.dashboard.elearning' , compact('posts', 'cats' , 'parent'));
    }


    public function show(int $id = null){ 
        $data = $this->postsService->find($id) ;
        if(!isset($data->id)){
            return redirect('/') ;
        }
        return view('frontend.page' , ['page' => $data ] );
    }
   

}