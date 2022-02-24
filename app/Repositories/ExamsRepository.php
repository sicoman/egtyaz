<?php

namespace App\Repositories;

use Illuminate\Container\Container as Application;

use App\Questions ;
use App\models\examSubjects ;
use App\Models\examSkills ;
use App\Models\Exams ;

use View ;

use DB ;


class ExamsRepository extends BaseRepository{

    protected $exams ;
    protected $questions ;
    protected $exSkills ;
    protected $exSubjects ;

    public function __construct(Application $app  , Questions $questions  ) // , examSubjects $exSubjects , examSkills $exSkills
    {
        parent::__construct($app);

        $this->questions    = $questions ;

       // $this->exSkills     = $exSkills ;

       // $this->exSubjects   = $exSubjects ;

    }

    public function model()
    {
       return ('App\\Models\\Exams');
    }

    public function CreateExam( $type = 'free' , $user , $subjects = [] , $skills = [] , $time = 10 , $count = 30 , $info = [] , $level = null ) {

        $exam = $this->model->fill([
           'type' => $type ?? 'free' ,
           'student_id' => $user ,
           'questions_count' => $count ?? 10 ,
           'available_time' => $time ?? 30 ,
           'level_id'  => null
        ]) ;

        $exam->save() ;

        $id = $exam->id ;

        //$skills = [] ;
        // Lets Attach Exam Questions - Subjects
        foreach( $subjects as $subject => $kill ){
            if( $subject == 0 ){ continue ; }
            $exam->subjects()->create([
                'subject_id' => $subject
            ]) ;
            $kills[] = $kill ;
        }

        foreach( $skills as $skill ){
            if( $skill == 0 ){ continue ; }
            $exam->skills()->create([
                'skill_id' => $skill
            ]) ;
        }



        // return $this->getExamQuestions( $exam , $skills , $subjects ) ;



        return $id ;

    }

    public function getExamQuestionsObject( $exam , $skills = [] , $subjects = [] , $count = 0 , $type  = 'random' , $old = false , $repeat = true  ){


            if( $exam->type == 'mock' || $exam->type == "challenge" ){
                // Select This exam Questions
                if( $exam->questions()->distinct('question_id')->count() >= $exam->questions_count || 1 == 1 ) {
                    $questions_ids = $exam->questions()->distinct('question_id')->pluck('question_id') ;
                    $questions = $this->questions->whereIn('id' , $questions_ids );

                    if( isset($skills) && !empty($skills) ) {
                       // $questions->whereIn('skill_id' , $skills ) ;
                    }
                    if( isset($subjects ) && !empty($subjects ) ) {
                       // $questions->whereIn('subject_id' , $subjects  ) ;
                    }

                    return $questions->with('itrue')->orderBy('subject_id' , 'desc')->orderBy('attachment_id' , 'DESC')->limit($count) ;

                }
            }


        if( empty($skills) ){
            $skills[] = $exam->skills()->get()->pluck('skill_id')->toArray() ;
        }

        $oldExams = DB::table('exams_answers')->where('student_id' , auth()->user()->id )->pluck('question_id') ;

        if( isset($skills) && !empty($skills) ) {
            $all_ids = [] ; $perSkill = floor( $count / count($skills) ) ; $moreThan = 0 ;
            if( $count <> ( $perSkill * count($skills) ) ){ $moreThan = $count - ($perSkill * count($skills)) ; }

            $skills = array_filter($skills, function($item){
                return !empty($item);
            });

            foreach( $skills as $kx => $skill ){

                $questions = $this->questions->where('status' , 1)->where('skill_id' , $skill) ;

                if( isset($exam->level_id) && $exam->level_id > 0 ) {
                    $questions->where('level_id' , $exam->level_id) ;
                }

                if( $type == 'random' ) {
                    $questions->inRandomOrder() ;
                }

                if( $old != false ) {
                    $questions->whereNotin('id' , $oldExams) ;
                }

                if( $repeat ) {
                    if ($oldExams){
                        $questions->whereIn('id' , $oldExams) ;
                    }

                }

                if( $skill == end($skills) ){
                    $questions->limit( $perSkill + $moreThan ) ;
                }else{
                    $questions->limit( $perSkill ) ;
                }

                $skill_ids = $questions->pluck('id')->toArray() ;

                foreach($skill_ids as $skid){
                    $all_ids [] = $skid ;
                }

                unset($questions) ;

            }


        }

        return $this->questions->whereIn('id' , $all_ids)->limit( $count )->orderBy('skill_id')->orderBy('attachment_id' , 'DESC')->with('itrue') ; //->distinct();

    }

    public function getExamQuestions( $exam , $skills = [] , $subjects = [] , $count = 0 , $type  = 'random' , $old = false , $repeat = false  ){

        return $this->getExamQuestionsObject( $exam , $skills , $subjects , $count , $type , $old , $repeat )->pluck('id')  ;

    }

    protected function createExamQuestions( $exam , $subjects ){

        $questionsList = $this->getExamQuestions($exam , $subjects ) ;

        if( !empty($questionsList) ){
              foreach( $questionsList as $question ){
                  if( $question == 0 ){ continue ; }
                  $exam->questions()->create([
                      'question_id' => $question
                  ]) ;
              }
        }

        return $exam ;

    }




}
