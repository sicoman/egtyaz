<?php


namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;

class SortByEnumType extends GraphQLType {

    protected $enumObject = true;
    protected $attributes = [
        'name' => 'SortByStatusEnum',
        'description' => 'sort Description sa',
        'values' => [
            'DESC' => 'DESC'
            , 'ASC' => 'ASC'
            , 'DATE' => 'DATE',
            'POPULAR' => 'POPULAR',
            'FEATURED' => 'FEATURED',
            'PRICE' => 'PRICE',
            'PRICEASC' => 'PRICEASC'
        ],
    ];

}
