<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class TaxonomyMutation extends Mutation
{
    protected $attributes = [
        'name' => 'TaxonomyMutation'
    ];

    public function type()
    {
        return GraphQL::type('TaxonomyMutationType');
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