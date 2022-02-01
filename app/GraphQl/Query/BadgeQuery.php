<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class BadgeQuery extends Query
{
    protected $attributes = [
        'name' => 'BadgeQuery'
    ];

    public function type()
    {
        return GraphQL::type('BadgeQueryType');
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