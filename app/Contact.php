<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact' ;

    public $fillable = ['type' , 'name' , 'email' , 'message' , 'mobile' , 'status' , 'ip'] ;

    
}
