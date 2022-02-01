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


class MessagesQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Messages';

    protected $attributes = [
        'name' => 'MessagesQueryType',
        'description' => 'Messages Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("MessagesSingleResponse"),
                'args' => [
                  'Messages' => ['name' => 'Messages', 'type' => GraphQL::type('MessagesInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("MessagesResponse"),
                'args' => [
                  'Messages' => ['name' => 'Messages', 'type' => GraphQL::type('MessagesInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]         
        ];
    }

    public function resolveGetField($root, $args) {

        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);

        $model = app($this->modelName);
        $Messages = isset($args['Messages']) ? $args['Messages'] : false;
        if($Messages){
          $model = $model->where($Messages);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }   
    
    public function resolveGetAllField($root, $args) {  

        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);

        $model = app($this->modelName) ;
        if(isset($args['Messages'])){
          $model = $model->where($args['Messages']);
        } 
        $current_id = $this->isAuthorized()->id ; 
        $model = $model->where(function($query) use ($current_id){   
            $query->where('owner_id', $current_id)->orWhere('user_id', $current_id);
        });

        $model = $model->with('guest')->with('owner');

        if( !isset($args['rest']) ) {
            $model->with('chat') ;
        }

        $model->orderBy('updated_at' , 'DESC') ;

      //  print_r($model->toSql()); die;    
         if (isset($args['pagination'])) {  
            $per_page = isset($args['pagination']['limit']) ? (int) $args['pagination']['limit'] : $this->responseLimit;
            $args['pagination']['page'] = isset($args['pagination']['page']) ? $args['pagination']['page'] : 1;
            $res = $model->paginate($per_page, ['*'], 'page', $args['pagination']['page']);
        } else {
            $res = $model->get();
        } 
        
        if( isset($args['rest']) ) {
            foreach( $res as $r ){
                $r->chat = $r->getLastMessageAttribute();
                $r->unread_count = $r->getUnreadCountAttribute();
            }
        }

        return $this->resolveResponse($res);
    }

}
