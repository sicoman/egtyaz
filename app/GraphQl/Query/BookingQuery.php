<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class BookingQuery extends Query
{
    protected $attributes = [
        'name' => 'BookingQuery'
    ];

    public function type()
    {
        return GraphQL::type('BookingQueryType');
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