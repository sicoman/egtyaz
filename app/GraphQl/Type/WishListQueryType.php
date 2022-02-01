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


class WishListQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\WishList';

    protected $attributes = [
        'name' => 'WishListQueryType',
        'description' => 'WishList Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'GetAll' => [
                'type' => GraphQL::type("WishListResponse"),
                'args' => [
                  'WishList' => ['name' => 'WishList', 'type' => GraphQL::type('WishListInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]         
        ];
    }


    
    public function resolveGetAllField($root, $args) {  
      
        if(!$this->isAuthorized()){
            return $this->resolveErrors(["denied permission for this request"]);
        }
 
        $uid = Auth::user()->id ;

        $model = app($this->modelName) ;
  
        if(isset($args['WishList'])){
            $model = $model->where($args['WishList']);
        }

        $model = $model->where('user_id', $uid);


        if( isset($args['rest']) ){

            if( isset( $args['full'] ) ){
                $model->with('unit.attachments') ;
            }

            if( isset( $args['id_only'] ) ){
                $listx = $model->select('unit_id')->get() ;
                $return = [] ;
                foreach( $listx as $unit ){
                    $return[] = $unit->unit_id ;
                }
                return $return ;
            }

        }



           
        if(!isset($args['pagination'])){
            $args['pagination'] = ['limit' => 9];
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
