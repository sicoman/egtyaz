<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class UnitsQuery extends Query
{
    protected $attributes = [
        'name' => 'UnitsQuery'
    ];

    public function type()
    {
        return GraphQL::type('UnitsQueryType');
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