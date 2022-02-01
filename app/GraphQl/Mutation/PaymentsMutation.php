<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class PaymentsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'PaymentsMutation'
    ];

    public function type()
    {
        return GraphQL::type('PaymentsMutationType');
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