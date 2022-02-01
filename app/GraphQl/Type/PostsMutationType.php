<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostsMutationType extends PostsQueryType {
    
    protected $modelName = '\App\Models\Posts';

    protected $attributes = [
        'name' => 'PostsMutationType',
        'description' => 'mutation Posts Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("PostsSingleResponse"),
                'args' => [
                  'Posts' => ['name' => 'Posts', 'type' => GraphQL::type('PostsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("PostsSingleResponse"),
                'args' => [
                'Posts' => ['name' => 'Posts', 'type' => GraphQL::type('PostsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('PostsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Posts' => ['name' => 'Posts', 'type' => GraphQL::type('PostsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Posts = isset($args['Posts']) ? $args['Posts'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Posts);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Posts = isset($args['Posts']) ? $args['Posts'] : false;
            $objects = (array) PostsInput::getObjects();
            $filteredPosts = array_filter($Posts, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newPosts = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewPosts = array_filter($newPosts, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredPosts);
            $res = $obj->update($filteredNewPosts); 
              if(!empty($relatedNew)){  
                foreach($relatedNew as $t => $related){
                    $f = array_flip(array_keys($objects));   
                    if(array_key_exists($t, $f)){ 
                        switch($objects[$t]->type){
                            case '_mtm_':
                            $p = $objects[$t]->plural;  
                             $obj->first()->$p()->sync(collect($newPost[$related])->pluck('id'));
                            break;
                        }
                    }

                }
            }
            return $this->resolveResponse($obj->first());
        }
        return $this->resolveErrors(["denied permission for this request"]);
    }

    public function resolveDeleteField($root, $args) {  
        if($this->isAuthorized()){
            $Posts = isset($args['Posts']) ? $args['Posts'] : false;
            $newPosts = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Posts)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
