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

class UnitsMutationType extends UnitsQueryType {
    
    protected $modelName = '\App\Models\Units';

    protected $attributes = [
        'name' => 'UnitsMutationType',
        'description' => 'mutation Units Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("UnitsSingleResponse"),
                'args' => [
                  'Units' => ['name' => 'Units', 'type' => GraphQL::type('UnitsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("UnitsSingleResponse"),
                'args' => [
                'Units' => ['name' => 'Units', 'type' => GraphQL::type('UnitsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('UnitsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Units' => ['name' => 'Units', 'type' => GraphQL::type('UnitsInput')]
                ]
                ],      
                'AddBooking' => [
                    'type' => GraphQL::type("BooleanReportSingleResponse"),
                    'args' => [
                    'Units' => ['name' => 'Units', 'type' => GraphQL::type('UnitsInput')]
                    ]
                ] ,
                'updateOrAddDays' => [
                    'type' => GraphQL::type("BooleanReportSingleResponse"),
                    'args' => [
                    'unit_id' => ['name' => 'unit_id', 'type' => Type::id()],
                    'Days' => ['name' => 'Days', 'type' => Type::listOf(GraphQL::type('DaysInput'))]
                    ]
                ] , 
        ];
    } 

    public function resolveupdateOrAddDaysField($root, $args) {  
        $days = isset($args['Days']) ? $args['Days'] : false;
        $id = isset($args['unit_id']) ? $args['unit_id'] : false;
        $model = app($this->modelName); 
        $unit = $model->find($id);
        $res = false ;   
        if($unit){    
            $unit->DaysList()->delete();
            $daysObjs = array_map(function($day){
                return new \App\Models\Days($day);
            }, $days);  
            $unit->DaysList()->saveMany($daysObjs);  
            $res = true ;
        }

        return $this->resolveResponse($res);
    }
 
    public function resolveCreateField($root, $args) {
        $Units = isset($args['Units']) ? $args['Units'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Units);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Units = isset($args['Units']) ? $args['Units'] : false;
            $objects = (array) UnitsInput::getObjects();
            $filteredUnits = array_filter($Units, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newUnits = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewUnits = array_filter($newUnits, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredUnits);
            $res = $obj->update($filteredNewUnits); 
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
            $Units = isset($args['Units']) ? $args['Units'] : false;
            $newUnits = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Units)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
