<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreviousUserQuestions extends Model{
    protected $table = 'previous_user_questions';
    public $timestamps = false;
    public $guarded = [];

    public function Question()
    {
        return $this->belongsTo('App\Questions', 'question_id');
    }

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
