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
use App\Models\Taxonomy;


class UsersType extends GraphQLType {

    protected $attributes = [
        'name' => 'UsersType',
        'description' => 'User descriptior'
    ];
    
    protected $__fields = [];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

   // protected $inputObject = true;
    
    public function __fields()
    {
        $this->__fields = [
            'id' => [
                'type' => Type::ID()
            ],
            'name' => [
                'type' => Type::string()
            ],
            'accept' => [
                'type' => Type::boolean()
            ],
            'trustworthy' => [
                'type' => Type::boolean(),
                'resolve' => function($user){
                    return ($user->email_verified_at != null && $user->mobile_verified_at != null && $user->photoid_verified_at != null);
                }
            ],
            'notifications' => [
                'type' => Type::listOf(GraphQl::type("NotificationType")),
                'resolve' => function($user){
                    return $user->unreadNotifications ;
                }
            ],
            'unitsCount' => [
                'type' => Type::Int(),
                'resolve' => function($user){
                     $units = $user->Units()->count();
                     return (int) $units ; 
                }
            ],
            'password' => [
                'type' => Type::string(),
                'resolve'=> function(){
                    return "Access denied";
                }
            ],
            'email' => [
                'type' => Type::string()
            ],
            'mobile' => [
                'type' => Type::string()
            ],
            'photoid' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string(),
            ],
            'avatar' => [
                'type' => Type::string(),
            ],
            'token' => [
                'type' => Type::string(),
            ] 
            , 'created_at' => [
                'type' => Type::string(),
                'resolve' => function($carbon) {
                    return (string) $carbon['created_at'];
                }
            ],
            'email_verified_at' => [
                'type' => Type::string(),
            ] ,
            'mobile_verified_at' => [
                'type' => Type::string(),
            ] ,
            'photoid_verified_at' => [
                'type' => Type::string(),
            ] ,
            'city' => [
                'type' => Type::int(),
            ],
            '_city' => [
                'type' => GraphQl::type("TaxonomyType"),
                'resolve' => function($user){
                   return $user->city()->first() ;
                }
            ],
            'lang' => [
                'type' => Type::string()
            ],
            'badges' => [
                'type' => Type::listOf(GraphQl::type("TaxonomyType")),
                'resolve' => function($inputObject){
                   $result = Taxonomy::join('badges', 'badges.badge', 'taxonomies.id')->where('badges.u_u_id', $inputObject->id)->get();
                   return $result ;
                }
            ],  
            'rate' => [
                'type'   => GraphQl::type("RatesType"),
                'resolve' => function($inputObject){
                    $rate =  $inputObject->rate ;  
                    return [
                        'counter' => (int) $rate['c'],
                        'score'   => (float) $rate['score']
                    ];
                }
            ],
        ]; ; 
    }

    public function fields() {
        $this->__fields();
        return $this->__fields;
    }

}
