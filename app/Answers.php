<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    protected $guarded = ['id'] ;

    public $timestamps = false ;

    protected function Question(){
        return $this->belongsTo('App\Questions') ;
    }

}
