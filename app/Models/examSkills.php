<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examSkills extends Model
{
    public $table   = 'exams_skills' ;

    public $timestamps = false;

    public $guraded = ['id'] ;

    public $fillable = ['exam_id' , 'skill_id'] ;

    public function Exam(){
        return $this->belongsTo('App\Models\Exams' , 'exam_id') ;
    }

    public function Skill(){
        return $this->belongsTo('App\Models\Taxonomy' , 'skill_id') ;
    }
    
}
