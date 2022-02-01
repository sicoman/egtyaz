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

class MessagesType extends GraphQLType {

    protected $attributes = [
        'name' => 'MessagesType',
        'description' => 'Messages Description'
    ];
    
    protected $__fields = [];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;
    
    public function fields() {
        $this->__fields();
        return $this->__fields;
    }

    public function __fields() {
        $this->__fields = [
        'id' => [
                    'type' => Type::int(),
                        
                ]
,'unit_id' => [
                    'type' => Type::int(),
                        
                ] 
,'user_id' => [
                    'type' => Type::int(),
                        
] 
,'Guest' => [
    'type' => GraphQl::type('UsersType') ,    
]
,'Owner' => [
    'type' => GraphQl::type('UsersType') ,    
],
'Chat' => [
    'type' => GraphQl::type('MessageTextResponse'),
    'args' => [
        'pagination' =>['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')],
        'order' =>['name' => 'order', 'type' => GraphQL::type('SortEnumType')]
    ],
    'resolve' => function($obj, $args){   
       if(!isset($args['pagination'])){
           $args['pagination'] = [];
       } 
       if(!isset($args['pagination']['page'])){
        $args['pagination']['page'] = 1;
    } 
    if(!isset($args['pagination']['limit'])){
        $args['pagination']['limit'] = 1;
    }

    $model = $obj->Chat()->with('Sender') ;

    if(!isset($args['order'])){
        $args['order'] = 'DATE';
    }

    if(isset($args['order'])){
        switch($args['order']){
            case 'DATE':
               $model =  $model->orderBy('created_at' , 'DESC');
            break;
            case 'ASC':
               $model =  $model->orderBy('created_at' , 'ASC');
            break;
            case 'DESC':
               $model =  $model->orderBy('created_at' , 'DESC');
            break;
        }
    }
    
        $res = $model->paginate(min($args['pagination']['limit'], 20), ['*'], 'page', $args['pagination']['page']) ;
        
        return ['response' => $res];
  
    }
]
,'owner_id' => [
                    'type' => Type::int(),
                        
                ]
,'created_at' => [
                    'type' => Type::string(),
                        
                ]
,'updated_at' => [
                    'type' => Type::string(),
                        
                ]

        ];
    }
    


}
