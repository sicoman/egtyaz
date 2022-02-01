<?php

namespace App\Repositories;

use App\Repositories\UserRepository as User;

use Illuminate\Support\Facades\DB ;

class PointsRepository extends BaseRepository{

    protected $user ;

    public function __construct(User $user)
    {
        $this->user = $user ;
    }

    public function model()
    {
       return ('App\\Points') ;
    }

    protected function data($params , $limit = 0 , $object = false){
        $obj = $this->where($params) ;
        if( $object === true ){
            return $obj  ;
        }
        if($limit > 0){
            return $obj->paginate($limit);
        }
        return $obj->get();
    }

    public function byType(string $type , $limit = 0 , $object = false){
        $params = ['type' => $type];
        return $this->data($params , $limit, $object) ;
    }

    public function ByUser(int $user , $limit = 0, $object = false){
        $params = ['refrence_user' => $user ];
        return $this->data($params , $limit, $object) ;
    }

    public function ByReferrer(int $user , $limit = 0, $object = false){
        $params = ['user_id' => $user ];
        return $this->data($params , $limit, $object) ;
    }

    protected function UpUser($user , $points  , $type = 'deposit' ){
        if(  $type == 'withdraw'){
            return $user->decrement('points', $points);
        }else{
            return $user->increment('points', $points);
        } 
    }

    public function AddPoints($user , $points , $refrence = null){
        $r =  $this->model()::create([
                'type' => 'deposit' ,
                'points' => $points ,
                'refrence_id' => $refrence ,
                'user_id'   => $user
         ]) ;

        if($r){
            $this->UpUser($user , $points , 'deposit') ;
        }
        return $r ;
    }

    public function NewRegisterPoint($user, $refernce){
       $this->AddPoints($user, 50, $refernce);
    }

    public function PackagePoints($packageId){
         return 1000 ;
    }   

    public function removePoints($user , $points ){
        $r =  $this->model()::create([
                'type' => 'withdraw' ,
                'points' => $points ,
                'user_id'   => $user
        ]) ;

        if($r){
            $this->UpUser($user , $points , 'withdraw') ;
        }
        return $r ;
    }

}