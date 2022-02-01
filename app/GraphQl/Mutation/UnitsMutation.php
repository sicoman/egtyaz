<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UnitsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UnitsMutation'
    ];

    public function type()
    {
        return GraphQL::type('UnitsMutationType');
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