<?php

namespace App\Services;

use App\Services\TaxonomyService;

class CommunityService extends BaseService{

    protected $taxService;
    protected $postsService;

    public function __construct(TaxonomyService $taxService, PostsService $postsService)
    {
        $this->taxService = $taxService;
        $this->postsService = $postsService;
    }

   
    public function listCategoriesWithPosts($where = [], $postLimit = 5){
        return $this->taxService->getWithPosts(array_merge(['type' => "forum"], $where), $postLimit);
    }

    public function getCategories(){
        return $this->taxService->byType("forum");
    }

    public function getCategoryById($id){
        return $this->taxService->getById($id);
    }

    public function getPosts($tax_id = false, $id = false){

        $conditions = [];

        if($tax_id){
           $conditions['taxonomy_id'] = $tax_id;
        }

        if($id){
            $conditions['id'] = $id;
        }

       $posts = $this->postsService->byType("forum", $conditions);

       return $posts;
    }



}
