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

class BookingCancelType extends GraphQLType {

    protected $attributes = [
        'name' => 'BookingCancelType',
        'description' => 'BookingCancelType Description'
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
,'booking_id' => [
                    'type' => Type::int(),
                ]
,'cancel_id' => [
                    'type' => Type::int(),
                ]
,'price' => [
                    'type' => Type::float(),
                ]
,'price_fee' => [
                    'type' => Type::float(),
                ]
,'ezuru_fee' => [
                    'type' => Type::float(),
                ]
,'tourism' => [
                    'type' => Type::float(),
                ]
,'tax' => [
                    'type' => Type::float(),
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
