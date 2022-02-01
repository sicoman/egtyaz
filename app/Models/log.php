<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class log extends Model
{
    public $table = 'logs';

    public $fillable = [
        'user_id',
        'log_type',
        'referer',
        'data',
        'model'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'log_type' => 'string',
        'referer' => 'string',
        'model' => 'string'
    ];

    public function user(){
        return $this->belongsTo( 'App\User' , 'user_id' ) ;
    }

    // logy( Auth::user()->id , 'delete' , '' , 'logs' , [] );

}
