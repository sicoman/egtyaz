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

class PaginationInputType extends GraphQLType {
    

    protected $attributes = [
        'name' => 'paginationInput',
        'description' => 'Pagination input descriptior'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    protected $inputObject = true;

    public function fields() {
        return [
            'page' => [
                'type' => Type::int()
            ],
            'limit' => [
                'type' => Type::int()
            ],
        ];
    }
    
    


}
