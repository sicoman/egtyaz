<?php

namespace App\Repositories;

use App\Repositories\UserRepository as User;
use Illuminate\Container\Container as Application;


use DB ;

class CommentRepository extends BaseRepository{

    protected $user ;
    protected $answers ;

    public function model()
    {
       return ('App\\Models\\Comments') ;
    }
    
}