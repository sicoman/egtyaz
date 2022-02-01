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

class TaxonomyMutationType extends TaxonomyQueryType {
    
    protected $modelName = '\App\Models\Taxonomy';

    protected $attributes = [
        'name' => 'TaxonomyMutationType',
        'description' => 'mutation Taxonomy Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Create' => [
                'type' => GraphQL::type("TaxonomySingleResponse"),
                'args' => [
                  'Taxonomy' => ['name' => 'Taxonomy', 'type' => GraphQL::type('TaxonomyInput')]
                ]
            ],
            'Update' => [
                'type' => GraphQL::type("TaxonomySingleResponse"),
                'args' => [
                'Taxonomy' => ['name' => 'Taxonomy', 'type' => GraphQL::type('TaxonomyInput')],
                'New' => ['name' => 'New', 'type' => GraphQL::type('TaxonomyInput')]
                ]
            ],
            'Delete' => [
                'type' => GraphQL::type("BooleanReportSingleResponse"),
                'args' => [
                'Taxonomy' => ['name' => 'Taxonomy', 'type' => GraphQL::type('TaxonomyInput')]
                ]
            ]       
        ];
    }
 
    public function resolveCreateField($root, $args) {
        $Taxonomy = isset($args['Taxonomy']) ? $args['Taxonomy'] : false;
        
        $model = app($this->modelName); 
        $res = $model::create($Taxonomy);
        return $this->resolveResponse($res);
    }
    
    public function resolveUpdateField($root, $args) {   
        if($this->isAuthorized()){
            $Taxonomy = isset($args['Taxonomy']) ? $args['Taxonomy'] : false;
            $objects = (array) TaxonomyInput::getObjects();
            $filteredTaxonomy = array_filter($Taxonomy, function($kk) use($objects){ if(in_array($kk,array_keys($objects)) ){ return false;} return true;},ARRAY_FILTER_USE_KEY);  
            $newTaxonomy = isset($args['New']) ? $args['New'] : false;
            $relatedNew = [];
            $filteredNewTaxonomy = array_filter($newTaxonomy, function($kk) use($objects, &$relatedNew){ if(in_array($kk,array_keys($objects)) ){ $relatedNew[$kk] = $kk; return false;} return true;},ARRAY_FILTER_USE_KEY); 
            $obj = $this->modelName::where($filteredTaxonomy);
            $res = $obj->update($filteredNewTaxonomy); 
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
            $Taxonomy = isset($args['Taxonomy']) ? $args['Taxonomy'] : false;
            $newTaxonomy = isset($args['New']) ? $args['New'] : false;
            $res = $this->modelName::where($Taxonomy)->delete(); 
            return $this->resolveResponse($res);
        }

        return $this->resolveErrors(["denied permission for this request"]);
    }

}
