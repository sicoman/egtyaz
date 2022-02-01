<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class MessageTextQuery extends Query
{
    protected $attributes = [
        'name' => 'MessageTextQuery'
    ];

    public function type()
    {
        return GraphQL::type('MessageTextQueryType');
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