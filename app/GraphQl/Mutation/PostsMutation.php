<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class PostsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'PostsMutation'
    ];

    public function type()
    {
        return GraphQL::type('PostsMutationType');
    }
    

    public function args()
    {
        return [

        ];
    }

    public function resolve($root, $args)
    {
        return true ;
    }
}