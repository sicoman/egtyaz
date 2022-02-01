<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Basic 
{

    protected $table = 'badges';
    public $timestamps = true;
    protected $fillable = array('type' ,'user_id', 'badge');

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Badge()
    {
        return $this->belongsTo('App\Models\Taxonomy', 'badge');
    }

}