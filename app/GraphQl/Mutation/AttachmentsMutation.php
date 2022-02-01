<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class AttachmentsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'AttachmentsMutation'
    ];

    public function type()
    {
        return GraphQL::type('AttachmentsMutationType');
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