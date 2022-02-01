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
use App\GraphQL\Type\ResponseType;

class AttachmentsResponse extends ResponseType {

    protected $attributes = [
        'name' => 'AttachmentsResponse',
        'description' => 'Attachments Description'
    ];
    
    public $isMulti = true ;

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;
    protected $responseType = 'AttachmentsType';

    


}
