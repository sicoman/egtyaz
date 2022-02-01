<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class UnitDaysQuery extends Query
{
    protected $attributes = [
        'name' => 'UnitDaysQuery'
    ];

    public function type()
    {
        return GraphQL::type('UnitDaysQueryType');
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