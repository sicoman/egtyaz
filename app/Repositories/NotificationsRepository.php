<?php

namespace App\Repositories;


class NotificationsRepository extends BaseRepository{

    public function model()
    {
       return ('App\\Models\\Notification');
    }

}