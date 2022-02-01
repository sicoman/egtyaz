<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UserLoginMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UserLoginMutation'
    ];

    public function type()
    {
        return GraphQL::type('UserLoginMutationType');
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