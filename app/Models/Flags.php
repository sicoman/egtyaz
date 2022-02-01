<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flags extends Basic 
{

    protected $table = 'flags';

    public $timestamps = true;

    protected $fillable = array('type', 'description', 'flagged_id' , 'flagged_by' , 'status');


    public function by(){
        return $this->belongsTo('App\User' , 'flagged_by') ;
    }

    public function user(){
        return $this->belongsTo('App\User' , 'flagged_id') ;
    }

    public function unit(){
        return $this->belongsTo('App\Models\Units' , 'flagged_id') ;
    }

    public function review(){
        return $this->belongsTo('App\Models\Reviews' , 'flagged_id') ;
    }

    public function message(){
        return $this->belongsTo('App\Models\Messages' , 'flagged_id') ;
    }

    public function booking(){
        return $this->belongsTo('App\Models\Booking' , 'flagged_id') ;
    }




}