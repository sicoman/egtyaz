<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class FlagsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'FlagsMutation'
    ];

    public function type()
    {
        return GraphQL::type('FlagsMutationType');
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