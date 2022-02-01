<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class OptionsQuery extends Query
{
    protected $attributes = [
        'name' => 'OptionsQuery'
    ];

    public function type()
    {
        return GraphQL::type('OptionsQueryType');
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