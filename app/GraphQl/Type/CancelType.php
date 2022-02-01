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

class CancelType extends GraphQLType {

    protected $attributes = [
        'name' => 'CancelType',
        'description' => 'CancelType Description'
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
,'name' => [
                    'type' => Type::string(),
                        
                ]
,'description' => [
                    'type' => Type::string(),
                        
                ]
,'name_en' => [
                    'type' => Type::string(),
                        
                ]
,'description_en' => [
                    'type' => Type::string(),
                        
                ]
,'photo' => [
                    'type' => Type::string(),
                        
                ]
                ,'before' => [
                    'type' => Type::int(),
                        
                ]
                ,'within' => [
                    'type' => Type::int(),
                        
                ]   
                   ,'within_percent' => [
                    'type' => Type::int(),
                        
                ]         
               ,'within_fee' => [
                    'type' => Type::int(),
                        
                ]                
                ,'within_minus' => [
                    'type' => Type::int(),
                        
                ]
                ,'checkin_out' => [
                    'type' => Type::int(),
                        
                ]
                 ,'checkin_minus' => [
                    'type' => Type::int(),
                        
                ]
                ,'checkin_fee' => [
                    'type' => Type::int(),
                        
                ]               
                
,'status' => [
                    'type' => Type::boolean(),
                        
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
