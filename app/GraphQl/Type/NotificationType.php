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

class NotificationJsonType extends ScalarType
{
    // Note: name can be omitted. In this case it will be inferred from class name 
    // (suffix "Type" will be dropped)
    public $name = 'NotificationJsonType';

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


class NotificationType extends GraphQLType {

    protected $attributes = [
        'name' => 'NotificationType',
        'description' => 'NotificationType descriptior'
    ];
    
    protected $__fields = [];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

   // protected $inputObject = true;
    
    public function __fields()
    {
        $this->__fields = [
            'id' => [
                'type' => Type::ID()
            ],
            'type' => [
                'type' => Type::string()
            ],
            'notifiable_type' => [
                'type' => Type::string()
            ],
            'notifiable_id' => [
                'type' => Type::int()
            ],
            'user' => [
                'type' => GraphQl::type("UsersType")
            ],
            'data'=> [
                'type' => new NotificationJsonType(),
                'resolve' => function($obj){
                   return $obj->data;
                }
            ]
            , 'read_at' => [
                'type' => Type::string(),
                'resolve' => function($carbon) {
                    return (string) $carbon['created_at'];
                }
            ]
        ]; ; 
    }

    public function fields() {
        $this->__fields();
        return $this->__fields;
    }

}
