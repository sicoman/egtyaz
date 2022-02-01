<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $guarded = ['id'] ;

    public $fillable = ['category_id' , 'subject_id' , 'skill_id' , 'level_id' , 'status' , 'title' , 'description' , 'attachment_id' ] ;

    public function Answers(){
        return $this->hasMany('App\Answers' , 'question_id') ;
    }

    public function Correct(){
        return $this->hasOne('App\Answers' , 'question_id')->where('is_true' , 1) ;
    }

    public function True(){
        return $this->Correct()  ;
    }

    public function Category(){
        return $this->belongsTo('App\Models\Taxonomy' , 'category_id') ;
    }

    public function Subject(){
        return $this->belongsTo('App\Models\Taxonomy' , 'subject_id') ;
    }

    public function Skill(){
        return $this->belongsTo('App\Models\Taxonomy' , 'skill_id') ;
    }

    public function Level(){
        return $this->belongsTo('App\Models\Taxonomy' , 'level_id') ;
    }

    public function Attachment(){
        return $this->belongsTo('App\Models\Posts' , 'attachment_id') ;
    }

    public function WishList(){
        return $this->hasOne('App\Models\WishList' , 'key_id') ;
    }

    public function inWishList(){
        return $this->hasOne('App\Models\WishList' , 'key_id')->where('user_id' , auth()->user()->id ) ;
    }

    public function itrue(){
        return $this->hasOne('App\Answers' , 'question_id')->where('is_true' , 1) ;
    }
    
}
