<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class AccountsQuery extends Query
{
    protected $attributes = [
        'name' => 'AccountsQuery'
    ];

    public function type()
    {
        return GraphQL::type('AccountsQueryType');
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