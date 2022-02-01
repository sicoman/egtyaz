<?php

namespace App\Repositories;

use App\Repositories\UserRepository ;
use App\Repositories\AskAnswersRepository ;
use Illuminate\Container\Container as Application;


use DB ;

class AskTeacherRepository extends BaseRepository{

    protected $user ;
    protected $answers ;

    public function __construct(Application $app , UserRepository $user , AskAnswersRepository $answers)
    {
        parent::__construct($app); 
        $this->user = $user ;
        $this->answers = $answers ;
    }

    public function model(){
        return ('App\\AskTeacher');  
    }

    protected function data($params , $limit = 0 , $orderBY = 'id' , $order = 'desc' , $object = false){

        $obj = $this ;

        if( empty($params) ){
            $obj->where([1 , 1]) ;
        }else{
            $obj->where($params) ;
        }

       
        if( isset($orderBY[1]) ){
            $obj->orderBy($orderBY , $order) ;
        }

        

        if( $object === true ){
            return $obj ;
        }elseif( is_array($object) && !empty($object) ){

            if( isset($object['subject_id']) && $object['subject_id'] > 0 ) {
                $obj->where('subject_id' , '='  , $object['subject_id'] ) ;
            }

            if( isset($object['skill_id']) && $object['skill_id'] > 0 ) {
                $obj->where('skill_id' , '='  , $object['skill_id']) ;
            }

            if( isset($object['user_id']) && $object['user_id'] > 0 ) {
                $obj->where('user_id' , '=' , $object['user_id']) ;
            }

        }

        if($limit > 0){
            return $obj->paginate($limit);
        }

        return $obj->get();

    }

    public function By( $column = 'user_id' , int $value , $limit = 0, $object = false){
        $params = [ $column  =>  $value ];
        return $this->data($params , $limit , '' , '' , $object) ;
    }

    public function ByUser(int $user , $limit = 0, $object = false){
        return $this->By('user_id' , $user , $limit , $object) ;
    }

    public function BySubject(int $subject , $limit = 0, $object = false){
        return $this->By('subject_id' , $subject , $limit , $object) ;
    }

    public function BySkill(int $skill , $limit = 0, $object = false){
        return $this->By('skill_id' , $skill , $limit , $object) ;
    }

    public function Latest($limit = 0 , $object = false){
        return $this->data( [] , $limit , 'id' , 'DESC' , $object) ;
    }

    public function New($limit , $object = false){
        return $this->Latest($limit , $objec ) ;
    }

    public function Answer($ask , $answer , $teacher){
        return $this->answers->create([
            'ask_id' => $ask ,
            'answer' => $answer ,
            'teacher_id' => $teacher 
        ]) ;
    }

    public function Answers($ask , $limit = 0){
        if( $limit == 0 ){
            return $this->answers->where('ask_id' , $ask)->get() ;
        }else{
            return $this->answers->where('ask_id' , $ask)->paginate($limit) ;
        }
    }

    public function TeacherAnswers($teacher , $limit = 0){
        if( $limit == 0 ){
            return $this->answers->where('teacher_id' , $teacher)->get() ;
        }else{
            return $this->answers->where('teacher_id' , $teacher )->paginate($limit) ;
        }
    }
    
}