<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UnitDaysMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UnitDaysMutation'
    ];

    public function type()
    {
        return GraphQL::type('UnitDaysMutationType');
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