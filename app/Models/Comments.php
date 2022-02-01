<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Posts ;

class Comments extends Model
{
    protected $table = 'comments';
    
    public    $timestamps = true;

    protected $fillable = array(
        'post_id',
        'user_id',
        'comment',
        'ip',
        'status'
    );

    public function post(){
        return $this->belongsTo('App\Models\Posts' , 'post_id') ;
    }

    public function author(){
        return $this->belongsTo('App\User' , 'user_id') ;
    }

    protected static function boot() {
        parent::boot();
        static::updated(function($model) {
            $active_comments = Comments::where('post_id' , $model->post_id)->where('status' , 1)->count();
            Posts::where('id' , $model->post_id)->update(['comments' , $active_comments])->save() ;
        });
    }

   

}
