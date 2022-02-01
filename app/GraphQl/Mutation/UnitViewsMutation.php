<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UnitViewsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UnitViewsMutation'
    ];

    public function type()
    {
        return GraphQL::type('UnitViewsMutationType');
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