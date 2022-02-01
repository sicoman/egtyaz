<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Basic 
{

    protected $table = 'user_account_log';
    public $timestamps = true;
    protected $fillable = array('account_id');

    public function Account()
    {
        return $this->belongsTo('App\Models\Accounts', 'account_id');
    }

}