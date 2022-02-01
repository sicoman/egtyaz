<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Points extends Model
{

    protected $guarded = ['id'];  

    protected $fillable = ['user_id' , 'type' , 'points' , 'refrence_user' ] ;

    public function User(){
        return $this->belongsTo('App\User' , 'user_id') ;
    }

    public function NewUser(){
        return $this->belongsTo('App\User' , 'refrence_user') ;
    }

}
