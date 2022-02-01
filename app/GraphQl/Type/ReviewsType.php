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

class ReviewsType extends GraphQLType {

    protected $attributes = [
        'name' => 'ReviewsType',
        'description' => 'Reviews Description'
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
                ,'unit_id' => [
                    'type' => Type::int(),
                        
                ]                
,'owner_id' => [
                    'type' => Type::int(),
                        
                ]
,'booking_id' => [
                    'type' => Type::int(),
                        
                ]
,'guest_id' => [
                    'type' => Type::int(),
                        
                ]
,'reviewer_id' => [
                    'type' => Type::int(),
                        
]
,'Unit' => [
    'type' => GraphQl::type("UnitsType"),
        
]
,'status' => [
    'type' => Type::int(),
          
],
'Booking' => [
    'type' => GraphQl::type("BookingType")
]
,'reviewerName' => [
    'type' => Type::string(),  
    'resolve' => function($obj){
        return $obj->name ;
    }    
],
'_guest' => [
    'type' => GraphQl::type("UsersType"),
    'resolve' => function($obj){
        return $obj->Reviewer()->first();
    }
],
'_owner' => [  
    'type' => GraphQl::type("UsersType"),
    'resolve' => function($obj){
        return $obj->Owner()->first();
    }
]
,'reviewerImg' => [
    'type' => Type::string(),
    'resolve' => function($obj){
        return $obj->avatar ;
    }    
]
,'score' => [
                    'type' => Type::float(),
                        
],'guest_score' => [
    'type' => Type::float(),
        
]
,'guest_review' => [
    'type' => Type::string(),
        
]
,'review' => [
    'type' => Type::string(),
        
]
,'items' => [  
    'type' => Type::listOf(GraphQl::type("ReviewItemsType")),
    'args' => [
        'type' =>['name' => 'itemsType', 'type' => Type::string()],
    ],
    'resolve' => function($obj, $args){
        if($args['itemsType'] == "reviews"){
            return $obj->reviews()->get();
        }else{
            return $obj->Guest_reviews()->get();
        }

    }    
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
