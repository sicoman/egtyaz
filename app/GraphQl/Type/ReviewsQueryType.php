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


class ReviewsQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Reviews';

    protected $attributes = [
        'name' => 'ReviewsQueryType',
        'description' => 'Reviews Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("ReviewsSingleResponse"),
                'args' => [
                  'Reviews' => ['name' => 'Reviews', 'type' => GraphQL::type('ReviewsInput')]
                ]
            ],
            'GetWithUnits' => [
                'type' => GraphQL::type("ReviewsResponse"),
                'args' => [
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')],
                  'getBy' => ['name' => 'getBy', 'type' => Type::string()],
                ]
            ]  ,
            'GetAll' => [
                'type' => GraphQL::type("ReviewsResponse"),
                'args' => [
                  'Reviews' => ['name' => 'Reviews', 'type' => GraphQL::type('ReviewsInput')],
                  'getUser' => ['name' => 'getUser', 'type' => Type::boolean()],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]  ,
            'CanReviewUnit' => [  
                'type' => GraphQL::type("CanReviewType"),
                'args' => [
                  'unit_id' => ['name' => 'unit_id', 'type' => Type::int()],
                  'guest_id' => ['name' => 'guest_id', 'type' => Type::int()],
                ]
            ]     
        ];
    }

    public function resolveCanReviewUnitField($root, $args) { 
        

        if(!$this->isAuthorized())
            return false;

        $model = app($this->modelName);

        $unit_id = isset($args['unit_id']) ? $args['unit_id'] : false;

        $user_id = isset($args['guest_id']) ? $args['guest_id'] : false;            
        
        $args = ['unit_id' => $unit_id] ;    
   
        $lastBookingId = \App\Models\Booking::where('status' , 4)
        ->where( 'unit_id' , $unit_id )
        ->where('date_end' , '<=' , date('Y-m-d'))
        ;

        if( (int) $user_id > 0 ){
           $lastBookingId->where('user_id' , $user_id ) ;
        }

        $lastBookingId = $lastBookingId->orderBy('created_at','DESC')->limit(1)->first();

        if(!$lastBookingId){
            return ["id" => null , "booking_id" => null , "result" => false ];
        }
        

        if($unit_id){ 

           $args['booking_id'] = $lastBookingId->id;

           $res = $this->modelName::where($args)->whereNotNull("review")->count(); 

           if($res){
                return ["booking_id" => $lastBookingId->id , "result" => false ];
           }else{
            $resss = true ;
            if( date('Y-m-d') < $lastBookingId->date_end ){ $resss = false ; }
            return ["booking_id" => $lastBookingId->id , "result" => $resss ] ;
           }
        }
      
        return ["booking_id" => $lastBookingId->id, "result" => true];
    }



    public function resolveGetWithUnitsField($root, $args) {
   
        if(!$this->isAuthorized())
            return false;

        $model = app($this->modelName)->with('owner')->with('guest') ;   
     
        if(!isset($args['getBy'])){
            $args['getBy'] = 'guest_id';
        }else{
            if(!in_array($args['getBy'], ["guest_id", "owner_id"])){
                $args['getBy'] = 'guest_id' ;
            }
        }

        $model = $model->where($args['getBy'], $this->isAuthorized()->id) ; 

        if (!isset($args['pagination']['page'])) {
            $args['pagination']['page'] = 1;
        }

        if (!isset($args['pagination']['limit'])) {
            $args['pagination']['limit'] = 5;
        }

        if (isset($args['pagination'])) {
            $per_page = isset($args['pagination']['limit']) ? (int) $args['pagination']['limit'] : $this->responseLimit;
            $args['pagination']['page'] = isset($args['pagination']['page']) ? $args['pagination']['page'] : 1;
            $res = $model->with('Unit')->paginate($per_page, ['*'], 'page', $args['pagination']['page']);
        }    
        return $this->resolveResponse($res);
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $Reviews = isset($args['Reviews']) ? $args['Reviews'] : false;
        if($Reviews){
          $model = $model->where($Reviews);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }
    
    public function resolveGetAllField($root, $args) {   
        $model = app($this->modelName)->with('owner')->with('guest') ;   
        if(isset($args['Reviews'])){
            $normalized = [];
            foreach($args['Reviews'] as $k => $value){
                $normalized['reviews.'.$k] = $value ;
            }
          $model = $model->where($normalized);
        }

        if(isset($args['getUser'])){
            $model = $model->join('users','users.id','reviews.guest_id')->groupBy('reviews.id');
        }

        if(isset($args['rest'])){
            $model = $model->with('unit.attachments');
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
