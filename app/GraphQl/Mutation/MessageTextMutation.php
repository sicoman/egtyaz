<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class MessageTextMutation extends Mutation
{
    protected $attributes = [
        'name' => 'MessageTextMutation'
    ];

    public function type()
    {
        return GraphQL::type('MessageTextMutationType');
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