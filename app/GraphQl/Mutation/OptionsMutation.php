<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class OptionsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'OptionsMutation'
    ];

    public function type()
    {
        return GraphQL::type('OptionsMutationType');
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