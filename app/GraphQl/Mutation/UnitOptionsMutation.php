<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UnitOptionsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UnitOptionsMutation'
    ];

    public function type()
    {
        return GraphQL::type('UnitOptionsMutationType');
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