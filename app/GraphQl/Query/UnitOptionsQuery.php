<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class UnitOptionsQuery extends Query
{
    protected $attributes = [
        'name' => 'UnitOptionsQuery'
    ];

    public function type()
    {
        return GraphQL::type('UnitOptionsQueryType');
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