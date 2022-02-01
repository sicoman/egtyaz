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

class MessageTextMutationType extends MessageTextQueryType {
    
    protected $modelName = '\App\Models\MessageText';

    protected $attributes = [
        'name' => 'MessageTextMutationType',
        'description' => 'mutation MessageText Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("MessageTextSingleResponse"),
                'args' => [
                  'MessageText' => ['name' => 'MessageText', 'type' => GraphQL::type('MessageTextInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("MessageTextSingleResponse"),
                'args' => [
                'MessageText' => ['name' => 'MessageText', 'type' => GraphQL::type('MessageTextInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('MessageTextInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'MessageText' => ['name' => 'MessageText', 'type' => GraphQL::type('MessageTextInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $MessageText = isset($args['MessageText']) ? $args['MessageText'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($MessageText);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $MessageText = isset($args['MessageText']) ? $args['MessageText'] : false;
            $objects = (array) MessageTextInput::getObjects();
            $filteredMessageText = array_filter($MessageText, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newMessageText = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewMessageText = array_filter($newMessageText, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredMessageText);
            $res = $obj->update($filteredNewMessageText); 
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
            $MessageText = isset($args['MessageText']) ? $args['MessageText'] : false;
            $newMessageText = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($MessageText)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
