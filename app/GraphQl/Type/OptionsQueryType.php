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


class OptionsQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Options';

    protected $attributes = [
        'name' => 'OptionsQueryType',
        'description' => 'Options Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("OptionsSingleResponse"),
                'args' => [
                  'Options' => ['name' => 'Options', 'type' => GraphQL::type('OptionsInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("OptionsResponse"),
                'args' => [
                  'Options' => ['name' => 'Options', 'type' => GraphQL::type('OptionsInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]         
        ];
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $Options = isset($args['Options']) ? $args['Options'] : false;
        if($Options){
          $model = $model->where($Options);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }
    
    public function resolveGetAllField($root, $args) {  
        $model = app($this->modelName) ;
        if(isset($args['Options'])){
          $model = $model->where($args['Options']);
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
