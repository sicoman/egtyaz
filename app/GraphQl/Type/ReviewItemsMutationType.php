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

class ReviewItemsMutationType extends ReviewItemsQueryType {
    
    protected $modelName = '\App\Models\ReviewItems';

    protected $attributes = [
        'name' => 'ReviewItemsMutationType',
        'description' => 'mutation ReviewItems Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("ReviewItemsSingleResponse"),
                'args' => [
                  'ReviewItems' => ['name' => 'ReviewItems', 'type' => GraphQL::type('ReviewItemsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("ReviewItemsSingleResponse"),
                'args' => [
                'ReviewItems' => ['name' => 'ReviewItems', 'type' => GraphQL::type('ReviewItemsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('ReviewItemsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'ReviewItems' => ['name' => 'ReviewItems', 'type' => GraphQL::type('ReviewItemsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $ReviewItems = isset($args['ReviewItems']) ? $args['ReviewItems'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($ReviewItems);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $ReviewItems = isset($args['ReviewItems']) ? $args['ReviewItems'] : false;
            $objects = (array) ReviewItemsInput::getObjects();
            $filteredReviewItems = array_filter($ReviewItems, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newReviewItems = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewReviewItems = array_filter($newReviewItems, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredReviewItems);
            $res = $obj->update($filteredNewReviewItems); 
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
            $ReviewItems = isset($args['ReviewItems']) ? $args['ReviewItems'] : false;
            $newReviewItems = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($ReviewItems)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
