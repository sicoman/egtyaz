<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class SearchMutation extends Mutation
{
    protected $attributes = [
        'name' => 'SearchMutation'
    ];

    public function type()
    {
        return GraphQL::type('SearchMutationType');
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