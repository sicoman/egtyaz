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
use App\Models\MessageText;
class MessagesMutationType extends MessagesQueryType {
    
    protected $modelName = '\App\Models\Messages';

    protected $attributes = [
        'name' => 'MessagesMutationType',
        'description' => 'mutation Messages Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("MessagesSingleResponse"),
                'args' => [
                  'Messages' => ['name' => 'Messages', 'type' => GraphQL::type('MessagesInput')],
                  'Item' => ['name' => 'Item', 'type' => GraphQL::type('MessageTextInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("MessagesSingleResponse"),
                'args' => [
                'Messages' => ['name' => 'Messages', 'type' => GraphQL::type('MessagesInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('MessagesInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Messages' => ['name' => 'Messages', 'type' => GraphQL::type('MessagesInput')]
                ]
            ] ,
            'MessageExists' => [
                'type' => GraphQL::type("MessagesSingleResponse"),
                'args' => [
                'Messages' => ['name' => 'Messages', 'type' => GraphQL::type('MessagesInput')]
                ]
            ]       
        ];
    }

    public function resolveMessageExistsField($root, $args) {

        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);

        $Messages = isset($args['Messages']) ? $args['Messages'] : false;

        $model = app($this->modelName); 

        $messageArgs = ['user_id' => $this->isAuthorized()->id, 'unit_id' => $args['Messages']['unit_id']] ;

        $isMessage = $model->where($messageArgs)->first();    
      
        return $this->resolveResponse($isMessage);
    }
 
    public function resolveCreateField($root, $args) {

        if(!$this->isAuthorized())
            return $this->resolveResponse(null, null, 'You are unauthorized for this mutation!', 401);

        $Messages = isset($args['Messages']) ? $args['Messages'] : false;

        $model = app($this->modelName); 
         
        $messageArgs = ['unit_id' => $args['Messages']['unit_id'], 'owner_id' => $args['Messages']['owner_id']] ;

    
        if(!isset($args['Messages']['user_id'])){
            $messageArgs['user_id'] =  $this->isAuthorized()->id ;
        }else {
            $messageArgs['user_id'] =  $args['Messages']['user_id'] ; 
        }

        if(isset($args['Messages']['id'])){
            $model = $model->where(['id' => $args['Messages']['id']]);
        }

        //Check if there is already a message 
        $isMessage = $model->where($messageArgs)->count();   
        
        if(!$isMessage){ 
            if( !isset($Messages['user_id']) ){
                 $Messages['user_id'] = $this->isAuthorized()->id ;
                 $args['Item']['user_id'] = $this->isAuthorized()->id ;
            }
            $res = $model->create($Messages);
        }else{  
            $res = $model->where($messageArgs)->first();   
        }

        if( isset($args['Item']['user_id']) && $args['Item']['user_id'] != $this->isAuthorized()->id ){ $args['Item']['user_id'] = $this->isAuthorized()->id ; }

        if( !isset($args['Item']['user_id']) ){ $args['Item']['user_id'] = $this->isAuthorized()->id ; }

        $message_new = new MessageText($args['Item']) ;
 
        //Create message item in here
        $res->Chat()->save($message_new);

        $res->updated_at = date('Y-m-d G:i:s');

        $res->update();
    
        // Lets Read All Messages Unreaded
        MessageText::where('message_id' , $res->id )->where('user_id' , '!=' , $args['Item']['user_id'] )->where('readed' , '0000-00-00 00:00:00')->update([
            'readed' => date('Y-m-d h:i:s') 
        ]);

        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Messages = isset($args['Messages']) ? $args['Messages'] : false;
            $objects = (array) MessagesInput::getObjects();
            $filteredMessages = array_filter($Messages, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newMessages = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewMessages = array_filter($newMessages, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredMessages);
            $res = $obj->update($filteredNewMessages); 
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
            $Messages = isset($args['Messages']) ? $args['Messages'] : false;
            $newMessages = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Messages)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
