<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examResults extends Model
{
    public $table   = 'exams_results' ;

    public $fillable = ['exam_id' , 'user_id' , 'valid_answers' , 'wrong_answers' , 'percent'] ;

    public $timestamps = false ;

    public function Exam(){
        return $this->belongsTo('App\Models\Exams' , 'exam_id') ;
    }

    public function Students(){
        return $this->belongsTo('App\User' , 'user_id' )  ;
    }

    public function Student(){
        return $this->Students()  ;
    }

}
