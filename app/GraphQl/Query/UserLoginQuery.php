<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class UserLoginQuery extends Query
{
    protected $attributes = [
        'name' => 'UserLoginQuery'
    ];

    public function type()
    {
        return GraphQL::type('UserLoginQueryType');
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