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

class AttachmentsMutationType extends AttachmentsQueryType {
    
    protected $modelName = '\App\Models\Attachments';

    protected $attributes = [
        'name' => 'AttachmentsMutationType',
        'description' => 'mutation Attachments Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("AttachmentsSingleResponse"),
                'args' => [
                  'Attachments' => ['name' => 'Attachments', 'type' => GraphQL::type('AttachmentsInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("AttachmentsSingleResponse"),
                'args' => [
                'Attachments' => ['name' => 'Attachments', 'type' => GraphQL::type('AttachmentsInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('AttachmentsInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Attachments' => ['name' => 'Attachments', 'type' => GraphQL::type('AttachmentsInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Attachments = isset($args['Attachments']) ? $args['Attachments'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Attachments);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Attachments = isset($args['Attachments']) ? $args['Attachments'] : false;
            $objects = (array) AttachmentsInput::getObjects();
            $filteredAttachments = array_filter($Attachments, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newAttachments = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewAttachments = array_filter($newAttachments, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredAttachments);
            $res = $obj->update($filteredNewAttachments); 
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
            $Attachments = isset($args['Attachments']) ? $args['Attachments'] : false;
            $newAttachments = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Attachments)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
