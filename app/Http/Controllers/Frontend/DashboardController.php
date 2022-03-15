<?php

namespace App\Http\Controllers\Frontend;

use App\Services\TaxonomyService;
use App\Repositories\StudentRepository as Student ;
use App\Services\QuestionsService;
use App\Traits\SiteMeta;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

use App\Models\Options ;
use App\Models\Posts ;
use App\Services\CouponService;
use View ;

use DB;

use App\User ;

use App\Courses ;
use App\Models\Exams ;

class DashboardController extends FrontendController
{
    use SiteMeta;

    protected $taxService;

    protected $Stu ;

    protected $questionService;

    protected $options  ;

    protected $couponService;


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

    public function __construct( TaxonomyService $taxService  , QuestionsService $questionService, Student $student , Options $options, CouponService $couponService )
    {
        parent::__construct();

        $this->setMeta('title', 'لوحة تحكم الطالب');

        $this->registerSiteMeta();

        $this->options = $options ;

        $this->taxService = $taxService;

        $this->couponService = $couponService;

        $this->Stu = $student ;

        $this->questionService = $questionService;
        $default_settings   = $this->getSetting(['seo' , 'social']) ;

        View::share('options' , $default_settings ) ;

        $this->addBreadCrumbLevel('الرئيسية', Route('cpanel'));

    }

    public function indexBreadCrumb(){
        $this->addBreadCrumbLevel('بنك الاسئلة', Route('bank'));
    }

    public function subjectsBreadCrumb(){
        $this->indexBreadCrumb();
        $this->addBreadCrumbLevel('المواد', Route('category_subjects', ['category_id' => Route::current()->parameter('category_id')]));
    }

    public function freeExamBreadCrumb(){
        $this->indexBreadCrumb();
        $this->addBreadCrumbLevel('أختبار تجريبى', Route('exam') );
    }
    public function skillsBreadCrumb(){
        $route = Route::current();
        $this->subjectsBreadCrumb();
        $this->addBreadCrumbLevel('المهارات', Route('subject_skills',
        ['category_id' => $route->parameter('category_id'),
        'subject_id' => $route->parameter('subject_id')])
    );
    }

    public function skillQuestionsBreadCrumb(){
        $route = Route::current();
        $this->skillsBreadCrumb();
        $this->addBreadCrumbLevel('{$skill_name}', Route('subject_skills',
        ['category_id' => $route->parameter('category_id'),
        'subject_id' => $route->parameter('subject_id')])
    );
    }

    public function startExamBreadCrumb(){
        $this->addBreadCrumbLevel('بدء الأختبار', '' ) ;
    }

    public function ExamResultBreadCrumb(){
        $this->addBreadCrumbLevel('نتيجة الأختبار', '' ) ;
    }

    public function examBreadCrumb(){
        $this->addBreadCrumbLevel('أختبار', Route('exam') );
    }

    public function wishlistBreadCrumb(){
        $this->indexBreadCrumb();
        $this->addBreadCrumbLevel('الاسئلة المحفوظة', '' ) ;
    }

    public function questionBreadCrumb(){
        $this->indexBreadCrumb();
        $this->addBreadCrumbLevel('عرض السؤال', '' ) ;
    }

    public function index()
    {
        parent::shareUser();

        $bankCategrories = $this->taxService->getQuestionsCategories('category');

        return $this->view('frontend.dashboard.index', compact('bankCategrories'));
    }

    public function subjects($category_id , Request $request )
    {

        if( isset( $request->skills ) && !empty($request->skills) ){
            $subject = $this->taxService->getById( $request->subject ) ;
            return $this->getSkillsQuestions( $request , $subject ) ;

         }
        $subjects = $this->taxService->getSubjects($category_id);

        return $this->view('frontend.dashboard.bank.subjects', compact('subjects'));
    }

    public function skillQuestions($category_id, $subject_id, $skill_id){

        $questions = $this->questionService->getBySkill($skill_id);

        $skill = $this->taxService->getById($skill_id);

        $skill_name = $skill->name;

        return $this->view('frontend.dashboard.skill-questions', compact('questions', 'category_id', 'subject_id','skill_name'));
    }

    public function skills($category_id, $subject_id)
    {
        parent::shareUser();

        $skills = $this->taxService->getSkills($subject_id);

        return $this->view('frontend.dashboard.skills', compact('skills', 'category_id', 'subject_id'));
    }

