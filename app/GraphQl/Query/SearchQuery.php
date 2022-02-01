<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class SearchQuery extends Query
{
    protected $attributes = [
        'name' => 'SearchQuery'
    ];

    public function type()
    {
        return GraphQL::type('SearchQueryType');
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