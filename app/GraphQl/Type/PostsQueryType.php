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


class PostsQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Posts';

    protected $attributes = [
        'name' => 'PostsQueryType',
        'description' => 'Posts Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("PostsSingleResponse"),
                'args' => [
                  'Posts' => ['name' => 'Posts', 'type' => GraphQL::type('PostsInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("PostsResponse"),
                'args' => [
                  'Posts' => ['name' => 'Posts', 'type' => GraphQL::type('PostsInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]         
        ];
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $Posts = isset($args['Posts']) ? $args['Posts'] : false;
        if($Posts){
          $model = $model->where($Posts);
        }
        $res = $model->first(); 
        $res->thumbs = $res->thumbs ;     
        return $this->resolveResponse($res);
    }
    
    public function resolveGetAllField($root, $args) {

        $model = app($this->modelName) ;
        if(isset($args['Posts'])){
            $model = $model->where($args['Posts']);
        }

        if(isset($args['Status'])){
            $model = $model->where('status' , $args['Status']);
        }else{
            $model = $model->where('status' , 1 );
        }

        if (isset($args['pagination'])) {
            $per_page = isset($args['pagination']['limit']) ? (int) $args['pagination']['limit'] : $this->responseLimit;
            $args['pagination']['page'] = isset($args['pagination']['page']) ? $args['pagination']['page'] : 1;
            $res = $model->paginate($per_page, ['*'], 'page', $args['pagination']['page']);
        } else {
            $res = $model->get();
        }


        $Xheaders = request()->header('accept-language') ;

        foreach( $res as $obj ){
            $obj->thumbs = $obj->thumbs ;

            if( $Xheaders == 'en' ){
                $obj->title = $obj->title_en ;
            }

            if( $Xheaders == 'en' && !in_array($obj->type , ['tourist_places' , 'popular_places'] ) ){
                $obj->description = $obj->description_en ;
            }

        }

        return $this->resolveResponse($res);
    }

}
