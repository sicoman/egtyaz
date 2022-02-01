<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examAnswers extends Model
{
    public $table   = 'exams_answers' ;

    public $fillable = ['exam_id' , 'question_id' , 'student_id' , 'answer_id' , 'is_true' , 'spent_time'] ;

    public $timestamps = false ;

    public function Exam(){
        return $this->belongsTo('App\Models\Exams' , 'exam_id') ;
    }

    public function Students(){
        return $this->belongsTo('App\User' , 'user_id' )  ;
    }

    public function Question(){
        return $this->belongsTo('App\Questions' , 'question_id' )  ;
    }

    public function Answer(){
        return $this->belongsTo('App\Answers' , 'answer_id' )  ;
    }

    public function Results(){
        return $this->hasMany('App\Models\examResults' , 'exam_id','exam_id') ;
    }
}
