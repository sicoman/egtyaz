<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenges extends Model
{
    public $table = 'challenges' ;

    public $fillable = ['exam_id' , 'finish_at', 'user_id' ] ;

    public function Challengers(){
        return $this->hasMany( 'App\Challengers' , 'challenge_id' ) ;
    }

    public function User(){
        return $this->belongsTo('App\User' , 'user_id') ;
    }

    public function Exam(){
        return $this->belongsTo('App\Models\Exams' , 'exam_id') ;
    }

}
