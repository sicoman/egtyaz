<?php

namespace App\Repositories;


class PaymentRepository extends BaseRepository{

    public function model()
    {
       return ('App\\Models\\Payments');
    }

}