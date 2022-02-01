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

class BooleanReportType extends GraphQLType {

    protected $attributes = [
        'name' => 'eeeeeettt',
        'description' => 'BooleanReport Description'
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
        'result' => [
                    'type' => Type::boolean(),
                        
        ],
        'message' =>[
            'type' =>  Type::string()
        ]

        ];
    }
    


}
