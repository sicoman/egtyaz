<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challengers extends Model
{
    public $table = 'challengers' ;

    public $timestamps = false ;

    public $fillable = ['challenge_id' , 'user_id' , 'status' , 'finish_at' ] ;

    public function user(){
        return $this->belongsTo( 'App\User' , 'user_id' ) ;
    }

}
