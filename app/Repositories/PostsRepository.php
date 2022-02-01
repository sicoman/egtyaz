<?php

namespace App\Repositories;


class PostsRepository extends BaseRepository{

    public function model()
    {
       return ('App\\Models\\Posts');
    }

    public function byType(string $type, $conditions = [], $paginate = false){
        
        $params = ['type' => $type];

        $params = array_merge($params, $conditions);
    
        $query= $this->where($params)->where('status' , 1);
     
        if(!$paginate){
            return $query->get();
        }

        return $query->paginate($paginate);
    }

}