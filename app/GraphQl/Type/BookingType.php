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

class BookingType extends GraphQLType {

    protected $attributes = [
        'name' => 'BookingType',
        'description' => 'Booking Description'
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
                        
],
'unit' => [
    'type'     => GraphQL::type("UnitsType"),
    'resolve'  => function($obj){  
        return $obj->Unit()->where('id', $obj->unit_id)->first();
    }
]
,'user_id' => [
                    'type' => Type::int(),
                        
                ]
,'owner_id' => [
                    'type' => Type::int(),
                        
]
,'adults' => [
    'type' => Type::int(),
        
]
,'childrens' => [
    'type' => Type::int(),
        
]
,'check_in' => [
    'type' => Type::string(),
        
]
,'check_out' => [
    'type' => Type::string(),
        
]
,'Owner' => [
    'type' => GraphQL::type("UsersType"),
        
]
,'User' => [
    'type' => GraphQL::type("UsersType"),
        
]
,'gateway' => [
    'type' => Type::string(),
        
]
,'date_start' => [
                    'type' => Type::string(),
                        
                ]
,'date_end' => [
                    'type' => Type::string(),
                        
                ]
,'day_price' => [
                    'type' => Type::float(),
                        
                ]
,'price' => [
                    'type' => Type::float(),
                        
                ]
,'fee' => [
                    'type' => Type::float(),
                        
]
,'ezuru_fee' => [
    'type' => Type::float(),
        
]
,'tax' => [
    'type' => Type::float(),
        
]
,'tourism' => [
    'type' => Type::float(),
        
]
                ,'childrens' => [
                    'type' => Type::int(),
                        
                ]  
                ,'adults' => [
                    'type' => Type::int(),
                        
                ]                     
,'check_in' => [
                    'type' => Type::string(),
                        
                ]
,'check_out' => [
                    'type' => Type::string(),
                        
                ]
,'status' => [
                    'type' => GraphQL::type("BookingStatusEnumType"),
                        
                ] 
,'created_at' => [
                    'type' => Type::string(),
                        
                ]
,'updated_at' => [
                    'type' => Type::string(),
                        
],
'cancel' => [
    'type' => GraphQL::type("BookingCancelType")
],
'getPaymentUrl' => [
    'type' => Type::string(),
    'resolve' => function($obj){
        return isset($obj->paymentUrl) ? $obj->paymentUrl : "";
    }
        
],

        ];
    }
    


}
