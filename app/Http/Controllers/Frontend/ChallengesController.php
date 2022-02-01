<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendController;
use App\Repositories\StudentRepository;
use App\Services\ChallengeService;
use App\Services\TaxonomyService;
use App\Services\PostsService ;
use App\Services\UserService;
use App\Traits\SiteMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB ;

class ChallengesController extends FrontendController
{

    use SiteMeta;

    protected $userService;

    protected $taxService;

    protected $postServices;

    protected $challengeService;

    protected $Stu ;

    public function __construct(PostsService $postServices , StudentRepository $stu ,UserService $userService, TaxonomyService $taxService, ChallengeService $challengeService)
    {    
        parent::__construct();
        $this->setMeta('title', 'المسابقات');
        $this->registerSiteMeta();
        $this->userService = $userService;
        $this->challengeService = $challengeService;
        $this->taxService  = $taxService;
        $this->postServices  = $postServices;
        $this->Stu = $stu ;

        $this->addBreadCrumbLevel('الرئيسية', Route('cpanel'));

    }

    public function indexBreadCrumb(){
        $this->addBreadCrumbLevel('المسابقات', Route('challenges'));
    }


    public function index(Request $request){
       
        parent::shareUser();

        $subjects = $this->taxService->getSubjects();

        $skills = $this->taxService->getSkills();
   
        return $this->view('frontend.dashboard.challenges', compact('subjects', 'skills'));
    }

    public function oldBreadCrumb(){
        $this->addBreadCrumbLevel('المسابقات الفردية', Route('challenges'));
    }

    public function collectiveBreadCrumb(){
        $this->addBreadCrumbLevel('المسابقات الجماعية', Route('collective'));
    }

    public function collective(Request $request){
       
        parent::shareUser();

        $posts = $this->postServices->Comps() ;
   
        return $this->view('frontend.dashboard.challenges-collective', compact('posts'));
    }
    
    public function collectiveExams($parent ,Request $request){
       
        parent::shareUser();

        $data = $this->Stu->getExamsByParents($parent , 'challenge'  , 10) ;
        
        return view('frontend.dashboard.exams.listChallenge' , ['list' => $data ] ) ;
   
        // return view('frontend.dashboard.challenges-collective', compact('challenges'));
    }

    public function old(Request $request){
       
        parent::shareUser();

        $challenges = $this->challengeService->getChallengeList($this->getUser());
   
        return $this->view('frontend.dashboard.challenges-old', compact('challenges'));
    }

    public function createChallenge(Request $request){
   
        try{
            $challenge = $this->challengeService->addChallenge(
                $this->getUser()->id,
                [$request->get('subject')],
                $request->get('skill'),
                $request->get('competitors'),
                $request->get('count'),
                $request->get('time') * 3600
            );
        }catch(Exception $exception){
            $challenge = [] ; 
        }

         

         return response()->json($challenge);
    }


}