    public function getSkillsQuestions(Request $request , $subjects  ){
            //بنك الأسئلة
        $user_id = $this->getUser()->id;
        $questionService = $this->questionService;
        $questions = $this->questionService->getBySkillsNew( array_keys( $request->skills ) , $request->count ?? 10, $user_id, $request ) ;

        $questions->each(function($question) use($questionService, $user_id){
            $questionService->addtoPreviousQuestions($question['id'], $user_id);
        });

        return $this->view('frontend.dashboard.bank.study', compact('questions' , 'request' , 'subjects' )) ;
    }

    public function bank()
    {
        return $this->view('frontend.dashboard.index');
    }

    public function freeExam()
    {

        parent::shareUser();

        $subjects = $this->Stu->exam() ;

        return $this->view('frontend.dashboard.exams.free' ,['subjects' => $subjects] ) ;
    }

    public function makeExam(Request $request){
        return $this->Stu->makeExam( $request->all() ) ;
    }

    public function saveExam($id , Request $request){

        $answers = $request->all() ;

        $listAnswers = [] ;

        foreach( $answers['answers'] as $answer ){
                $listAnswers[$answer['id']] = $answer ;
        }

        $answers['answers'] = $listAnswers ;

        return $this->Stu->saveExam($id , $answers ) ;

    }

    public function startExam($id , Request $requets){

        parent::shareUser();
        $data = $this->Stu->startExam($id) ;

        if( !isset($data['exam']) ){
            return redirect( route('myExams') ) ;
        }else{
            $results = $data['exam']->results()->where('user_id' , auth()->user()->id )->first() ;
            if( isset($results->id) ) {
                // return redirect( route('ExamResult' , $id ) ) ;
            }
        }

        return $this->view('frontend.dashboard.exams.exam' , $data ) ;
    }

    public function ExamResult($id){
        parent::shareUser();
        $data = $this->Stu->getExamResult($id , 10) ;
        $shareThis = $data['exam']->ShareUri ;
        View::share('shareThis' , $shareThis.'-x'.auth()->user()->id ) ;
        return $this->view('frontend.dashboard.exams.result' , $data ) ;
    }

    public function examResultShareBreadCrumb(){
        $this->addBreadCrumbLevel('نتيجة الأختبار', '' ) ;
    }

    public function examResultShare($Fcode) {
        $code = explode('-x' , $Fcode) ;
        $getExamId = Exams::where( DB::raw( 'MD5( concat( id , ":" , created_at ) )') , $code[0] )->first();

        if( !isset($getExamId->id) || !isset($code[1]) ){
            return redirect('/') ;
        }

        $data = $this->Stu->getExamResult($getExamId->id , 10 , $code[1] ) ;

        if( !isset($data['results']) ){
            return redirect('/') ;
        }

        $User = User::find( $code[1] ) ;


        if( !isset($getExamId->id) || !isset($code[1]) ){
            return redirect('/') ;
        }


        $shareThis = $data['exam']->ShareUri ;
        View::share('ShareUserName' ,  $User->name ?? '' );
        return $this->view('frontend.dashboard.exams.result_share' , $data ) ;
    }

    public function ExamsBreadCrumb(){
        $this->addBreadCrumbLevel('انجازاتي', Route('exams') );
    }

    public function Exams($type = 'free'){

        parent::shareUser();
        $data = $this->Stu->getExamsByType($type , $this->getUser()->id) ;

        return $this->view('frontend.dashboard.exams.list' , ['list' => $data ] ) ;
    }

    public function MocksBreadCrumb(){
        $this->addBreadCrumbLevel('اختبارات القدرات', Route('mocks') );
    }
    public function Mocks($type = 'mock'){
        parent::shareUser();
        $data = $this->Stu->getExamsByType($type , $this->getUser()->id) ;
        return $this->view('frontend.dashboard.exams.list' , ['list' => $data ] ) ;
    }



    public function wishlist(Request $requets){
        parent::shareUser();
    }

    public function question($id , Request $requets){
        parent::shareUser();
        $data = $this->Stu->getQuestion($id) ;
        return $this->view('frontend.dashboard.exams.question' , $data ) ;
    }


    public function previewCoupon($code){
        $coupon = $this->couponService->isCoupon($code);
        return response()->json(['coupon' => $coupon]);
    }



    public function rateBreadCrumb(){
        $this->addBreadCrumbLevel('عرض التقييم', '' ) ;
    }

    public function rate(){
        parent::shareUser();
        $data = $this->Stu->getRate() ;
        return $this->view('frontend.dashboard.rate' , $data ) ;
    }

    public function cpanelBreadCrumb(){

    }

