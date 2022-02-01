<?php

namespace App\Repositories;

use App\Repositories\UserRepository as User;
use Illuminate\Container\Container as Application;


use DB ;

class AskAnswersRepository extends BaseRepository{

    protected $user ;
    protected $answers ;

    public function __construct(Application $app , User $user )
    {
        parent::__construct($app); 
        $this->user    = $user ;
        // $this->answers = $answers ;
    }

    public function model()
    {
       return ('App\\askAnswers') ;
    }

    /*

    protected function data($params , $limit = 0 , $orderBY = 'id' , $order = 'DESC' , $object = false){

        $obj = $this->where($params) ;

        if( isset($orderBY) ){
            $obj->orderBy($orderBY , $order) ;
        }

        if( $object === true ){
            return $obj ;
        }

        if($limit > 0){
            return $obj->paginate($limit);
        }
        return $obj->get();
    }

    public function By( $column = 'user_id' , int $value , $limit = 0, $object = false){
        $params = [ $column  =>  $value ];
        return $this->data($params , $limit, $object) ;
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
        return $this->data( [ 1 => 1 ] , $limit , 'id' , 'DESC' , $object) ;
    }

    public function New($limit){
        return $this->Latest($limit , false) ;
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

    */
    
}