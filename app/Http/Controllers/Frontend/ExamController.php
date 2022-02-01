<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendController;
use App\Services\ExamsService;
use App\Traits\SiteMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ExamController extends FrontendController
{

    use SiteMeta;

    protected $examsService;

    public function __construct(ExamsService $examsService)
    {    
        parent::__construct();
        $this->setMeta('title', 'اختباراتى');
        $this->registerSiteMeta();
        $this->examsService = $examsService;
       
    }

    public function indexBreadCrumb(){
        $this->addBreadCrumbLevel('اختباراتى', Route('my-exams'));
    }

    public function myExamBreadCrumb(){
        $this->addBreadCrumbLevel('نتائج الاختبار', Route('my-exam', ['id' => Route::current()->parameter('id')]));
    }

    public function index(){
        parent::shareUser();
        $myExams = $this->examsService->getExams($this->getUser()->id);
        return view('frontend.dashboard.exams.my-exams' , compact('myExams'));
    }

    public function myExam ($exam_id){
         
        parent::shareUser();

        $paginate = 1;

        $examContents = $this->examsService->getExamAnswers($this->getUser()->id, $exam_id, $paginate);
     
        return view('frontend.dashboard.exams.my-exam' , ['examDetails' => $examContents['examDetails'], 'exam' => $examContents['examWithResults']]);

    }


}