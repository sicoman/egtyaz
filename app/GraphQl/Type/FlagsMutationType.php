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

class FlagsMutationType extends FlagsQueryType {
    
    protected $modelName = '\App\Models\Flags';

    protected $attributes = [
        'name' => 'FlagsMutationType',
        'description' => 'mutation Flags Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("FlagsSingleResponse"),
                'args' => [
                  'Flags' => ['name' => 'Flags', 'type' => GraphQL::type('FlagsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("FlagsSingleResponse"),
                'args' => [
                'Flags' => ['name' => 'Flags', 'type' => GraphQL::type('FlagsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('FlagsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Flags' => ['name' => 'Flags', 'type' => GraphQL::type('FlagsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        if(!$this->isAuthorized()){
            return false; 
        }

        $Flags = isset($args['Flags']) ? $args['Flags'] : false;

        $model = app($this->modelName); 

        $isFlagged = $model->where($Flags)->first();

        if(!$isFlagged){
            $Flags['flagged_by'] = $this->isAuthorized()->id;
            $res = $model::create($Flags);
            return $this->resolveResponse($res);
        }else{
            return $this->resolveErrors("You already have reported this unit !");
        }
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Flags = isset($args['Flags']) ? $args['Flags'] : false;
            $objects = (array) FlagsInput::getObjects();
            $filteredFlags = array_filter($Flags, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newFlags = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewFlags = array_filter($newFlags, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredFlags);
            $res = $obj->update($filteredNewFlags); 
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
            $Flags = isset($args['Flags']) ? $args['Flags'] : false;
            $newFlags = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Flags)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
