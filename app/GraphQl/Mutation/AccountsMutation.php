<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class AccountsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'AccountsMutation'
    ];

    public function type()
    {
        return GraphQL::type('AccountsMutationType');
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