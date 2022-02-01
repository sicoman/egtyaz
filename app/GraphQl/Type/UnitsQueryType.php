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
use App\Models\Days;
use App\Models\Options;
use App\Models\Search;

use DB ;


class UnitsQueryType extends ResponseQueryType {
    
    protected $modelName = '\App\Models\Units';

    protected $attributes = [
        'name' => 'UnitsQueryType',
        'description' => 'Units Description'
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */

    // protected $inputObject = true;

    public function fields() {
        return [
            'Get' => [
                'type' => GraphQL::type("UnitsSingleResponse"),
                'args' => [
                  'Units' => ['name' => 'Units', 'type' => GraphQL::type('UnitsInput')],
                  'StatusIn' => ['name' => 'StatusIn', 'type' => Type::listOf(Type::int())],
                ]
            ],
            'GetTaxonomy' => [
              'type' => GraphQL::type("TaxonomyResponse"),
              'args' => [
                'Unit' => ['name' => 'Unit', 'type' => GraphQL::type('UnitsInput')],
                'type' => ['name' => 'type', 'type' => Type::string()],
              ]
            ] ,
            'GetPriceFromRange' => [
              'type' => GraphQL::type('availableType'),
              'args' => [
              'unit_id' => ['name' => 'unit_id', 'type' => Type::id()],
              'start_date' => ['name' => 'start_date', 'type' => Type::string()],
              'end_date' => ['name' => 'end_date', 'type' => Type::string()]
              ]
          ] , 
            'GetAll' => [
                'type' => GraphQL::type("UnitsResponse"),
                 'args'        => [
                    'Units'      => ['name' => 'Units', 'type' => GraphQL::type('UnitsInput')],
                    'not_in'     => ['name' => 'not_in', 'type' =>Type::listOf(Type::int())],
                    'check_in'   => ['name' => 'check_in', 'type' =>Type::string()],
                    'check_out'  => ['name' => 'check_out', 'type' =>Type::string()],
                    'price_in'   => ['name' => 'price_in', 'type' => Type::listOf(Type::int())],
                    'StatusIn'   => ['name' => 'StatusIn', 'type' => Type::listOf(Type::int())],
                    'Amenities'  => ['name' => 'Amenities', 'type' => Type::listOf(Type::int())],
                    'Types'      => ['name' => 'Types', 'type' => Type::listOf(Type::int())],
                    'Rest'       => ['name' => 'Rest', 'type' => Type::listOf(Type::int())],
                    'Category'   => ['name' => 'Category', 'type' => Type::listOf(Type::int())],
                    'OrderBy'    => ['name' => "OrderBy", "type" => GraphQl::type('SortByEnumType')],
                    'pagination' => ['name' => 'pagination', 'type' => GraphQL::type('PaginationInputType')],
                    'isSearch'   => ['name' => 'isSearch', 'type' => Type::boolean()],
                    'search'     => ['name' => 'search', 'type' =>Type::string()],
                    'origin_id'  => ['name' => 'origin_id', 'type' =>Type::int()]
                ]
            ]         
        ];
    }


    public function resolveGetPriceFromRangeField($root, $args) {

      $model = app($this->modelName);

      $start = $args['start_date'] ; 

      $end = $args['end_date'] ;

      $unitid = $args['unit_id'] ;

      $days_list = getDatesFromRange( $start , $end );
    
      $user = $this->isAuthorized() ;

      $user = $this->isAuthorized() ;
      $trustWorthy =  false;
      if($user){
        $trustWorthy = ($user->email_verified_at != null && $user->mobile_verified_at != null && $user->photoid_verified_at != null);
      }

      if( $trustWorthy ){  
        $avg = Days::where('unit_id' , $unitid)->whereIn("date",$days_list)->where('status' , 1)
        ->selectRaw('IF( price_before > 0 , avg(price_before) , avg(price) )  as normalized')->first();
        $avg = $avg->normalized ;
      }else{
        $avg = Days::where('unit_id' , $unitid)->whereIn("date",$days_list)->where('status' , 1)->avg('price');
      }  
 
      $unit_days = Days::where('unit_id' , $unitid)->whereIn("date",$days_list)->where('status' , 1)->count() ;
      
      if( count($days_list) ==  $unit_days ) {
       return ['available' => 1 , 'avg' => $avg] ;
      }else{
       return ['available' => 0 , 'avg' => $avg] ;
      }
  }  

    //@todo and refactor GetAll func 
    public function resolveGetTaxonomyField($root, $args) {
      $model = app($this->modelName);
      $Units = isset($args['Units']) ? $args['Units'] : false;
      if($Units){
        $model = $model->where($Units);
      }
      $res = $model->first();      
      return $this->resolveResponse($res);
  }  

    public function resolveGetField($root, $args) {
        $model = app($this->modelName);
        $Units = isset($args['Units']) ? $args['Units'] : false;
        if($Units){
          $model = $model->where($Units);
        }

        if(isset($args['StatusIn'])){
          $model = $model->whereIn('units.status', $args['StatusIn']);
        }
        
        $res = $model->first();
        // $res->feeVat = $this->feeVat ;
        return $this->resolveResponse($res);
    } 
    

    
    public function resolveGetAllField($root, $args) {  
 
        $model = app($this->modelName) ;

        $args = array_filter($args);
       
        if(isset($args['Units'])){  

          $args['Units'] = array_filter($args['Units']);
        
          if(isset($args['Units']['guests'])){ 
            $model = $model->where('guests', '>=', $args['Units']['guests']); 
          }

          if(isset($args['Units']['max_children'])){ 
            $model = $model->where('max_children', '>=', $args['Units']['max_children']); 
          }

          if(isset($args['Units']['max_children'])){ 
            $model = $model->where('max_children', '>=', $args['Units']['max_children']); 
          }

          if(isset($args['Units']['rooms'])){ 
            $model = $model->where('rooms', '>=', $args['Units']['rooms']); 
          }

          if(isset($args['Units']['beds'])){ 
            $model = $model->where('beds', '>=', $args['Units']['beds']); 
          }

          if(isset($args['Units']['bathrooms'])){ 
            $model = $model->where('bathrooms', '>=', $args['Units']['bathrooms']); 
          }

          if(isset($args['Units']['city'])){ 
            $model = $model->where('city', '=', $args['Units']['city']); 
          }

          if(isset($args['Units']['country'])){ 
            $model = $model->where('country', '=', $args['Units']['country']); 
          }
 
          if(isset($args['Units']['government'])){ 
            $model = $model->where('government', '=', $args['Units']['government']); 
          }

          if(isset($args['Units']['area'])){ 
            $model = $model->where('area', '=', $args['Units']['area']); 
          }

          if(isset($args['Types'])){   
            $model = $model->whereIn('type', $args['Types']); 
          } 

          if(isset($args['Units']['id'])){   
            $model = $model->where('id', $args['Units']['id']); 
          } 

          if(isset($args['Units']['featured'])){   
            $model = $model->where('featured', $args['Units']['featured']); 
          } 

          if(isset($args['Units']['user_id'])){   
            $model = $model->where('user_id', $args['Units']['user_id']); 
          } 

        }

        // Types 
        if(isset($args['Types'])){   
          $model = $model->whereIn('type', $args['Types']); 
        } 
        

        //Check in and check out 
       
        if(isset($args['check_in']) || isset($args['check_out'])){
          $list_days = [] ; $list_count = 1 ;
          if( isset($args['check_in']{1}) && isset($args['check_out']{1}) )   {
               $list_days = getDatesFromRange( $args['check_in'] , $args['check_out'] ) ;
               $list_count = count($list_days) ;
          }elseif($args['check_in']{1}){
            $list_days[] = $args['check_in'] ; 
          }elseif($args['check_out']{1}){
            $list_days[] = $args['check_out'] ; 
          }

          if( !empty($list_days) ){
                $model = $model->join('days' , 'units.id' , 'unit_id')
                ->whereIn('date' , $list_days )
                ->where('days.status' , 1 )
                ->select(DB::raw(' units.*, count( days.id ) as days_avilable'));
                //->groupBy('units.id');
          }
        }

        if(isset($args['price_in'])){  
          $model = $model->whereBetween(DB::raw('units.price') , $args['price_in']);
        }

        
        if(isset($args['Amenities']) && count($args['Amenities'])){
            $amenities = $args['Amenities']; 
            $model = $model->whereHas('Amenities', function($q) use($amenities){
                $q->whereIn('taxonomies.id', $amenities);
            });
        }

        if(isset($args['Rest']) && count($args['Rest'])){
            $rest = $args['Rest']; 
            $model = $model->whereHas('Rest', function($q) use($rest){
                $q->whereIn('taxonomies.id', $rest);
            });
        }

        if(isset($args['Views']) && count($args['Views'])){
          $views = $args['Views']; 
          $model = $model->whereHas('Views', function($q) use($views){
              $q->whereIn('taxonomies.id', $rest);
          });
        }

        if(isset($args['not_in']) && count($args['not_in'])){
          $model = $model->whereNotIn('units.id', $args['not_in']);
        }

        $Coheaders = request()->header('accept-graph') ;

        if( isset($Coheaders) ) {
          $Coheader = explode('#' , $Coheaders ) ;
          call_user_func( $Coheader[0] , $Coheader[1] ) ;
        }
    
        if(!isset($args['Units']['status']) && !isset($args['StatusIn'])){    
          $model = $model->where('units.status', 1); 
        } 

        if(isset($args['StatusIn'])){
          $model = $model->whereIn('units.status', $args['StatusIn']);
        }

        if(isset($args['Units']['status'])){
          $model = $model->where('units.status', $args['Units']['status']);
        }


    
        if(isset($args['OrderBy'])){
            switch($args['OrderBy']){
                case 'DATE':
                $model =  $model->orderBy( DB::raw('units.id') , 'DESC');
                break;
                case 'DATEASC':
                $model =  $model->orderBy( DB::raw('units.id') , 'ASC');
                break;
                case 'PRICE':
                $model =  $model->orderBy( DB::raw('units.price') , 'DESC');
                break;
                case 'PRICEASC': //price
                $model =  $model->orderBy( DB::raw('units.price') , 'ASC');
                break;
                case 'FEATURED': //price
                  $model =  $model->orderBy( DB::raw('units.feature') , 'DESC');
                break;
                case 'RATED': //price
                    $model =  $model->orderBy('rate_count' , 'DESC')->orderBy('rate_score' , 'DESC');
                    break;
                case 'POPULAR':
                  /*
                    $model = $model->leftJoin("reviews", "reviews.unit_id", '=', "units.id")
                    ->selectRaw("units.*, sum(`reviews`.`score`) as scoreNum")
                    ->groupBy("units.id")
                    ->orderBy('scoreNum', "DESC");
                   */
                $model->orderBy('rate_score', "DESC")->orderBy('rate_count', "DESC");
                break;
            }

        }else{
            $model =  $model->orderBy(DB::raw('units.id') , 'DESC');
        }

        $units =  $model->with('Attachments')->withCount('Booking') ;
      

      if(!isset($args['pagination']['limit'])){
          $args['pagination']['limit'] = 10 ;
      }
   
      if(!isset($args['pagination']['page'])){
        $args['pagination']['page'] = 1 ;
      }

      if(isset($args['origin_id'])){  
        $model = $model->whereRaw('? IN (government, country, area,city)', $args['origin_id']);
      }

      $model = $model->groupBy('units.id');
 
        $per_page = isset($args['pagination']['limit']) ? (int) $args['pagination']['limit'] : $this->responseLimit;
        $args['pagination']['page'] = isset($args['pagination']['page']) ? $args['pagination']['page'] : 1;  
        $res = $model->paginate($per_page, ['*'], 'page', $args['pagination']['page']);    
      
        if(isset($args['isSearch']) && $user = $this->isAuthorized()){
          if(!isset($args['search'])){
            $args['search']  = '';
          }
          Search::create(['user_id' => $user->id, 'search' => $args['search'], 'search_query' => json_encode($args), 'count' => $res->total()]);
        } 


        $Xheaders = request()->header('accept-language') ;
        $Cheaders = request()->header('accept-currency') ;

        if( isset($Cheaders) ) {
          $rate = 0 ;
          $exQuery =  Options::where('option_var' , 'USD_EGP')->first() ;
            if( isset($exQuery->option_value) ){
                $rate = $exQuery->option_value ;
            }


          $ccc = [
            'usd' => 'egp' ,
            'egp' => 'usd'
          ];  
          
          foreach( $res as $unit ) {
            $main_currency = $unit->currency ;
            $unit->main_currency = $main_currency ;
            if( $Cheaders == 'usd' ) {
              $unit->price = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $unit->price , 'usd' , $main_currency , $rate ) ;
              $unit->currency = 'usd' ;
            }else{
              $unit->price = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $unit->price , 'egp' , $main_currency , $rate ) ;
              $unit->currency = 'egp' ;
            }
          }
        }
          
        return $this->resolveResponse($res);
    }

}
