<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class TaxonomyQuery extends Query
{
    protected $attributes = [
        'name' => 'TaxonomyQuery'
    ];

    public function type()
    {
        return GraphQL::type('TaxonomyQueryType');
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