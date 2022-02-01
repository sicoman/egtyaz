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

class WishListMutationType extends WishListQueryType {
    
    protected $modelName = '\App\Models\WishList';

    protected $attributes = [
        'name' => 'WishListMutationType',
        'description' => 'mutation WishList Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("WishListSingleResponse"),
                'args' => [
                  'WishList' => ['name' => 'WishList', 'type' => GraphQL::type('WishListInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("WishListSingleResponse"),
                'args' => [
                'WishList' => ['name' => 'WishList', 'type' => GraphQL::type('WishListInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('WishListInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'WishList' => ['name' => 'WishList', 'type' => GraphQL::type('WishListInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {

        if(!$this->isAuthorized()){
            return $this->resolveErrors(["denied permission for this request"]);
        }
     
        $uid = Auth::user()->id ;

        $WishList = isset($args['WishList']) ? $args['WishList'] : false;
        
        $WishList['user_id'] = $uid ;
        
        $model = app($this->modelName); 

        $result = $model->where($WishList)->first();

        if($result){
         $result->delete();
         $res = [];
        }else{
         $res = $model::create($WishList);
        }

        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $WishList = isset($args['WishList']) ? $args['WishList'] : false;
            $objects = (array) WishListInput::getObjects();
            $filteredWishList = array_filter($WishList, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newWishList = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewWishList = array_filter($newWishList, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredWishList);
            $res = $obj->update($filteredNewWishList); 
              if(!empty($relatedNew)){  
                foreach($relatedNew as $t => $related){
                    $f = array_flip(array_keys($objects));   
                    if(array_key_exists($t, $f)){ 
                        switch($objects[$t]->type){
                            case '_mtm_':
                            $p = $objects[$t]->plural;  
                             $obj->first()->$p()->sync(collect($newPost[$related])->pluck('id'));
                            break;
                        }
                    }

                }
            }
            return $this->resolveResponse($obj->first());
        }
        return $this->resolveErrors(["denied permission for this request"]);
    }

    public function resolveDeleteField($root, $args) {  
        if($this->isAuthorized()){
            $WishList = isset($args['WishList']) ? $args['WishList'] : false;
            $newWishList = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($WishList)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
