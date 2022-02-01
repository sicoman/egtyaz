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
use GraphQL\Type\Definition\ScalarType;
use App\Models\Taxonomy;

class JsonType extends ScalarType
{
    // Note: name can be omitted. In this case it will be inferred from class name 
    // (suffix "Type" will be dropped)
    public $name = 'JsonType';

    /**
     * Serializes an internal value to include in a response.
     *
     * @param string $value
     * @return string
     */
    public function serialize($value)
    {  
        // Assuming internal representation of email is always correct:
        return $value;

        // If it might be incorrect and you want to make sure that only correct values are included
        // in response - use following line instead:
        // if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        //     throw new InvariantViolation("Could not serialize following value as email: " . Utils::printSafe($value));
        // }
        // return $this->parseValue($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Error("Cannot represent following value as email: " . Utils::printSafeJson($value));
        }
        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     * 
     * E.g. 
     * {
     *   user(email: "user@example.com") 
     * }
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @param array|null $variables
     * @return string
     * @throws Error
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        // Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
        // error location in query:
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }
        if (!filter_var($valueNode->value, FILTER_VALIDATE_EMAIL)) {
            throw new Error("Not a valid email", [$valueNode]);
        }

        return $valueNode->value;
    }
}


class UnitsType extends GraphQLType {

    protected $attributes = [
        'name' => 'UnitsType',
        'description' => 'Units Description'
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
,'user_id' => [
                    'type' => Type::int(),
                        
                ]
,'title' => [
                    'type' => Type::string(),
                        
                ]
,'description' => [
                    'type' => Type::string(),
                        
                ]
,'type' => [
                    'type' => Type::int(),
                        
                ]
,'child_type' => [
                    'type' => Type::int(),
                        
                ]
,'allow_childrens' => [
                    'type' => Type::boolean(),
                        
                ]
,'allow_infants' => [
                    'type' => Type::boolean(),
                        
                ]
,'allow_animals' => [
                    'type' => Type::int(),
                        
                ]
,'allow_extra' => [
                    'type' => Type::boolean(),
                        
                ]
,'guests' => [
                    'type' => Type::int(),
                        
                ]
,'rooms' => [
                    'type' => Type::int(),
                        
                ]  
,'beds' => [
                    'type' => Type::int(),
                        
                ]
                ,'balacons' => [
                    'type' => Type::int(),
                        
                ]              
,'bathrooms' => [
                    'type' => Type::int(),
                        
                ]
,'min_guests' => [
                    'type' => Type::int(),
                        
                ]
,'max_childrens' => [
                    'type' => Type::int(),
                        
                ]
,'min_days' => [
                    'type' => Type::int(),
                        
                ]
,'max_days' => [
                    'type' => Type::int(),
                        
                ]
,'longitude' => [
                    'type' => Type::string(),
                        
                ]
,'latitude' => [
                    'type' => Type::string(),
                        
                ]
,'city' => [
                    'type' => Type::int(),
                        
                ]
                ,'country' => [
                    'type' => Type::int(),
                        
                ]       
                ,'government' => [
                    'type' => Type::int(),
                        
                ]  
                ,'area' => [
                    'type' => Type::int(),
                        
                ]        
,'address' => [
                    'type' => Type::string(),
                        
                ]
,'building_number' => [
                    'type' => Type::int(),
                        
                ]
,'unit_number' => [
                    'type' => Type::int(),
                        
                ]
,'floor_number' => [
                    'type' => Type::int(),
                        
                ]
,'bank_account' => [
                    'type' => Type::string(),
                        
                ]
,'bank_number' => [
                    'type' => Type::string(),
                        
                ]
,'fee' => [
                    'type' => Type::float(),
                        
                ]
,'checkin' => [
                    'type' => Type::string(),
                        
                ]
,'checkout' => [
                    'type' => Type::string(),
                        
                ]
,'deliver_keys' => [
                    'type' => Type::string(),
                        
                ]
,'get_keys' => [
                    'type' => Type::string(),
                        
                ]
,'notes' => [
                    'type' => Type::string(),
                        
                ]
,'contract_image' => [
                    'type' => Type::string(),
                        
]
,'video' => [
    'type' => Type::string(),
        
]
,'currency' => [
    'type' => Type::string(),
        
]
,'cancle_policy' => [
                    'type' => Type::int(),
                        
]
,'price' => [
    'type' => Type::float(),
         
]
,'status' => [
           'type' => GraphQL::type("UnitStatusEnumType"),
                        
                ]
,'featured' => [
                    'type' => Type::boolean(),
                        
                ]
,'created_at' => [
                    'type' => Type::string(),
                        
                ]
,'updated_at' => [
                    'type' => Type::string(),
                          
],
'cPolicy' => [   
    'type' => GraphQl::type("CancelType"),
    'resolve' => function($unit){
        return $unit->cPolicy()->first();
    }
],
'owner' => [
    'type'   => GraphQl::type("UsersType"),
    'resolve' => function($unit){
         return $unit->Owner()->first();
      
    }
],
'rate' => [
    'type'   => GraphQl::type("RatesType"),
    'resolve' => function($inputObject){
        $rate =  $inputObject->rate ;  
        return [
            'counter' => (int) $rate['c'],
            'score'   => (float) $rate['score']
        ];
    }
],
'Amenities' => [
    'type'   => Type::listOf(GraphQl::type("TaxonomyType")),
    'resolve' => function($inputObject){
        return $inputObject->Amenities()->get();
    }
],
'Views' => [
    'type'   => Type::listOf(GraphQl::type("TaxonomyType")),
    'resolve' => function($inputObject){
        return $inputObject->Views()->get();
    }
], 
'Rests' => [
    'type'   => Type::listOf(GraphQl::type("TaxonomyType")),
    'resolve' => function($inputObject){
        return $inputObject->Rest()->get();
    }
],
'Days' => [
    'type'   => Type::listOf(GraphQl::type("UnitDaysType")),
    'resolve' => function($inputObject){
        return $inputObject->Days()->get();
    }
],
'Promo' => [
    'type'   => Type::listOf(GraphQl::type("UnitPromoType")),
    'resolve' => function($inputObject){
        return $inputObject->Promo()->get();
    }
],
'DaysList' => [
    'type'   => Type::listOf(GraphQl::type("DaysType")),
    'resolve' => function($inputObject){
        return $inputObject->DaysList()->get();
    }
],
'Fees' => [
    'type'   => Type::listOf(GraphQl::type("UnitFeesType")),
    'resolve' => function($inputObject){
        $result = Taxonomy::join('unit_fees', 'unit_fees.fee_id', 'taxonomies.id')->where('unit_fees.unit_id', $inputObject->id)->get();
        return $result ;
    }
],
'FeeVat'=> [
    'type' => new JsonType(),
    'resolve' => function($obj){
       return $obj->feeVat;
    }
],
'Bookings' => [
    'type'   => Type::listOf(GraphQl::type("BookingType")),
    'resolve' => function($inputObject){
        return $inputObject->Booking()->get();
    }
],

'badges' => [
    'type'   => Type::listOf(GraphQl::type("BadgeType")) ,
    'resolve' => function($inputObject){
        return $inputObject->Badges()->get();
    }
],

'attachments' => ['type' => Type::listOf(GraphQL::type("AttachmentsType")) ]

        ];
}
    


}
