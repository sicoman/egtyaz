<?php

namespace App\Repositories;

class UserRepository extends BaseRepository{

    public function model()
    {
       return ('App\\User');
    }

}