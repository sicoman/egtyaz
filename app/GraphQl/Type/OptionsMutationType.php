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

class OptionsMutationType extends OptionsQueryType {
    
    protected $modelName = '\App\Models\Options';

    protected $attributes = [
        'name' => 'OptionsMutationType',
        'description' => 'mutation Options Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("OptionsSingleResponse"),
                'args' => [
                  'Options' => ['name' => 'Options', 'type' => GraphQL::type('OptionsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("OptionsSingleResponse"),
                'args' => [
                'Options' => ['name' => 'Options', 'type' => GraphQL::type('OptionsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('OptionsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Options' => ['name' => 'Options', 'type' => GraphQL::type('OptionsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Options = isset($args['Options']) ? $args['Options'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Options);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Options = isset($args['Options']) ? $args['Options'] : false;
            $objects = (array) OptionsInput::getObjects();
            $filteredOptions = array_filter($Options, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newOptions = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewOptions = array_filter($newOptions, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredOptions);
            $res = $obj->update($filteredNewOptions); 
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
            $Options = isset($args['Options']) ? $args['Options'] : false;
            $newOptions = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Options)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
