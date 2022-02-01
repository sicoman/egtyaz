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

class PaymentsType extends GraphQLType {

    protected $attributes = [
        'name' => 'PaymentsType',
        'description' => 'Payments Description'
    ];
    
    protected $__fields = [];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;
    
    public function fields() {
        $this->__fields();
        return $this->__fields;
    }

    public function __fields() {
        $this->__fields = [
        'id' => [
                    'type' => Type::int(),
                        
                ]
,'unit_id' => [
                    'type' => Type::int(),
                        
                ]
,'booking_id' => [
                    'type' => Type::int(),
                        
                ]
,'user_id' => [
                    'type' => Type::int(),
                        
                ]
,'owner_id' => [
                    'type' => Type::int(),
                        
                ]
,'cost' => [
                    'type' => Type::int(),
                        
                ]
,'fee' => [
                    'type' => Type::int(),
                        
                ]
,'total' => [
                    'type' => Type::int(),
                        
                ]
,'is_paid' => [
                    'type' => Type::boolean(),
                        
                ]
,'status' => [
                    'type' => Type::int(),
                        
                ]
,'created_at' => [
                    'type' => Type::string(),
                        
                ]
,'updated_at' => [
                    'type' => Type::string(),
                        
                ]

        ];
    }
    


}
