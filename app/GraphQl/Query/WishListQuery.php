<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class WishListQuery extends Query
{
    protected $attributes = [
        'name' => 'WishListQuery'
    ];

    public function type()
    {
        return GraphQL::type('WishListQueryType');
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