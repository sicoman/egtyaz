<?php

namespace App\Repositories;


class TaxonomyRepository extends BaseRepository{

    public function model()
    {
       return ('App\\Models\\Taxonomy');
    }

    public function byType(string $type, $parent_id = false){
        return $this->ByTypeObject($type , $parent_id)->get();
    }

    public function ByTypeObject(string $type, $parent_id = false){
        $params = ['type' => $type];
        if($parent_id){
            $params['parent'] = $parent_id;
        }
        return $this->where($params)->where('status' , 1) ;
    }

}