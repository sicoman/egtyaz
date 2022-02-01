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


class BookingQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Booking';

    protected $attributes = [
        'name' => 'BookingQueryType',
        'description' => 'Booking Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
  
    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("BookingSingleResponse"),
                'args' => [
                  'Booking' => ['name' => 'Booking', 'type' => GraphQL::type('BookingInput')]
                ]
            ],
            'GetAll' => [  
                'type' => GraphQL::type("BookingResponse"),
                'args' => [
                  'Booking' => ['name' => 'Booking', 'type' => GraphQL::type('BookingInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')],
                  'StatusIn' => ['name' => 'StatusIn', 'type' => Type::listOf(GraphQL::type("BookingStatusEnumType"))]
                ]
                ],
            'HasBookedUnit' => [
                    'type' => Type::Boolean(),
                    'args' => [
                      'unit_id' => ['name' => 'unit_id', 'type' => Type::int()],
                      'user_id' => ['name' => 'user_id', 'type' => Type::int()],
                    ]
                ]     
        ];
    }


    public function resolveHasBookedUnitField($root, $args) {  

        $model = app($this->modelName);

        $unit_id = isset($args['unit_id']) ? $args['unit_id'] : false;

        $user_id = isset($args['user_id']) ? $args['user_id'] : false;

        if(!$this->isAuthorized())
            return false;
            
        
        $args = ['unit_id' => $unit_id] ;    

        if($user_id){
            $args['owner_id'] = $user_id;
        }

        $args['user_id'] = $this->isAuthorized()->id;

        if($unit_id){  
           $res = $this->modelName::where($args)->orderBy('id' , 'DESC')->first(); 

           if(isset($res->id) && $res->id > 0){

               if( $res->status < 2 ) {
                    return false ;
               }

              return true;  
           }
        }
        return false ;
    }

    public function resolveGetField($root, $args) {
      
        $model = app($this->modelName);
        $Booking = isset($args['Booking']) ? $args['Booking'] : false;
        if($Booking){
          $model = $model->where($Booking);
        }
        $res = $model->orderBy('created_at', 'DESC')->first();      
        return $this->resolveResponse($res);
    }
     
    public function resolveGetAllField($root, $args) {  
 
        $model = app($this->modelName) ; 
        if(isset($args['Booking'])){  
          $model = $model->where($args['Booking']);
        }
      
        if(isset($args['StatusIn'])){
            $model = $model->whereIn('status', $args['StatusIn']);
        }

        $model = $model->with('Unit')->with('cancel');

        $model = $model->orderBy('updated_at', 'DESC');

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
