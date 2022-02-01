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

class PaymentsMutationType extends PaymentsQueryType {
    
    protected $modelName = '\App\Models\Payments';

    protected $attributes = [
        'name' => 'PaymentsMutationType',
        'description' => 'mutation Payments Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("PaymentsSingleResponse"),
                'args' => [
                  'Payments' => ['name' => 'Payments', 'type' => GraphQL::type('PaymentsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("PaymentsSingleResponse"),
                'args' => [
                'Payments' => ['name' => 'Payments', 'type' => GraphQL::type('PaymentsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('PaymentsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Payments' => ['name' => 'Payments', 'type' => GraphQL::type('PaymentsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Payments = isset($args['Payments']) ? $args['Payments'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Payments);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Payments = isset($args['Payments']) ? $args['Payments'] : false;
            $objects = (array) PaymentsInput::getObjects();
            $filteredPayments = array_filter($Payments, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newPayments = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewPayments = array_filter($newPayments, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredPayments);
            $res = $obj->update($filteredNewPayments); 
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
            $Payments = isset($args['Payments']) ? $args['Payments'] : false;
            $newPayments = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Payments)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
