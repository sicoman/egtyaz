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

class BadgeMutationType extends BadgeQueryType {
    
    protected $modelName = '\App\Models\Badge';

    protected $attributes = [
        'name' => 'BadgeMutationType',
        'description' => 'mutation Badge Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("BadgeSingleResponse"),
                'args' => [
                  'Badge' => ['name' => 'Badge', 'type' => GraphQL::type('BadgeInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("BadgeSingleResponse"),
                'args' => [
                'Badge' => ['name' => 'Badge', 'type' => GraphQL::type('BadgeInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('BadgeInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Badge' => ['name' => 'Badge', 'type' => GraphQL::type('BadgeInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Badge = isset($args['Badge']) ? $args['Badge'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Badge);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Badge = isset($args['Badge']) ? $args['Badge'] : false;
            $objects = (array) BadgeInput::getObjects();
            $filteredBadge = array_filter($Badge, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newBadge = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewBadge = array_filter($newBadge, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredBadge);
            $res = $obj->update($filteredNewBadge); 
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
            $Badge = isset($args['Badge']) ? $args['Badge'] : false;
            $newBadge = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Badge)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
