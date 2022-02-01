<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AskTeacher extends Model
{

    protected $guarded = ['id'];  

    protected $fillable = ['user_id','question','subject_id','skill_id' ] ;

    public function User(){
        return $this->belongsTo('App\User' , 'user_id') ;
    }

    public function Subject(){
        return $this->belongsTo('App\Models\Taxonomy' , 'subject_id') ;
    }

    public function Skill(){
        return $this->belongsTo('App\Models\Taxonomy' , 'skill_id') ;
    }

    public function Answers(){
        return $this->hasMany('App\askAnswers' , 'ask_id') ;
    }

}
