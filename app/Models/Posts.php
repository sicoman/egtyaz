<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Basic 
{

    protected $table = 'posts';
    public $timestamps = true;
    protected $fillable = array('type', 'user_id', 'title', 'description', 'photo', 'file' , 'taxonomy_id', 'status', 'views' , 'parent' , 'start' , 'end');

    public function Author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Category()
    {
        return $this->belongsTo('App\Models\Taxonomy', 'taxonomy_id');
    }

    public function GetThumbsAttribute(){
        return str_replace( 'category/' , 'category/thumbs/' , $this->photo ) ;
    }

    public function Comments(){
        return $this->hasMany('App\Models\Comments' , 'post_id') ;
    }

    public function Childrens()
    {
        return $this->HasMany('App\Models\Posts' , 'parent');
    }

    public function Parent()
    {
        return $this->belongsTo('App\Models\Posts', 'id' , 'parent');
    }

}