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
use Illuminate\Support\Facades\Validator;
use \App\Models\ReviewItems;
use App\Models\Taxonomy;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UNotify;
use \App\Laravue\Models\User;

class ReviewsMutationType extends ReviewsQueryType {
    
    protected $modelName = '\App\Models\Reviews';

    protected $attributes = [
        'name' => 'ReviewsMutationType',
        'description' => 'mutation Reviews Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("ReviewsSingleResponse"),
                'args' => [
                  'Reviews' => ['name' => 'Reviews', 'type' => GraphQL::type('ReviewsInput')],
                  'Items' => ['name' => 'Items', 'type' => Type::listOf(Type::string())],
                  'Notes' => ['name' => 'Notes', 'type' => Type::listOf(Type::string())],
                  'Ratings' => ['name' => 'Ratings', 'type' => Type::listOf(Type::int())],
                  'type'    => ['name' => 'type', 'type' => Type::string()],
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("ReviewsSingleResponse"),
                'args' => [
                'Reviews' => ['name' => 'Reviews', 'type' => GraphQL::type('ReviewsInput')],
                'Items' => ['name' => 'Items', 'type' => Type::listOf(Type::string())],
                'Notes' => ['name' => 'Notes', 'type' => Type::listOf(Type::string())],
                'Ratings' => ['name' => 'Ratings', 'type' => Type::listOf(Type::int())],
                'type'    => ['name' => 'type', 'type' => Type::string()]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Reviews' => ['name' => 'Reviews', 'type' => GraphQL::type('ReviewsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) { 

        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);
 
        $model = app($this->modelName);   
        $Reviews = isset($args['Reviews']) ? $args['Reviews'] : false;
        $Ratings = isset($args['Ratings']) ? $args['Ratings'] : false;
        $Items = isset($args['Items']) ? $args['Items'] : false;
        $Notes = isset($args['Notes']) ? $args['Notes'] : false;  
 
        $unitProps = ['unit_id' => $Reviews['unit_id']] ; 
        if($args['type'] == "guest_review"){
            $unitProps['guest_id'] = $this->isAuthorized()->id  ; 
            $model = $model->whereNotNull('review');
            $Reviews['guest_id'] = $this->isAuthorized()->id ;
            $reviewFor = 'unit';
            $reviewedBy = 'user' ;
            $unitProps['booking_id'] = $args['Reviews']['booking_id'];
            $reviewTaxType = 'review_type';
        }else{  
            $unitProps['owner_id'] = $this->isAuthorized()->id  ; 
            $model = $model->whereNotNull('guest_review');
            $Reviews['owner_id'] = $this->isAuthorized()->id ;
            $reviewFor = 'guest';
            $reviewedBy = 'user' ;
            $reviewTaxType = 'review_type_guest';
        } 
        $hasReview = $model->where($unitProps)->first();
        if($hasReview){
          return $this->resolveErrors(["You already have reviewed this unit, thanks !"]);
        }
        $r_items = Taxonomy::where('type' , $reviewTaxType)->whereIn('name', $Items)->where('status',1)->pluck('id' , 'name')->toArray(); 

        $res = $this->modelName::updateOrCreate($unitProps,$Reviews); 
        
        foreach(array_keys($r_items) as $key => $item){

          $res->reviews()->save(new  ReviewItems(['type' => $item,'for' => $reviewFor,'note' => $Notes[$key], 'score' => $Ratings[$key], 'reviewed_by' => $reviewedBy]));
        }   
        if($reviewFor == "unit"){
            $user = User::find($res->owner_id) ; 
            Notification::send($user, new UNotify(['review' => $res , 'lang' => $user->lang] , 'review_add'));
        }else{
            $user = User::find($res->guest_id) ; 
            Notification::send($user, new UNotify(['review' => $res , 'lang' => $user->lang] , 'review_add'));
        }

        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        $unitProps = [];
        if($this->isAuthorized()){ 
            $model = app($this->modelName);   
            $Reviews = isset($args['Reviews']) ? $args['Reviews'] : false;
            $Ratings = isset($args['Ratings']) ? $args['Ratings'] : false;
            $Items = isset($args['Items']) ? $args['Items'] : false;
            $Notes = isset($args['Notes']) ? $args['Notes'] : false;  
            if(isset($Reviews['unit_id'])){
                $unitProps = ['unit_id' => $Reviews['unit_id']] ; 
            }
            
            if($args['type'] == "guest_review"){
                $reviewKey = 'review';
                $reviewContent =  $Reviews['review'];
                $unitProps['guest_id'] = $this->isAuthorized()->id  ; 
                $Reviews['guest_id'] = $this->isAuthorized()->id ;
                $reviewFor = 'unit';
                $reviewedBy = 'user' ;
                if(isset($Reviews['booking_id'])){
                    $unitProps['booking_id'] = $Reviews['booking_id'];
                }
                $reviewTaxType = 'review_type';
       
            }else{  
                $unitProps['owner_id'] = $this->isAuthorized()->id  ; 
                $Reviews['owner_id'] = $this->isAuthorized()->id ;
                $reviewFor = 'guest';
                $reviewedBy = 'user' ;
                $reviewKey = 'guest_review';
                $reviewContent =  $Reviews['guest_review'];
                $reviewTaxType = 'review_type_guest';
            } 
            
            $hasReview = $model->where('id', $Reviews['id'])->where($unitProps)->first();
 
            if($hasReview){

                $r_items = Taxonomy::where('type' , 'review_type')->whereIn('name', $Items)->where('status',1)->pluck('id' , 'name')->toArray(); 

                $res = $hasReview->update([$reviewKey => $reviewContent]); 
                
                $hasReview->reviews()->where('for', $reviewFor)->delete();
    
                foreach(array_keys($r_items) as $key => $item){
        
                    $hasReview->reviews()->save(new  ReviewItems(['type' => $item,'for' => $reviewFor,'note' => $Notes[$key], 'score' => $Ratings[$key], 'reviewed_by' => $reviewedBy]));
                }   
                if($reviewFor == "unit"){
                 //   $user = User::find($res->owner_id) ; 
                 //   Notification::send($user, new UNotify(['review' => $res , 'lang' => $user->lang] , 'review_add'));
                }else{
                  //  $user = User::find($res->guest_id) ; 
                  //  Notification::send($user, new UNotify(['review' => $res , 'lang' => $user->lang] , 'review_add'));
                }
            }

            return $this->resolveResponse($hasReview);

        }
        return $this->resolveErrors(["denied permission for this request"]);
    }

    public function resolveDeleteField($root, $args) {  
        if($this->isAuthorized()){
            $Reviews = isset($args['Reviews']) ? $args['Reviews'] : false;
            $newReviews = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Reviews)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
