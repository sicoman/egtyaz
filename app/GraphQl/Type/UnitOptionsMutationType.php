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

class UnitOptionsMutationType extends UnitOptionsQueryType {
    
    protected $modelName = '\App\Models\UnitOptions';

    protected $attributes = [
        'name' => 'UnitOptionsMutationType',
        'description' => 'mutation UnitOptions Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("UnitOptionsSingleResponse"),
                'args' => [
                  'UnitOptions' => ['name' => 'UnitOptions', 'type' => GraphQL::type('UnitOptionsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("UnitOptionsSingleResponse"),
                'args' => [
                'UnitOptions' => ['name' => 'UnitOptions', 'type' => GraphQL::type('UnitOptionsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('UnitOptionsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'UnitOptions' => ['name' => 'UnitOptions', 'type' => GraphQL::type('UnitOptionsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $UnitOptions = isset($args['UnitOptions']) ? $args['UnitOptions'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($UnitOptions);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $UnitOptions = isset($args['UnitOptions']) ? $args['UnitOptions'] : false;
            $objects = (array) UnitOptionsInput::getObjects();
            $filteredUnitOptions = array_filter($UnitOptions, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newUnitOptions = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewUnitOptions = array_filter($newUnitOptions, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredUnitOptions);
            $res = $obj->update($filteredNewUnitOptions); 
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
            $UnitOptions = isset($args['UnitOptions']) ? $args['UnitOptions'] : false;
            $newUnitOptions = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($UnitOptions)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
