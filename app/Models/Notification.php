<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = 'notifications';

    public function getDataAttribute($data){    
        return json_decode($data, true);
    }

    public function user(){
        return $this->belongsTo( 'App\User' , 'user_id' ) ;
    }

    public function markAsRead(){
        $this->read_at = now();
        $this->update();
    }


}
