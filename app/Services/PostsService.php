<?php

namespace App\Services;

use App\Repositories\PostsRepository;

class PostsService extends BaseService
{
    public function __construct(PostsRepository $repo)
    {
        parent::__construct($repo) ;
    }

    public function byType(string $type, $conditions = [], $paginate = 15){
        $posts = $this->repo->byType($type, $conditions, $paginate);
        return $posts;
    }

    public function Comps($paginate = 15){
        $date = date('Y-m-d h:i:s') ;
        $posts = $this->repo->byType( 'competitions', [
            ['start' , '<=' , $date] ,
            ['end' , '>=' , $date]
        ] , $paginate);
        return $posts;
    }


}

?>