<?php


namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;

class UnitStatusEnumType extends GraphQLType {
  
    protected $enumObject = true;
    protected $attributes = [
        'name' => 'UnitStatusEnumType',
        'description' => 'UnitStatusEnumType Description sa',
        'values' => [
            'DISABLED' => 0, 
            'ACTIVE' => 1,
            'UPDATED' => 2,  //system
            'DRAFT' => -10, //system

        ],
    ];

}
