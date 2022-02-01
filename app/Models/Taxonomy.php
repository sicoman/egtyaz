<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Basic 
{

    protected $table = 'taxonomies';
    public $timestamps = true;
    protected $fillable = array('type', 'name', 'description', 'name_en', 'description_en', 'photo', 'parent', 'status');

    public function parent()
    {
        return $this->belongsTo('App\Models\Taxonomy');
    }

    public function father()
    {
        return $this->belongsTo('App\Models\Taxonomy' , 'parent');
    }

    public function childrens(){
        return $this->HasMany('App\Models\Taxonomy' , 'parent')->where('status' , 1);
    }

    public function Posts()
    {
        return $this->hasMany('App\Models\Posts', 'taxonomy_id');
    }

}