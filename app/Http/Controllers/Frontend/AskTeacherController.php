<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Repositories\AskTeacherRepository ;

use App\Repositories\AskAnswersRepository ;

use App\Services\TaxonomyService ;

use App\Traits\SiteMeta;

use App\Models\Options ;

use DB ;

use View ;

class AskTeacherController extends FrontendController
{
    use SiteMeta ;

    protected $askRepo  ;
    protected $options ;
    protected $taxService ;

    public function __construct( AskTeacherRepository $ask , AskAnswersRepository $answers , TaxonomyService $taxService  , Options $options )
    {

      parent::__construct();

        $this->setMeta('title', 'لوحة تحكم - اسأل معلم');

         $this->registerSiteMeta();

        $this->askRepo = $ask ;
        $this->answerRepo = $answers ;
        $this->options = $options ;
        $this->taxService = $taxService;

        $default_settings   = $this->getSetting(['seo' , 'social']) ;

        View::share('options' , $default_settings ) ;

        $this->addBreadCrumbLevel('الرئيسية', Route('cpanel'));


    }

    public function getSetting( $type ){
        if( is_array($type) ){
            $returnOptions = [] ;
            $options = $this->options->whereIn('type' , $type )->select([ 'type' ,'option_value' , 'option_var']) ;
            foreach($options as $option){
                $returnOptions[$option->type][ $option->option_var] =  $option->option_value ; 
            }
            return  $returnOptions ;
        }
        return  $this->options->where('type' , $type )->pluck('option_value' , 'option_var') ;
    }


    public function indexBreadCrumb(){  
        $this->addBreadCrumbLevel(' اسال معلم ', Route('askTeacherList'));
    }

    public function askBreadCrumb(){ 
        $this->indexBreadCrumb() ; 
        $this->addBreadCrumbLevel(' أضف سؤال ', Route('askTeacher'));
    }

    public function showBreadCrumb(){ 
        $this->indexBreadCrumb() ;  
        $this->addBreadCrumbLevel(' عرض سؤال ', Route('askTeacher'));
    }

    public function myBreadCrumb(){ 
        $this->indexBreadCrumb() ;  
        $this->addBreadCrumbLevel(' قائمة أسئلتى ', Route('askTeacherMine'));
    }

    public function index(Request $request) { 

        parent::shareUser();

        $listAsk = $this->askRepo->latest(10 , $request->all() );
        
        $subjects  = $this->taxService->getSubjectsObject()->pluck('name'  , 'id') ;

        $skills    = $this->taxService->getSkillsObject()->pluck('name'  , 'id') ;

        return $this->view('frontend.dashboard.askteacher.list', compact('listAsk' , 'subjects' , 'skills'));
    }

    public function my(Request $request) { 

        parent::shareUser();

        $requestData = $request->all() + ['user_id' => auth()->user()->id ] ;
        
        $listAsk = $this->askRepo->latest(10 , $requestData );
        
        $subjects  = $this->taxService->getSubjectsObject()->pluck('name'  , 'id') ;

        $skills    = $this->taxService->getSkillsObject()->pluck('name'  , 'id') ;

        return $this->view('frontend.dashboard.askteacher.list', compact('listAsk' , 'subjects' , 'skills'));
    }

    public function show($id) { 

        parent::shareUser();

        $showAsk = $this->askRepo->find($id);

        $subjects  = $this->taxService->getSubjectsObject()->pluck('name'  , 'id') ;

        $skills    = $this->taxService->getSkillsObject()->pluck('name'  , 'id') ;

        $answers   = $showAsk->answers()->orderBy('id' , 'DESC')->paginate(5) ;

        return $this->view('frontend.dashboard.askteacher.show', compact('showAsk' , 'subjects' , 'skills' , 'answers') );
    }

    public function ask(){

        parent::shareUser();

        $subjects  = $this->taxService->getSubjectsObject()->pluck('name'  , 'id') ;

        $skills    = $this->taxService->getSkillsObject()->pluck('name'  , 'id') ;

        return $this->view('frontend.dashboard.askteacher.ask', compact('subjects' , 'skills'));
    }

    public function goAsk(Request $request){

        $this->validate( $request , [
            'question'   => 'required'  
        ]);

        $data = $this->askRepo->create([
            'user_id' => auth()->user()->id ,
            'subject_id'    => $request->subject_id ,
            'skill_id'  =>     $request->skill_id ,
            'question'  => $request->question
        ]) ;

        if ( $data ) {
           return redirect()->back()->with('toast-success', 'تم ارسال السؤال بنجاح'  );
        }else{
           return redirect()->back()->with('toast-error', 'حدث خطا ما');
        }
    }


    public function answer($id ,Request $request){

        $this->validate( $request , [
            'answer'   => 'required'  
        ]);

        $data = $request->all() ;

        $data['teacher_id'] = auth()->user()->id ;

        $answer = $this->askRepo->find($id)->answers()->create($data) ;

        if ( $answer ) {
           return redirect()->back()->with('toast-success', 'تم ارسال الجواب بنجاح'  );
        }else{
           return redirect()->back()->with('toast-error', 'حدث خطا ما');
        }
    }




    
}
