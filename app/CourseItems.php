<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseItems extends Model
{

    protected $table = 'course_items';

    public $timestamps = true;

    protected $fillable = array( 'course_id' ,	'title' ,	'file' ,	'time' ,	'is_free' );

    public function Course()
    {
        return $this->belongsTo('App\Courses', 'course_id');
    }

}