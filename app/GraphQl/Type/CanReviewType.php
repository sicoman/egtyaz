<?php
namespace App\GraphQL\Type;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
class CanReviewType extends GraphQLType {

protected $attributes = [
    'name' => 'CanReviewType',
    'description' => 'CanReviewType Description'
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
    'booking_id' => [
                'type' => Type::int(),
                    
            ]
    ,'result' => [
                'type' => Type::boolean(),
                    
            ]
    ];
}



}
