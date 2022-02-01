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

class AccountsMutationType extends AccountsQueryType {
    
    protected $modelName = '\App\Models\Accounts';

    protected $attributes = [
        'name' => 'AccountsMutationType',
        'description' => 'mutation Accounts Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("AccountsSingleResponse"),
                'args' => [
                  'Accounts' => ['name' => 'Accounts', 'type' => GraphQL::type('AccountsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("AccountsSingleResponse"),
                'args' => [
                'Accounts' => ['name' => 'Accounts', 'type' => GraphQL::type('AccountsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('AccountsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Accounts' => ['name' => 'Accounts', 'type' => GraphQL::type('AccountsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Accounts = isset($args['Accounts']) ? $args['Accounts'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Accounts);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Accounts = isset($args['Accounts']) ? $args['Accounts'] : false;
            $objects = (array) AccountsInput::getObjects();
            $filteredAccounts = array_filter($Accounts, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newAccounts = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewAccounts = array_filter($newAccounts, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredAccounts);
            $res = $obj->update($filteredNewAccounts); 
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
            $Accounts = isset($args['Accounts']) ? $args['Accounts'] : false;
            $newAccounts = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Accounts)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
