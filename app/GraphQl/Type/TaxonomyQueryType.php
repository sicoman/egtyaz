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


class TaxonomyQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Taxonomy';

    protected $attributes = [
        'name' => 'TaxonomyQueryType',
        'description' => 'Taxonomy Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("TaxonomySingleResponse"),
                'args' => [
                  'Taxonomy' => ['name' => 'Taxonomy', 'type' => GraphQL::type('TaxonomyInput')]
                ]
            ],
            'GetAll' => [
                'type' => GraphQL::type("TaxonomyResponse"),
                'args' => [
                  'Taxonomy' => ['name' => 'Taxonomy', 'type' => GraphQL::type('TaxonomyInput')],
                  'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
                ],
            'SearchByCountryAndCities' => [
                'type' => GraphQL::type("TaxonomyResponse"),
                'args' => [
                    'search' => ['name' => 'search', 'type' => Type::string()],
                    'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')]
                ]
            ] 
        ];
    }

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $Taxonomy = isset($args['Taxonomy']) ? $args['Taxonomy'] : false;
        if($Taxonomy){
          $model = $model->where($Taxonomy);
        }
        $res = $model->first();      
        return $this->resolveResponse($res);
    }
    
    public function resolveSearchByCountryAndCitiesField($root, $args) {  
        $model = app($this->modelName) ;
        $model = $model->whereIn('type',['government','city','country']);
        $q = $args['search'] ;  
        $model = $model->where(function($query) use($q){
            $query = $query->where('name', 'LIKE', '%'.$q.'%');
            $query = $query->orWhere('name_en', 'LIKE', '%'.$q.'%');
            $query = $query->orWhere('description', 'LIKE', '%'.$q.'%');
            $query = $query->orWhere('description_en', 'LIKE', '%'.$q.'%');
            return $query ;
        });
        $_get  = $model->first();
        if($_get){
            $_type = $_get->type ; 
            if($_type == "government"){   
              $res =  app($this->modelName)->where('parent', $_get->id)->get();
            }else{
              $res= [$_get] ;  
            }

        }else{
            $res = [];
        }     
        return $this->resolveResponse($res);
    }
    public function resolveGetAllField($root, $args) {  
        $model = app($this->modelName) ;
        if(isset($args['Taxonomy'])){
          $model = $model->where($args['Taxonomy']);
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
