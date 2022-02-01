<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class ReviewsQuery extends Query
{
    protected $attributes = [
        'name' => 'ReviewsQuery'
    ];

    public function type()
    {
        return GraphQL::type('ReviewsQueryType');
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