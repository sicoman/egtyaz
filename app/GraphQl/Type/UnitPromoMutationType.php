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

class UnitPromoMutationType extends UnitPromoQueryType {
    
    protected $modelName = '\App\Models\UnitPromo';

    protected $attributes = [
        'name' => 'UnitPromoMutationType',
        'description' => 'mutation UnitPromo Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("UnitPromoSingleResponse"),
                'args' => [
                  'UnitPromo' => ['name' => 'UnitPromo', 'type' => GraphQL::type('UnitPromoInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("UnitPromoSingleResponse"),
                'args' => [
                'UnitPromo' => ['name' => 'UnitPromo', 'type' => GraphQL::type('UnitPromoInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('UnitPromoInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'UnitPromo' => ['name' => 'UnitPromo', 'type' => GraphQL::type('UnitPromoInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $UnitPromo = isset($args['UnitPromo']) ? $args['UnitPromo'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($UnitPromo);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $UnitPromo = isset($args['UnitPromo']) ? $args['UnitPromo'] : false;
            $objects = (array) UnitPromoInput::getObjects();
            $filteredUnitPromo = array_filter($UnitPromo, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newUnitPromo = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewUnitPromo = array_filter($newUnitPromo, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredUnitPromo);
            $res = $obj->update($filteredNewUnitPromo); 
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
            $UnitPromo = isset($args['UnitPromo']) ? $args['UnitPromo'] : false;
            $newUnitPromo = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($UnitPromo)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
