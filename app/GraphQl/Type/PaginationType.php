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

class PaginationType extends GraphQLType {
    

    protected $attributes = [
        'name' => 'paginationType',
        'description' => 'Pagination object descriptior'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'total' => [
                'type' => Type::int()
            ],
            'hasNext' => [
                'type' => Type::boolean()
            ],
            'hasPrev' => [
                'type' => Type::boolean()
            ],
            'pagesCount' => [
                'type' => Type::int()
            ],
            'page' => [
                'type' => Type::int()
            ],
            'limit' => [
                'type' => Type::int()
            ],
        ];
    }
    
    


}
