<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomMessage extends Model
{
    protected $guarded = ['id'] ;  

    public function users(){
         return $this->hasMany( CustomMessageUsers::class , 'user_id' ) ;
    }
}
