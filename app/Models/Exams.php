<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB ;

class Exams extends Model
{
    public $guraded = ['id'] ;

    public $fillable = ['title' ,'type' ,'student_id' , 'level_id' , 'questions_count' , 'available_time' , 'parent'] ;

    public $timestamps = true ;

    public function questions(){
        return $this->hasMany('App\Models\examQuestions' , 'exam_id') ;
    }

    public function answers(){
        return $this->hasMany('App\Models\examAnswers' , 'exam_id') ;
    }

    public function level(){
        return $this->belongsTo('App\Models\Taxonomy' , 'level_id') ;
    }

    public function Subjects(){
        return $this->hasMany('App\Models\examSubjects' , 'exam_id') ;
    }

    public function Skills(){
        return $this->hasMany('App\Models\examSkills' , 'exam_id') ;
    }

    public function Results(){
        return $this->hasMany('App\Models\examResults' , 'exam_id') ;
    }

    public function MyResults(){
        return $this->hasMany('App\Models\examResults' , 'exam_id')->where('user_id' , auth()->user()->id ?? 0) ;
    }

    public function Student(){
        return $this->belongsTo('App\User' , 'student_id') ;
    }

    public function User(){
        return $this->Student() ;
    }

    public function Challenge(){
        return $this->hasOne('App\Challenges' , 'exam_id') ;
    }

    public function getTimeAttribute($value)
    { 
        return $value / 60;
    }

    public function getShareCodeAttribute($value)
    { 
        return md5( $this->id.':'.$this->created_at ) ;
    }

    public function getShareUriAttribute($value)
    { 
        return route('exam.share' , $this->ShareCode ) ;
    }

    



    protected static function boot() {

        parent::boot();

        static::created(function($model) {
             // If This exam is Challenge Lets Send Invitation to All Users
             
        });

        static::updated(function($model) {
            
        });

   }


   public static function Clean($exam = 1){
        // Delete Non Existed Questions
        DB::statement('DELETE FROM exams_questions  WHERE question_id NOT IN 
        (
          SELECT q.id FROM questions  as q
        )') ;

        /*
            Get list of Multi User Questions
        */    
        $list = DB::select('
            select id , exam_id , question_id , count(question_id) as c from exams_questions as t2  group by exam_id , question_id HAVING(c)  > 1
        ') ;

        // Lets Delete From All Loop Minus 1
        foreach($list as $exam) {
            $query = 'DELETE FROM exams_questions  WHERE exam_id = "'.$exam->exam_id.'" and question_id = "'.$exam->question_id.'" LIMIT '.($exam->c - 1) ;
            DB::statement($query) ;
        }

        return $list ;

   }


    

}
