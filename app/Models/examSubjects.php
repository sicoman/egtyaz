<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examSubjects extends Model
{
    public $table   = 'exams_subjects' ;

    public $timestamps = false;

    public $guraded = ['id'] ;

    public $fillable = ['exam_id' , 'subject_id'] ;

    public function Exam(){
        return $this->belongsTo('App\Models\Exams' , 'exam_id') ;
    }

    public function Subject(){
        return $this->belongsTo('App\Models\Taxonomy' , 'subject_id') ;
    }
    
}
