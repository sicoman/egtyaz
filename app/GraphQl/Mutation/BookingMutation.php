<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class BookingMutation extends Mutation
{
    protected $attributes = [
        'name' => 'BookingMutation'
    ];

    public function type()
    {
        return GraphQL::type('BookingMutationType');
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