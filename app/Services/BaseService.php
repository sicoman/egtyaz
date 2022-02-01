<?php

namespace App\Services;

use App\Repositories\BaseRepository;
 
class BaseService
{

    /**
     * @var BaseRepository
     */
    protected $repo;

    public function __construct(BaseRepository $repo)
    {
        $this->repo = $repo;
    }

    public function find($where){
       if(is_numeric($where)){
          return $this->repo->find($where);
       }
      return  $this->repo->where($where)->first();
    }

    public function add($attributes)
    {
       return $this->repo->create($attributes);
    }

    public function update($id, $attributes)
    {
       return $this->repo->update($attributes, $id);
    }

    public function delete($id)
    {
      return  $this->repo->delete($id);
    }

    public function deleteWhere($conditions)
    {
       return $this->repo->deleteWhere($conditions);
    }

    public function WhereIn($column , $array)
    {
       return $this->repo->whereIn($column , $array);
    }
}
