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

class BadgeInput extends BadgeType {

    protected $attributes = [
        'name' => 'BadgeInput',
        'description' => 'Badge Description'
    ];


    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

     protected $inputObject = true;
      
     
     public function __construct($attributes = array()) {
         parent::__construct($attributes);
     }

     public static function getObjects(){
         return json_decode('[]');
     }
     
     
     public function unsetFields($arr){
         if(empty($arr))
             return ;
         $this->exclude = $arr;
         foreach($arr as $field){
             unset($this->__fields[$field]);
         }
     }

     public function attachTypes($arr){
         if(empty($arr))
             return ;
         foreach(array_filter($arr) as $k => $field){
            $this->__fields[$k] = ['type' => Type::listOf(GraphQL::type($k.'Input'))];
         }
     }
     
     public function fields() {
         parent::fields() ;
         $this->unsetFields(explode(',','badge'));
         $this->attachTypes((array) json_decode('[]'));
         return $this->__fields ;
         
     }
     
    



}