    public function cpanel(){

        parent::shareUser();

        $data = $this->Stu->getRate() ;

        $referalUrl = route("register")."?referer={$this->getUser()->code}";

        $frontRepo = resolve("App\Repositories\FrontEndRepository");

        $data['referalUrl'] = $referalUrl ;

        $data['dashBlocks'] = $frontRepo->dashcpanel()['dash_blocks'];

        return $this->view('frontend.dashboard.cpanel' , $data ) ;
    }


    /*
        Start Learn
    */

    public function startBreadCrumb(){
        $this->addBreadCrumbLevel('التأسيس', Route('start'));
    }

    public function startSubjectsBreadCrumb(){
        $this->startBreadCrumb();
        $this->addBreadCrumbLevel('المواد', Route('start_category_subjects', ['category_id' => Route::current()->parameter('category_id')]));
    }

    public function startSkillsBreadCrumb(){
        $route = Route::current();
        $this->startSubjectsBreadCrumb();
        $this->addBreadCrumbLevel('المهارات', Route('start_skills',
        ['category_id' => $route->parameter('category_id'),
        'subject_id' => $route->parameter('subject_id')])
    );
    }

    public function startSkillQuestionsBreadCrumb(){
        $route = Route::current();
        $this->startSkillsBreadCrumb();
        $this->addBreadCrumbLevel('{$skill_name}', Route('start_skills',
        ['category_id' => $route->parameter('category_id'),
        'subject_id' => $route->parameter('subject_id')])
    );
    }

    public function start()
    {
        parent::shareUser();

        $bankCategrories = $this->taxService->getQuestionsCategories('category');

        return $this->view('frontend.dashboard.start.index', compact('bankCategrories'));
    }

    public function startSubjects($category_id , Request $request )
    {
        if( isset( $request->skills ) && !empty($request->skills) ){
            $subject = $this->taxService->getById( $request->subject ) ;
            return $this->getSkillsQuestions( $request , $subject ) ;
         }
        $subjects = $this->taxService->getSubjects($category_id);

        return $this->view('frontend.dashboard.start.subjects', compact('subjects'));
    }

    public function startSkills($category_id, $subject_id)
    {
        parent::shareUser();

        $skills = $this->taxService->getSkills($subject_id);

        return $this->view('frontend.dashboard.start.skills', compact('skills', 'category_id', 'subject_id'));
    }

    public function startSkillQuestions($category_id, $subject_id, $skill_id){

        $questions  = Posts::where('status' , 1)->where('type' , 'learn')->where('taxonomy_id' , $skill_id)->paginate(20) ;

        $skill      = $this->taxService->getById($skill_id);

        $skill_name = $skill->name;

        return $this->view('frontend.dashboard.start.skill-questions', compact('questions', 'category_id', 'subject_id','skill_name'));
    }


    public function coursesBreadCrumb(){

        $this->addBreadCrumbLevel('الدورات المتقدمة', Route('courses') );
    }
    public function courses($category = 0 , Request $request){
        $list = Courses::where('status' , 1) ;
        if($category > 0){
            $list->where('taxonomy_id' , $category) ;
        }
        $list = $list->orderBy('id' , 'DESC')->paginate(10) ;

        return $this->view('frontend.dashboard.courses.list', compact('list'));
    }

    public function getCourseBreadCrumb(){
        $this->coursesBreadCrumb();
        $route = Route::current() ;
        $this->addBreadCrumbLevel( $route->parameter('title') , '#' );
    }
    public function getCourse($id = 0 , $title = '' , Request $request){
        $course = Courses::where('status' , 1)->where('id' ,$id)->with( ['items'])->first() ;
        if(!$course->id){
            return redirect()->back() ;
        }
        return $this->view('frontend.dashboard.courses.course', compact('course'));
    }

    public function joinCourseBreadCrumb(){
        $this->coursesBreadCrumb();
        $route = Route::current() ;
        $this->addBreadCrumbLevel( $route->parameter('title') , '#' );
    }
    public function joinCourse($id = 0 , $title = '' , Request $request){
        $course = Courses::where('status' , 1)->where('id' ,$id)->with( ['items'])->first() ;
        if(!$course->id){
            return redirect()->back() ;
        }
        return $this->view('frontend.dashboard.courses.join', compact('course'));
    }

    public function examsSubjects($id){
        parent::shareUser();
        $data = $this->Stu->getExamsBySubjects($id , $this->getUser()->id) ;
        return $this->view('frontend.dashboard.exams.list' , ['list' => $data ] ) ; 
    }

    public function examsSubjects($id){
        parent::shareUser();
        $data = $this->Stu->getExamsBySubjects($id , $this->getUser()->id) ;
        return $this->view('frontend.dashboard.exams.list' , ['list' => $data ] ) ; 
    }


}
