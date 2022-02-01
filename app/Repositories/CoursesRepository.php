<?php

namespace App\Repositories;


class CoursesRepository extends BaseRepository{

    public function model()
    {
       return ('App\\Courses');
    }

    public function by($conditions = [], $paginate = false){
        
        $params = [];

        $params = array_merge($params, $conditions);
    
        $query= $this->where($params)->where('status' , 1);
     
        if(!$paginate){
            return $query->get();
        }

        return $query->paginate($paginate);
    }

}