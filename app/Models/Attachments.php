<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Basic 
{

    protected $table = 'unit_images';
    public $timestamps = true;
    protected $fillable = array('unit_id', 'image', 'title', 'ordr');

    public function Units()
    {
        return $this->belongsTo('App\Models\Units');
    }

}