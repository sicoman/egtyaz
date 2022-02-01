<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class askAnswers extends Model
{

    protected $guarded = ['id'];  

    protected $fillable = ['ask_id','answer','teacher_id'] ;

    public function User(){
        return $this->belongsTo('App\User' , 'teacher_id') ;
    }

    public function Teacher(){
        return $this->User() ;
    }

    public function Ask(){
        return $this->belongsTo('App\AskTeacher' , 'ask_id') ;
    }

}
