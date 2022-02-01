<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class ReviewItemsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'ReviewItemsMutation'
    ];

    public function type()
    {
        return GraphQL::type('ReviewItemsMutationType');
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