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
use GraphQL\Type\Definition\ResolveInfo;


class UsersQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\User';

    protected $attributes = [
        'name' => 'UsersQueryType',
        'description' => 'UsersQueryType Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("UsersResponse"),
                'args' => [
                  'User' => ['name' => 'User', 'type' => GraphQL::type('UsersInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("UsersResponse"),
                'args' => [
                  'User' => ['name' => 'User', 'type' => GraphQL::type('UsersInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ],
            'GetNotification' => [
                'type' => Type::listOf(GraphQL::type("NotificationType"))
            ],
            'GetCurrent' => [
                'type' => GraphQL::type("UsersSingleResponse"),
            ]

        ];
    }
   
    public function resolveGetNotificationField(){  

        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized!', 401);

        $user = $this->isAuthorized();   // دا بيجيب الاوبجيكت بتاع اليوزر المسجل ؟ اللى هو id 66 

        $userNote = \App\User::find($user->id) ;
  
        $notification =  $userNote->unreadNotifications()->limit(1)->first();  
        if($notification){
            $notification->markAsRead();
            return [$notification] ;
        }

        return [];
  
    }

    public function resolveGetCurrentField(){ 
        return $this->resolveResponse( $this->isAuthorized());
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $UserLogin = isset($args['UserLogin']) ? $args['UserLogin'] : false;
        if($UserLogin){
          $model = $model->where($UserLogin);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }
    
    public function resolveGetAllField($root, $args, $context = null, ResolveInfo $resolveInfo = null) {  
 
        $model = app($this->modelName) ;
        if(isset($args['User'])){
          $model = $model->where($args['User']);
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
