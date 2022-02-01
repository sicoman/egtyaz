<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class AttachmentsQuery extends Query
{
    protected $attributes = [
        'name' => 'AttachmentsQuery'
    ];

    public function type()
    {
        return GraphQL::type('AttachmentsQueryType');
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