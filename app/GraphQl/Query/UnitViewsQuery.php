<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class UnitViewsQuery extends Query
{
    protected $attributes = [
        'name' => 'UnitViewsQuery'
    ];

    public function type()
    {
        return GraphQL::type('UnitViewsQueryType');
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