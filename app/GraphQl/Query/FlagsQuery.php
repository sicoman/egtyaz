<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class FlagsQuery extends Query
{
    protected $attributes = [
        'name' => 'FlagsQuery'
    ];

    public function type()
    {
        return GraphQL::type('FlagsQueryType');
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