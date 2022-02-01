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


class ReviewItemsQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\ReviewItems';

    protected $attributes = [
        'name' => 'ReviewItemsQueryType',
        'description' => 'ReviewItems Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("ReviewItemsSingleResponse"),
                'args' => [
                  'ReviewItems' => ['name' => 'ReviewItems', 'type' => GraphQL::type('ReviewItemsInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("ReviewItemsResponse"),
                'args' => [
                  'ReviewItems' => ['name' => 'ReviewItems', 'type' => GraphQL::type('ReviewItemsInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]         
        ];
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $ReviewItems = isset($args['ReviewItems']) ? $args['ReviewItems'] : false;
        if($ReviewItems){
          $model = $model->where($ReviewItems);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }
    
    public function resolveGetAllField($root, $args) {  
        $model = app($this->modelName) ;
        if(isset($args['ReviewItems'])){
          $model = $model->where($args['ReviewItems']);
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
