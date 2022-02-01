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

class UnitViewsMutationType extends UnitViewsQueryType {
    
    protected $modelName = '\App\Models\UnitViews';

    protected $attributes = [
        'name' => 'UnitViewsMutationType',
        'description' => 'mutation UnitViews Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("UnitViewsSingleResponse"),
                'args' => [
                  'UnitViews' => ['name' => 'UnitViews', 'type' => GraphQL::type('UnitViewsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("UnitViewsSingleResponse"),
                'args' => [
                'UnitViews' => ['name' => 'UnitViews', 'type' => GraphQL::type('UnitViewsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('UnitViewsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'UnitViews' => ['name' => 'UnitViews', 'type' => GraphQL::type('UnitViewsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $UnitViews = isset($args['UnitViews']) ? $args['UnitViews'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($UnitViews);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $UnitViews = isset($args['UnitViews']) ? $args['UnitViews'] : false;
            $objects = (array) UnitViewsInput::getObjects();
            $filteredUnitViews = array_filter($UnitViews, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newUnitViews = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewUnitViews = array_filter($newUnitViews, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredUnitViews);
            $res = $obj->update($filteredNewUnitViews); 
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
            $UnitViews = isset($args['UnitViews']) ? $args['UnitViews'] : false;
            $newUnitViews = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($UnitViews)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
