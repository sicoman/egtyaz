<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class BadgeMutation extends Mutation
{
    protected $attributes = [
        'name' => 'BadgeMutation'
    ];

    public function type()
    {
        return GraphQL::type('BadgeMutationType');
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