<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use \App\GraphQL\Type\UnitsQueryType;
use \App\GraphQL\Type\UnitsMutationType;
use \App\GraphQL\Type\FlagsMutationType;
use \App\GraphQL\Type\WishListMutationType;
use \App\GraphQL\Type\ReviewsMutationType;
use \App\GraphQL\Type\BookingMutationType;
use \App\GraphQL\Type\BookingQueryType;
USE \EzuruCustom\Core\Traits\FlatParametersToObjs;

use App\Models\Units ;
use App\Models\Options ;
use App\Models\Days ;
use App\Models\UnitFee ;
use App\Models\Booking ;

use DB ;

class UnitsController extends UnitsQueryType
{
    use FlatParametersToObjs;
    /**  
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
       $unitParams = array_keys((new \App\GraphQL\Type\UnitsType())->fields()); 
       return $this->resolveGetAllField($root = true, $this->convert($request->toArray())->packParam($unitParams, 'Units')->mergeParams());
    }

    public function show($id , Request $request)
    {   
        $unit = Units::where('id' , (int)$id)->with('DaysList')
                    ->with('attachments')
                    ->with('rest')
                    ->with('amenities')
                    ->with('views')
                    ->with('owner')
                    ->with('Reviews.Guest')
                    ->with('fees')
                    ->with('Cpolicy')
                    ->with('badges.badge')
                    ->first() ;

                    $Xheaders = request()->header('accept-language') ;
                    $Cheaders = request()->header('accept-currency') ;
            
                    $rate = 0 ;
                    $exQuery =  Options::where('option_var' , 'USD_EGP')->first() ;
                      if( isset($exQuery->option_value) ){
                          $rate = $exQuery->option_value ;
                      }
                   
                      
            $main_currency =  $unit->currency ; 

            $unit = $unit->toArray() ; 
            
            $unit['main_currency'] = $main_currency ;
                  
            if( $Cheaders == 'usd' ) {
                        $unit['price'] = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $unit['price'] , 'usd' , $main_currency , $rate ) ;
                        $unit['currency'] = 'usd' ;
            }else{
                        $unit['price'] = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $unit['price'] , 'egp' , $main_currency , $rate ) ;
                        $unit['currency'] = 'egp' ;
            }
            
            if( !empty($unit['days_list']) ){
                foreach( $unit['days_list'] as $k=>$day ){
                    if( $Cheaders == 'usd' ) {
                        $unit['days_list'][$k]['price'] = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $day['price'] , 'usd' , $main_currency , $rate ) ;
                        $unit['days_list'][$k]['price_before'] = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $day['price_before'] , 'usd' , $main_currency , $rate ) ;
                    }else{
                        $unit['days_list'][$k]['price'] = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $day['price'] , 'egp' , $main_currency , $rate ) ;
                        $unit['days_list'][$k]['price_before'] = app('App\Http\Controllers\v1\UnitsController')->ExchangeRate( $day['price_before'] , 'egp' , $main_currency , $rate ) ;
                    }    
                } 
            }

            if( !empty($unit['amenities']) ){
                foreach( $unit['amenities'] as $k=>$tax ){
                    if( $Xheaders == 'en' ) {
                        $unit['amenities'][$k]['name']  = $unit['amenities'][$k]['name_en'];
                        $unit['amenities'][$k]['description'] = $unit['amenities'][$k]['description_en'];
                    }    
                } 
            }

            if( !empty($unit['views']) ){
                foreach( $unit['views'] as $k=>$tax ){
                    if( $Xheaders == 'en' ) {
                        $unit['views'][$k]['name']  = $unit['views'][$k]['name_en'];
                        $unit['views'][$k]['description'] = $unit['views'][$k]['description_en'];
                    }    
                } 
            }

            if( !empty($unit['rest']) ){
                foreach( $unit['rest'] as $k=>$tax ){
                    if( $Xheaders == 'en' ) {
                        $unit['rest'][$k]['name']  = $unit['rest'][$k]['name_en'];
                        $unit['rest'][$k]['description'] = $unit['rest'][$k]['description_en'];
                    }    
                } 
            }

            
            if( isset($unit['cpolicy']) ){
                if( $Xheaders == 'en' ) {
                    $unit['cpolicy']['name']  = $unit['cpolicy']['name_en'];
                    $unit['cpolicy']['description'] = $unit['cpolicy']['description_en'];
                }    

            }
             
            return $unit ;

    }

    public function bookings(Request $request){
        $params = array_keys((new \App\GraphQL\Type\BookingType())->fields()); 
     
        return (new BookingQueryType())->resolveGetAllField($root = true, $this->convert($request->toArray())->packParam($params, 'Booking')->mergeParams());

    }

    public function GetPriceFromRange(Request $request)
    {   
       return $this->resolveGetPriceFromRangeField($root = true, $request->toArray());
    }

    public function addToWishList(Request $request)
    {   
        $unitParams = array_keys((new \App\GraphQL\Type\WishListType())->fields()); 
        return (new WishListMutationType())->resolveCreateField($root = true, $this->convert($request->toArray())->packParam($unitParams, 'WishList')->mergeParams());
    } 

    public function updateOrAddDays(Request $request)
    {   
        return (new UnitsMutationType())->resolveupdateOrAddDaysField($root = true, $request->toArray());
    }

    
    public function flagUnit(Request $request)
    {    
        $unitParams = array_keys((new \App\GraphQL\Type\FlagsType())->fields()); 
        return (new FlagsMutationType())->resolveCreateField($root = true, $this->convert($request->toArray())->packParam($unitParams, 'Flags')->mergeParams());
    }


    public function addReview(Request $request)
    {   
        $unitParams = array_keys((new \App\GraphQL\Type\ReviewsType())->fields()); 
        $allItems = $request->get('Items');
        $Ratings = $Items = $Notes = [];
        if($allItems && !empty($allItems)){
            foreach($allItems as $key => $item){
                array_push($Items, $item['name']);
                array_push($Ratings, $item['rating']);
                array_push($Notes, $item['note']);
            }
        }

        $request->request->add(['Ratings' => $Ratings, 'Items' => $Items, 'Notes' => $Notes]);
   
        return (new ReviewsMutationType())->resolveCreateField($root = true, $this->convert($request->toArray())->packParam($unitParams, 'Reviews')->mergeParams());
    }
    
    public function addBooking(Request $request)
    {   
        $unitParams = array_keys((new \App\GraphQL\Type\BookingType())->fields()); 
        
        // get All By Unit It Self Prices
        $request->headers->remove('Accept-Currency');
        $request->headers->remove('accept-currency');

        $request->request->add(['start_date' => $request->date_start , 'end_date' => $request->date_end ]) ;

        // Handle Prices On Backend 

        $data = $this->IsDaysAvilable( $request , true ) ;

        // Lets Check $data to Request

        if( is_array($data) && isset( $data['unit_tax_fee'] ) && isset( $data['unit_tax_fee']['all_rent_seprate'] )  ){
            $request->request->add([
                'price' => $data['unit_tax_fee']['all_rent'] ,
                'day_price' => $data['unit_tax_fee']['all_rent_seprate']['day_price'] ,
                'fee' => $data['unit_tax_fee']['all_rent_seprate']['fee'] ,
                'ezuru_fee' => $data['unit_tax_fee']['all_rent_seprate']['ezuru'],
                'tourism' => $data['unit_tax_fee']['all_rent_seprate']['tourism'] ,
                'tax'   => $data['unit_tax_fee']['all_rent_seprate']['vat']
            ]) ;
        }

        // dd( $request->toArray() ) ;

        return (new BookingMutationType())->resolveCreateField($root = true, $this->convert($request->toArray())->packParam($unitParams, 'Booking')->mergeParams());
    }

    public function setBookingStatus(Request $request)
    {    
        return (new BookingMutationType())->resolveSetBookingStatusField($root = true, $request->toArray());
    }

    public function IsDaysAvilable(Request $request , $ignore = false){
        $unit           = $request->unit_id ;
        $currency       = 'egp' ;
        $avg_column     = 'price' ; 
        $unitCurrency   = 'egp' ;
        $locale         = 'ar' ;
        $exChange       = 0 ;
        $start          = $request->start_date;
        $end            = $request->end_date;

        $Cheaders = request()->header('accept-currency') ;
        if( isset($Cheaders) and strtolower($Cheaders) == 'usd' ){  $currency = 'usd' ;  }
        if( isset($request['locale']) and strtolower($request['locale']) == 'en' ){  $locale = 'en' ;  }
        if( isset($request['isVerified']) and $request['isVerified'] == 1 ){  $avg_column = 'price_before' ;  }

        $return = ['avg' => 0 , 'avilable' => 0 , 'unit_tax_fee' => [] ] ;

    
        // Lets Check if it's Avilable
        if( $start == $end ){
            $days_between_range = [$start] ;
            $days_between_range_count = 1;
        }else{
            $days_between_range = getDatesFromRange( $start , $end ) ;
            $days_between_range_count = count( $days_between_range ) ;
        }


        $days_avilable_count = Days::where('unit_id' , $unit)->whereIn( 'date' , $days_between_range )->where('status' , 1)->count();


        $unit_obj = Units::where('id' , $unit)->first() ;
        // Check Min && Max
        if( isset($unit_obj->id) ){
            $return['min_max'] = [
                'min' => ($days_between_range_count >= $unit_obj->min_days) ? 0 : 1 ,
                'max' => ($days_between_range_count <= $unit_obj->max_days) ? 0 : 1
            ] ;
        }

        if( $days_avilable_count < $days_between_range_count && $ignore === false ){
            return $return ;
        }

        // Get Unit Currency
        
        $unitCurrency = strtolower( $unit_obj->currency ) ;

        $days_avilable_avg = Days::where('unit_id' , $unit)->whereIn( 'date' , $days_between_range ) ;
        

        if( $ignore === false ) {
            $days_avilable_avg = $days_avilable_avg->where('status' , 1);
        }
        

        $days_avilable_avg = $days_avilable_avg->avg( $avg_column );

        $return['avg'] =  $days_avilable_avg ; // $this->ExchangeRate($days_avilable_avg , $currency , $unitCurrency , $exChange ) ;
        
        $return['avilable'] = 1 ;

        // Lets Get Fee
        $unitFee_list = UnitFee::where('unit_id' , $unit)->with('taxonomy')->get();
        $fees = [] ;

        // Set All Unit Days Price 
        $unitDaysPrice = $return['avg'] * $days_between_range_count ;
        $unitDaysPrice_fee = $unitDaysPrice ;

        $unitDaysPrice_fee_only = 0 ;

        if(!empty($unitFee_list)){
             foreach( $unitFee_list as $fee_item ){
                 if($fee_item->fee_type == 'static'){
                    $amount = $this->ExchangeRate( $fee_item->amount , $currency , $unitCurrency , $exChange ) ;
                    $amount_ = $fee_item->amount ;
                 }else{
                    $amount_ = ( $unitDaysPrice * $fee_item->amount) / 100 ;
                    $amount = $this->ExchangeRate( $amount_ , $currency , $unitCurrency , $exChange ) ; 
                 }
                 $fees[] = [ 'amount' => $amount , 'fee' => $fee_item] ;
                 $unitDaysPrice_fee = $unitDaysPrice_fee + $amount_ ;
                 $unitDaysPrice_fee_only = $unitDaysPrice_fee_only + $amount  ;
             }
        }

        

        $ezuru_fee = [] ;
        // Calculate Ezuru Fee
            if( $unit_obj->fee_static > 0 ){
                $ezuru_fee['static'] = $unit_obj->fee_static; // $this->ExchangeRate( $unit_obj->fee_static , $currency , $unitCurrency , $exChange ) ; 
            }else{
                $ezuru_fee['static'] = 0 ;
            }


            if( $unit_obj->fee > 0 ){
                $ezuru_f =  ( $unitDaysPrice * $unit_obj->fee ) / 100  ;
                $ezuru_fee['percent'] = $ezuru_f ; // $this->ExchangeRate( $ezuru_f , $currency , $unitCurrency , $exChange ) ; 
            }else{
                $ezuru_fee['percent'] = 0 ;
            }

        $unitDaysPrice_ezuru =  $unitDaysPrice_fee +  $ezuru_fee['percent'] + $ezuru_fee['static'] ;  

        $tourism_fee = [] ;

        // Calculate Tourism Fee
            if( $unit_obj->tourism_static > 0 ){
                $tourism_fee['static'] = $unit_obj->tourism_static; // $this->ExchangeRate( $unit_obj->tourism_static , $currency , $unitCurrency , $exChange ) ; 
            }else{
                $tourism_fee['static'] = 0 ;
            }

            if( $unit_obj->tourism > 0 ){
                $tourism_f =  ( $unitDaysPrice_ezuru * $unit_obj->tourism ) / 100  ;
                $tourism_fee['percent'] = $tourism_f; // $this->ExchangeRate( $tourism_f , $currency , $unitCurrency , $exChange ) ; 
            }else{
                $tourism_fee['percent'] = 0 ;
            }

            $vat_fee = [] ;
        // Calculate Vat Fee
            
            if( $unit_obj->vat > 0 ){
                $vat_f =  ( $unitDaysPrice_ezuru * $unit_obj->vat ) / 100  ;
                $vat_fee['percent'] = $vat_f; // $this->ExchangeRate( $vat_f , $currency , $unitCurrency , $exChange ) ; 
            }else{
                $vat_fee['percent'] = 0 ;
            }
        

        $all_amount = $unitDaysPrice_ezuru + $tourism_fee['static'] + $tourism_fee['percent'] + $vat_fee['percent'] ;


        // Lets Convert All To Currency
        $unitDaysPrice =  $this->ExchangeRate( $unitDaysPrice , $currency , $unitCurrency , $exChange ) ; 
        
        $ezuru_fee['static'] =  $this->ExchangeRate( $ezuru_fee['static'] , $currency , $unitCurrency , $exChange ) ; 
        $ezuru_fee['percent'] =  $this->ExchangeRate( $ezuru_fee['percent'] , $currency , $unitCurrency , $exChange ) ; 

        $tourism_fee['static'] =  $this->ExchangeRate( $tourism_fee['static'] , $currency , $unitCurrency , $exChange ) ; 
        $tourism_fee['percent'] =  $this->ExchangeRate( $tourism_fee['percent'] , $currency , $unitCurrency , $exChange ) ; 

        $vat_fee['percent'] =  $this->ExchangeRate( $vat_fee['percent'] , $currency , $unitCurrency , $exChange ) ;
        
        $return['avg'] =  $this->ExchangeRate( $return['avg'] , $currency , $unitCurrency , $exChange ) ;
        


        $return['unit_tax_fee'] = [
                'days_count' =>  $days_between_range_count ,
                'days_list' => $days_between_range ,
                'rent' => $unitDaysPrice   ,
                'fee' => $fees ,
                'ezuru' => $ezuru_fee ,
                'tourism' => $tourism_fee ,
                'vat' => $vat_fee ,
                'all_rent' => $this->ExchangeRate( $all_amount , $currency , $unitCurrency , $exChange ),
                'all_rent_seprate' => [
                    'day_price' => $return['avg'] ,
                    'rent' => $unitDaysPrice ,
                    'fee'  => $unitDaysPrice_fee_only ,
                    'ezuru' => $ezuru_fee['percent'] + $ezuru_fee['static'] ,
                    'tourism' => $tourism_fee['percent'] + $tourism_fee['static'],
                    'vat'   => $vat_fee['percent'] 
                ]
        ] ;

        
        $return['pay_price'] = $all_amount ;

        return $return ;

    }

    public function IsDaysAvilable_old(Request $request){
            $unit           = $request->unit_id ;
            $currency       = 'egp' ;
            $avg_column     = 'price' ; 
            $unitCurrency   = 'egp' ;
            $locale         = 'ar' ;
            $exChange       = 0 ;
            $start          = $request->start_date;
            $end            = $request->end_date;

            $Cheaders = request()->header('accept-currency') ;
            if( isset($Cheaders) and strtolower($Cheaders) == 'usd' ){  $currency = 'usd' ;  }
            if( isset($request['locale']) and strtolower($request['locale']) == 'en' ){  $locale = 'en' ;  }
            if( isset($request['isVerified']) and $request['isVerified'] == 1 ){  $avg_column = 'price_before' ;  }

            $return = ['avg' => 0 , 'avilable' => 0 , 'unit_tax_fee' => [] ] ;

        
            // Lets Check if it's Avilable
            if( $start == $end ){
                $days_between_range = [$start] ;
                $days_between_range_count = 1;
            }else{
                $days_between_range = getDatesFromRange( $start , $end ) ;
                $days_between_range_count = count( $days_between_range ) ;
            }


            $days_avilable_count = Days::where('unit_id' , $unit)->whereIn( 'date' , $days_between_range )->where('status' , 1)->count();


            $unit_obj = Units::where('id' , $unit)->first() ;
            // Check Min && Max
            if( isset($unit_obj->id) ){
                $return['min_max'] = [
                    'min' => ($days_between_range_count > $unit_obj->min_days) ? 0 : 1 ,
                    'max' => ($days_between_range_count < $unit_obj->max_days) ? 0 : 1
                ] ;
            }

            if( $days_avilable_count < $days_between_range_count ){
                return $return ;
            }

            // Get Unit Currency
            
            $unitCurrency = strtolower( $unit_obj->currency ) ;

            $days_avilable_avg = Days::where('unit_id' , $unit)->whereIn( 'date' , $days_between_range )->where('status' , 1)->avg( $avg_column );

            $return['avg'] =  $this->ExchangeRate($days_avilable_avg , $currency , $unitCurrency , $exChange ) ;
            
            $return['avilable'] = 1 ;

            // Lets Get Fee
            $unitFee_list = UnitFee::where('unit_id' , $unit)->with('taxonomy')->get();
            $fees = [] ;

            // Set All Unit Days Price 
            $unitDaysPrice = $return['avg'] * $days_between_range_count ;
            $unitDaysPrice_fee = $unitDaysPrice ;
            

            if(!empty($unitFee_list)){
                 foreach( $unitFee_list as $fee_item ){
                     if($fee_item->fee_type == 'static'){
                        $amount = $this->ExchangeRate( $fee_item->amount , $currency , $unitCurrency , $exChange ) ;
                     }else{
                        $amount_ = ( $unitDaysPrice * $fee_item->amount) / 100 ;
                        $amount = $this->ExchangeRate( $amount_ , $currency , $unitCurrency , $exChange ) ; 
                     }
                     $fees[] = [ 'amount' => $amount , 'fee' => $fee_item] ;
                     $unitDaysPrice_fee = $unitDaysPrice_fee + $amount ;
                     $unitDaysPrice_fee_only = $unitDaysPrice_fee_only + $amount ;
                 }
            }

            dd( $unitDaysPrice_fee_only ) ; 

            

            $ezuru_fee = [] ;
            // Calculate Ezuru Fee
                if( $unit_obj->fee_static > 0 ){
                    $ezuru_fee['static'] = $this->ExchangeRate( $unit_obj->fee_static , $currency , $unitCurrency , $exChange ) ; 
                }else{
                    $ezuru_fee['static'] = 0 ;
                }


                if( $unit_obj->fee > 0 ){
                    $ezuru_f =  ( $unitDaysPrice * $unit_obj->fee ) / 100  ;
                    $ezuru_fee['percent'] = $this->ExchangeRate( $ezuru_f , $currency , $unitCurrency , $exChange ) ; 
                }else{
                    $ezuru_fee['percent'] = 0 ;
                }

            $unitDaysPrice_ezuru =  $unitDaysPrice_fee +  $ezuru_fee['percent'] + $ezuru_fee['static'] ;  

            $tourism_fee = [] ;

            // Calculate Tourism Fee
                if( $unit_obj->tourism_static > 0 ){
                    $tourism_fee['static'] = $this->ExchangeRate( $unit_obj->tourism_static , $currency , $unitCurrency , $exChange ) ; 
                }else{
                    $tourism_fee['static'] = 0 ;
                }
    
                if( $unit_obj->tourism > 0 ){
                    $tourism_f =  ( $unitDaysPrice_ezuru * $unit_obj->tourism ) / 100  ;
                    $tourism_fee['percent'] = $this->ExchangeRate( $tourism_f , $currency , $unitCurrency , $exChange ) ; 
                }else{
                    $tourism_fee['percent'] = 0 ;
                }

                $vat_fee = [] ;
            // Calculate Vat Fee
                
                if( $unit_obj->vat > 0 ){
                    $vat_f =  ( $unitDaysPrice_ezuru * $unit_obj->vat ) / 100  ;
                    $vat_fee['percent'] = $this->ExchangeRate( $vat_f , $currency , $unitCurrency , $exChange ) ; 
                }else{
                    $vat_fee['percent'] = 0 ;
                }
            
    
            $all_amount = $unitDaysPrice_ezuru + $tourism_fee['static'] + $tourism_fee['percent'] + $vat_fee['percent'] ;


            $return['unit_tax_fee'] = [
                    'days_count' =>  $days_between_range_count ,
                    'days_list' => $days_between_range ,
                    'rent' => $unitDaysPrice ,
                    'fee' => $fees ,
                    'ezuru' => $ezuru_fee ,
                    'tourism' => $tourism_fee ,
                    'vat' => $vat_fee ,
                    'all_rent' => $all_amount ,
                    'all_rent_seprate' => [
                        'day_price' => $return['avg'] ,
                        'rent' => $unitDaysPrice ,
                        'fee'  => $unitDaysPrice_fee_only ,
                        'ezuru' => $ezuru_fee['percent'] + $ezuru_fee['static'] ,
                        'tourism' => $tourism_fee['percent'] + $tourism_fee['static'],
                        'vat'   => $vat_fee['percent'] 
                    ]
            ] ;

            if( $unitCurrency != $currency ){
                $return['pay_price'] = $this->ExchangeRate( $vat_f , $unitCurrency , $currency  , $exChange ) ;    
            }else{
                $return['pay_price'] = $all_amount ;
            }


            return $return ;

    }

    public function JustRate($amount , $currency , $rate = 0 ){
        if( $rate == 0 ){
            $exQuery =  Options::where('option_var' , 'USD_EGP')->first() ;
            if( isset($exQuery->option_value) ){
                $rate = $exQuery->option_value ;
            }
        }
        if( $currency == 'egp' ){

        }
    }

    public function ExchangeRate( $amount = 1 , $Tocurrency = 'usd' , $unitCurrency = 'egp' , $rate = 0 ){
        if( $rate == 0 ){
          $exQuery =  Options::where('option_var' , 'USD_EGP')->first() ;
          if( isset($exQuery->option_value) ){
              $rate = $exQuery->option_value ;
          }
        }
        if( $Tocurrency == 'egp' && $unitCurrency == 'egp' ) {
            return $amount ;
        }elseif( $Tocurrency == 'egp' && $unitCurrency == 'usd' ) {
            return $amount * $rate ;
        }elseif( $Tocurrency == 'usd' && $unitCurrency == 'usd' ) {
            return $amount ;
        }else{
            return $amount / $rate ;
        }
    }



    public function Payouts(Request $request){
        $booking = Booking::where('owner_id' , ( $request->user ?? 0 ) )->whereIn('status' , [
            2,3,4,5,6,-3,-2
         ])->with('unit')->with('cancel') ;

         if( isset($request->unit) && $request->unit != '' ){ $booking->where('unit_id' , $request->unit ) ; }
         if( isset($request->status) && $request->status != '' ){ $booking->where('status' , $request->status ) ; }
         
         $year = date('Y') ;  $month = date('m') ; 

         if( isset($request->year) && $request->year != '' ){  $year = $request->year ; }

         if( isset($request->month) && $request->month != '' ){  $month = $request->month ; }


         $date_start  = $year.'-'.$month.'-01' ;

         $date_end    = date( 'Y-m-t' , strtotime( $year.'-'.$month.'-01' ) )   ;

         $booking->whereBetween('date_end' , [ $date_start , $date_end ] ) ;
                
         return $booking->get() ;
}

    public function Payments(Request $request){

        $booking = Booking::with('unit')->with('cancel') ;

        if( isset($request->is_host) &&  $request->is_host == 1 && 1 == 0 ){
            $booking->where('owner_id' , ( $request->user ?? 0 ) ) ;
        }else{
            $booking->where('user_id' , ( $request->user ?? 0 ) ) ;
        }

        if( isset($request->status) && $request->status != '' ){ $booking->where('status' , $request->status ) ; }
        
        $year = date('Y') ;  $month = date('m') ; 

        if( isset($request->year) && $request->year != '' ){  $year = $request->year ; }

        if( isset($request->month) && $request->month != '' ){  $month = $request->month ; }

        $date_start  = $year.'-'.$month.'-01' ;

        $date_end    = date( 'Y-m-t' , strtotime( $year.'-'.$month.'-01' ) )   ;

        $booking->whereBetween('date_end' , [ $date_start , $date_end ] ) ;
                
        $list = $booking->get() ;
    
        return $list ;
    }

    public function ListClientPayouts(Request $request){
        $user = auth()->user()->id ;
        $request->request->add(['user' => $user]) ;
        $result = $this->Payouts($request) ;

        $return = [] ; 
        foreach($result as $booking){
             $has_cancel = false ;
             $currency = $request->currency ?? $booking->unit->currency ;
             $amount = [
                 'amount' => 
                 $this-> ExchangeRate(
                        $booking->price - ( $booking->ezuru_fee + $booking->tourism + $booking->tax ) ,
                        $currency , $booking->unit->currency
                 )    
                  ,
                 'fee'   => $this-> ExchangeRate( $booking->fee , $currency , $booking->unit->currency )
             ];

             $cancel_amount = [
                 'amount' => '' ,
                 'fee'    => '' ,
             ];

             if( !empty($booking->cancel) ){
                $has_cancel = true ;
                $cancel_amount = [
                    'amount' => 
                        $this-> ExchangeRate( ( $booking->price - ( $booking->cancel->price + $booking->cancel->ezuru_fee + $booking->cancel->tourism + $booking->cancel->tax ) )
                            , $currency , $booking->unit->currency ) ,
                    'fee'    => $this-> ExchangeRate( ($booking->fee - $booking->cancel->price_fee) , $currency , $booking->unit->currency  ) ,
                ];
             }

             $return[] = [
                 'unit' => ['title' => $booking->unit->title , 'id' => $booking->unit->id , 'currency' => $booking->unit->currency ]  ,
                 'hasCancel' => $has_cancel ,
                 'amount' =>  $amount ,
                 'cancel_amount' => $cancel_amount ,
                 'amount_status' => $booking->status ,
                 'object' => $booking
             ] ;


        }

        return $return ;
    }

    public function ListClientPayments(Request $request){
        $user = auth()->user()->id ;
        $request->request->add(['user' => $user]) ;
        $result = $this->Payments($request) ;

        $return = [] ; 
        foreach($result as $booking){
             $has_cancel = false ;
             $currency = $request->currency ?? $booking->unit->currency ;
             $amount = $this-> ExchangeRate(  $booking->price , $currency , $booking->unit->currency  ) ;

             $cancel_amount = '' ;

             if( !empty($booking->cancel) ){
                $has_cancel = true ;
                $cancel_amount = $this-> ExchangeRate(
                    ( $booking->cancel->price + $booking->cancel->ezuru_fee + $booking->cancel->tourism + $booking->cancel->tax )
                   , $currency , $booking->unit->currency ) ;
             }

             $return[] = [
                 'unit' => ['title' => $booking->unit->title , 'id' => $booking->unit->id , 'currency' => $booking->unit->currency ]  ,
                 'hasCancel' => $has_cancel ,
                 'amount' =>  $amount ,
                 'cancel_amount' => $cancel_amount ,
                 'amount_status' => $booking->status ,
                 'object' => $booking
             ] ;


        }

        return $return ;
    }

    public function ListOwnerunits(Request $request){
        $user = auth()->user()->id ;
        $request->request->add(['user' => $user]) ;
        return $this->Lunits($request) ;
   }

    public function Lunits(Request $request){
         //$user = Auth::user()->id ;
         $units = Units::where('user_id' , ( $request->user ?? 0 ) )->select('id' , 'title')->where('status' , '>' , 0) ;
         return $units->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
