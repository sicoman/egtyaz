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


class AttachmentsQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Attachments';

    protected $attributes = [
        'name' => 'AttachmentsQueryType',
        'description' => 'Attachments Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("AttachmentsSingleResponse"),
                'args' => [
                  'Attachments' => ['name' => 'Attachments', 'type' => GraphQL::type('AttachmentsInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("AttachmentsResponse"),
                'args' => [
                  'Attachments' => ['name' => 'Attachments', 'type' => GraphQL::type('AttachmentsInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ]         
        ];
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $Attachments = isset($args['Attachments']) ? $args['Attachments'] : false;
        if($Attachments){
          $model = $model->where($Attachments);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }
    
    public function resolveGetAllField($root, $args) {  
        $model = app($this->modelName) ;
        if(isset($args['Attachments'])){
          $model = $model->where($args['Attachments']);
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
