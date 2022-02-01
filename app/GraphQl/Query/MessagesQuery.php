<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class MessagesQuery extends Query
{
    protected $attributes = [
        'name' => 'MessagesQuery'
    ];

    public function type()
    {
        return GraphQL::type('MessagesQueryType');
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