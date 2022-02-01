<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService{

    protected $pointService;

    public function __construct(UserRepository $repo)
    {    
        parent::__construct($repo); 
    }


    public function generateCode(){

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $code = "";

        for ($i = 0; $i < 12; $i++) {
            $code .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        if($this->repo->where('code', $code)->first()){
            return $this->generateCode();
        }

        return $code;
    }


    public function add($attributes){
        $attributes['code'] = $this->generateCode();
        $user = parent::add($attributes);
        return $user;
    }

    public function update($id, $attributes)
    {
        if(isset($attributes['password'])){  
            $attributes['password'] = Hash::make($attributes['password']);
        } 

        if(!isset($attributes['status'])){
            $attributes['status'] = 1;
        }

        return parent::update($id, $attributes);
    }

    public function searchUsers(string $query = ""){

        $repo = $this->repo->orderBy('id');
        
        if($query){
            $user = $repo->orWhere('name', 'LIKE', "%{$query}%")->orWhere('mobile','LIKE', "%{$query}%");
        }

        return $user->get();
    }


}



?>