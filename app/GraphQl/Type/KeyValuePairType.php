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


class KeyValuePairType extends GraphQLType {

    protected $attributes = [
        'name' => 'KeyValuePairType',
        'description' => 'KeyValuePairType descriptior'
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
            'key' => [
                'type' => (Type::string())
            ],
            'value' => [
                'type' => Type::string()
            ], 
        ]; ; 
    }

    public function fields() {
        $this->__fields();
        return $this->__fields;
    }

}
