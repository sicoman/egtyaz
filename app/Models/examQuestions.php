<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examQuestions extends Model
{
    public $table   = 'exams_questions' ;

    public $timestamps = false;

    public $guraded = ['id'] ;

    public $fillable = ['exam_id' , 'question_id'] ;

    public function Exam(){
        return $this->belongsTo('App\Models\Exams' , 'exam_id') ;
    }

    public function Question(){
        return $this->belongsTo('App\Questions' , 'question_id') ;
    }


}
