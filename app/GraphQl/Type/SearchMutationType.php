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

class SearchMutationType extends SearchQueryType {
    
    protected $modelName = '\App\Models\Search';

    protected $attributes = [
        'name' => 'SearchMutationType',
        'description' => 'mutation Search Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("SearchSingleResponse"),
                'args' => [
                  'Search' => ['name' => 'Search', 'type' => GraphQL::type('SearchInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("SearchSingleResponse"),
                'args' => [
                'Search' => ['name' => 'Search', 'type' => GraphQL::type('SearchInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('SearchInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Search' => ['name' => 'Search', 'type' => GraphQL::type('SearchInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Search = isset($args['Search']) ? $args['Search'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Search);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Search = isset($args['Search']) ? $args['Search'] : false;
            $objects = (array) SearchInput::getObjects();
            $filteredSearch = array_filter($Search, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newSearch = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewSearch = array_filter($newSearch, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredSearch);
            $res = $obj->update($filteredNewSearch); 
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
            $Search = isset($args['Search']) ? $args['Search'] : false;
            $newSearch = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Search)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
