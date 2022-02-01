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
use Illuminate\Support\Facades\Auth;
use App\GraphQL\Type\ResponseQueryType;


class SearchQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Search';

    protected $attributes = [
        'name' => 'SearchQueryType',
        'description' => 'Search Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("SearchSingleResponse"),
                'args' => [
                  'Search' => ['name' => 'Search', 'type' => GraphQL::type('SearchInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("SearchResponse"),
                'args' => [
                  'Search' => ['name' => 'Search', 'type' => GraphQL::type('SearchInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]         
        ];
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $Search = isset($args['Search']) ? $args['Search'] : false;
        if($Search){
          $model = $model->where($Search);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }
    
    public function resolveGetAllField($root, $args) {  
        $model = app($this->modelName) ;
        if(isset($args['Search'])){
          $model = $model->where($args['Search']);
        }

        if (isset($args['pagination'])) {
            $per_page = isset($args['pagination']['limit']) ? (int) $args['pagination']['limit'] : $this->responseLimit;
            $args['pagination']['page'] = isset($args['pagination']['page']) ? $args['pagination']['page'] : 1;
            $res = $model->paginate($per_page, ['*'], 'page', $args['pagination']['page']);
        } else {
            $res = $model->get();
        }      
        return $this->resolveResponse($res);
    }

}
