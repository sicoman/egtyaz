<?php

namespace App\Services;

use App\Repositories\PointsRepository;

class PointsService extends BaseService{

    protected $userService ;

    public function __construct(PointsRepository $repo)
    { 
        parent::__construct($repo) ;
    }

    public function User($id , $limit = 0 , $object = false){
        return $this->repo->byUser($id , $limit, $object);
    }

    public function Referrer($id , $limit = 0, $object = false){
        return $this->repo->ByReferrer($id , $limit, $object);
    }

    public function Type($type = 'deposit' , $limit = 0, $object = false){
        return $this->repo->byType($type , $limit , $object) ;
    }

    public function NewRegisterPoint(UserService $service, $code, $referenceUser){
        $user = $service->find(['code' => $code]);  
        if($user){
            return $this->repo->NewRegisterPoint($user, $referenceUser) ;
        }
    }

    public function subtract($user, $points){
        return $this->repo->removePoints($user, $points);
    }
    
    public function PackagePoints($packageId){
        return $this->repo->PackagePoints($packageId) ;
    }


}



?>