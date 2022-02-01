<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepo;
use Illuminate\Container\Container as Application;

 class BaseRepository extends PrettusBaseRepo { 

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function model(){
        return  "";    
    }

    

}




?>