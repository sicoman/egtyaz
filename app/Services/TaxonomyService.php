<?php

namespace App\Services;

use App\Repositories\TaxonomyRepository;
use Exception;

class TaxonomyService extends BaseService{

    public function __construct(TaxonomyRepository $repo)
    {   
        parent::__construct($repo) ;
    }

    public function getWithPosts($params, $limit = 5){
       return $this->repo->where($params)->with(['Posts' => function($query) use($limit){
            $query->where('posts.status', 1);
            $query->limit($limit);
        }])->where('status' , 1)->get() ;
    }

    public function byType($type){
        return $this->repo->byType($type);
    }

    public function getById($id){
        try{
            return $this->repo->find($id);
        }catch(Exception $exception){
            return false;
        }
   
    }

    public function getQuestionsCategories(){
        return $this->repo->byType('category');
    }

    public function getSubjects(int $category_id = 0){
        return $this->repo->byType('subject', $category_id);
    }

    public function getSkills(int $category_id = 0){ 
        return $this->repo->byType('skill', $category_id);
    }

    public function getQuestionsCategoriesObject(){
        return $this->repo->ByTypeObject('category');
    }

    public function getSubjectsObject(int $category_id = 0){
        return $this->repo->ByTypeObject('subject', $category_id);
    }

    public function getSkillsObject(int $category_id = 0){
        return $this->repo->ByTypeObject('skill', $category_id);
    }

    public function getLevelsObject(int $category_id = 0){
        return $this->repo->ByTypeObject('level', $category_id);
    }

    public function getElearn(int $category_id = 0){
        return $this->repo->byType('elearn', $category_id);
    }


}



?>