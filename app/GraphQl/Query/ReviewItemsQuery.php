<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class ReviewItemsQuery extends Query
{
    protected $attributes = [
        'name' => 'ReviewItemsQuery'
    ];

    public function type()
    {
        return GraphQL::type('ReviewItemsQueryType');
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