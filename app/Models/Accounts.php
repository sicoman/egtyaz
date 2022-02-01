<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Basic 
{

    protected $table = 'user_accounts';
    public $timestamps = false;
    protected $fillable = array('user_id', 'provider', 'provider_id');

    public function User()
    {
        return $this->belongsTo('App\Users', 'user_id');
    }

    public function Alog()
    {
        return $this->hasMany('App\Models\UserLogin', 'account_id');
    }

}