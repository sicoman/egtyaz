<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait FrontendAuth{
    public function getUser(){
        return $this->guard()->user();
    }

    public function checkIsUser(){
        return $this->guard()->check();
    }

    private function guard(){
        return Auth::guard('web');
    }

}