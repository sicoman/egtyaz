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

class UnitDaysMutationType extends UnitDaysQueryType {
    
    protected $modelName = '\App\Models\UnitDays';

    protected $attributes = [
        'name' => 'UnitDaysMutationType',
        'description' => 'mutation UnitDays Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("UnitDaysSingleResponse"),
                'args' => [
                  'UnitDays' => ['name' => 'UnitDays', 'type' => GraphQL::type('UnitDaysInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("UnitDaysSingleResponse"),
                'args' => [
                'UnitDays' => ['name' => 'UnitDays', 'type' => GraphQL::type('UnitDaysInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('UnitDaysInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'UnitDays' => ['name' => 'UnitDays', 'type' => GraphQL::type('UnitDaysInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $UnitDays = isset($args['UnitDays']) ? $args['UnitDays'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($UnitDays);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $UnitDays = isset($args['UnitDays']) ? $args['UnitDays'] : false;
            $objects = (array) UnitDaysInput::getObjects();
            $filteredUnitDays = array_filter($UnitDays, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newUnitDays = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewUnitDays = array_filter($newUnitDays, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredUnitDays);
            $res = $obj->update($filteredNewUnitDays); 
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
            $UnitDays = isset($args['UnitDays']) ? $args['UnitDays'] : false;
            $newUnitDays = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($UnitDays)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
