<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class WishListMutation extends Mutation
{
    protected $attributes = [
        'name' => 'WishListMutation'
    ];

    public function type()
    {
        return GraphQL::type('WishListMutationType');
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