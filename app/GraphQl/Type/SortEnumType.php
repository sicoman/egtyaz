<?php


namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;

class SortEnumType extends GraphQLType {

    protected $enumObject = true;
    protected $attributes = [
        'name' => 'SortEnumType',
        'description' => 'sort Description sa',
        'values' => [
              'DATEASC'     => 'DATEASC'
            , 'DATE'        => 'DATE'
            , 'PRICE'       => 'PRICE'
            , 'PRICEASC'    => 'PRICEASC'
            , 'FEATURED'    => 'FEATURED'
        ],
    ];

}
