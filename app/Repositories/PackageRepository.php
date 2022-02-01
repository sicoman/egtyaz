<?php

namespace App\Repositories;


class PackageRepository extends BaseRepository{

    public function model()
    {
       return ('App\\Packages');
    }

}