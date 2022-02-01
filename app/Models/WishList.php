<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishList extends Basic 
{

    protected $table = 'wishlist';
    public $timestamps = true;
    protected $fillable = array('user_id', 'key_id' , 'type');

    public function User()
    {
        return $this->belongsTo('App\Users');
    }
   
    public function Question()
    {
        return $this->belongsTo('App\Questions', "key_id");
    }

}