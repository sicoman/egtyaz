<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ResponseType extends GraphQLType {

    protected $attributes = [
        'name' => 'addddddddd',
        'description' => 'Response descriptior'
    ];
    
    protected $responseType = null ;
    public $isMulti = true ;

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() { 
        return [
            'status' => [
                'type' => Type::nonNull(Type::ID())
            ],
            'message' => [
                'type' => (Type::string())
            ],
            'response' => [
                'type' => $this->isMulti ? Type::listOf(GraphQL::type($this->responseType)) : GraphQL::type($this->responseType)
            ],
            'pagination' => [
                'type' => GraphQL::type('PaginationType'),
                'resolve' => function($collection) {  
               
                    if(!isset($collection['response']) || is_array($collection['response']))
                        return null ;
                   
                    if( get_class($collection['response']) != "Illuminate\\Pagination\\LengthAwarePaginator")
                        return false ;
   
                    return ['page' => $collection['response']->currentPage(),'pagesCount' => $collection['response']->lastPage(),'hasPrev' => $collection['response']->currentPage() >  1, 'hasNext' => $collection['response']->currentPage() <  $collection['response']->lastPage(), 'limit' => $collection['response']->perPage(), 'total' => $collection['response']->total()];
                }
            ],
            'errors' => [
                'type' => Type::listOf(Type::string()),
                'resolve' => function($collection) {
                   return isset($collection['errors']) ? $collection['errors']  : [] ;
                }
            ]
        ];
    }

}
