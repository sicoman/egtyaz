<?php 

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use App\User;

class UsersMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UsersMutation'
    ];

    public function type()
    {
        return GraphQL::type('UsersMutationType');
    }

    public function args()
    {
        return [

        ];
    }

    public function resolve($root, $args)
    {
        return true;
    }
}