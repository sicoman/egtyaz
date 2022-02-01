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

class UserLoginMutationType extends UserLoginQueryType {
    
    protected $modelName = '\App\Models\UserLogin';

    protected $attributes = [
        'name' => 'UserLoginMutationType',
        'description' => 'mutation UserLogin Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("UserLoginSingleResponse"),
                'args' => [
                  'UserLogin' => ['name' => 'UserLogin', 'type' => GraphQL::type('UserLoginInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("UserLoginSingleResponse"),
                'args' => [
                'UserLogin' => ['name' => 'UserLogin', 'type' => GraphQL::type('UserLoginInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('UserLoginInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'UserLogin' => ['name' => 'UserLogin', 'type' => GraphQL::type('UserLoginInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $UserLogin = isset($args['UserLogin']) ? $args['UserLogin'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($UserLogin);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $UserLogin = isset($args['UserLogin']) ? $args['UserLogin'] : false;
            $objects = (array) UserLoginInput::getObjects();
            $filteredUserLogin = array_filter($UserLogin, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newUserLogin = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewUserLogin = array_filter($newUserLogin, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredUserLogin);
            $res = $obj->update($filteredNewUserLogin); 
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
            $UserLogin = isset($args['UserLogin']) ? $args['UserLogin'] : false;
            $newUserLogin = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($UserLogin)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
