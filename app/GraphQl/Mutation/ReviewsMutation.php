<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class ReviewsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'ReviewsMutation'
    ];

    public function type()
    {
        return GraphQL::type('ReviewsMutationType');
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