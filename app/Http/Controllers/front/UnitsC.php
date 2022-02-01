<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Units;
use App\Models\Taxonomy;

use App\Models\UnitOptions ;
use App\Models\UnitDays ;
use App\Models\Days ;
use App\Models\Cancel ;
use App\Models\Attachments ;
use App\Models\UnitFee ;
use App\Models\ReviewItems ;
use App\Models\UnitPromo ;

use App\User ;

use Auth ;

use DB ;

class UnitsC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id ,Request $request)
    {

        $auth = Auth::user() ;
       
        $user = $auth->id ;

        if( $id == 0 ) {
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        
            $create = [] ;
            
            $create = Units::create([
              'title' => ''  , //str_repeat( ' ' , rand(1,20) )
              'user_id' => $user ,
              'status' => -10
            ]);   
            
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

            $id = $create->id ;
        }



        $locale = $request->locale ?? "en" ; $column_name = 'name_en' ; $column_desc = 'description_en' ;
        if( $locale == 'ar' ){ $column_name = 'name' ; $column_desc = 'description' ; }


        $unit = Units::where('id' , $id)->where('user_id' , $user )->with('attachments')->with('cPolicy')->with('days')->with('fees')->with('dates')->with('Promo')->first();
        $options = ['aminites' => [] , 'views' => [] , 'rest' => [] , 'cpolicy' => [] , 'category' => [] , 'country' => [] , 'fee' => [] ] ;
        $opts = $options ;
        $data    = Taxonomy::whereIn('type' , ['aminites'  , 'views'  , 'rest' , 'category' , 'country' , 'fee' ] )->where('status' , 1)->whereNull('parent')->get();
        foreach( $data as $tax ){
            if( $tax->type == 'cpolicy' ) {
                $options[$tax->type][$tax->id] = [$tax->{$column_name} , $tax->{$column_desc}] ;
            }else{
                $options[$tax->type][$tax->id] = $tax->{$column_name} ;
            }
        }

        

        $dataop = Cancel::where('status' , 1)->selectRaw('id,'.$column_name.' as name, '.$column_desc.' as description')->get() ;
        foreach( $dataop as $do ){
            $options['cpolicy'][$do->id] = [$do->name , $do->description] ;
        }

        $unitOptions = $unit->options ;
        $unit = $unit->toArray() ;
        $unit['options'] = $opts ; 
        foreach( $unitOptions as $opt ) {
            $unit['options'][$opt->type][] = $opt->taxonomy_id ;
    
        }

        return [ 'unit' => $unit  , 'options' => $options ] ;
    }

    public function showN($id ,Request $request)
    {

        $auth = Auth::user() ;
       
        $user = $auth->id;

        $draftUnit = Units::where('user_id', $user)->where('status', -10)->first();

        if( $id == 0 && !$draftUnit) {
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        
            $create = [] ;
            
            $create = Units::create([
              'title' => ''  , //str_repeat( ' ' , rand(1,20) )
              'user_id' => $user ,
              'status' => -10
            ]);   
            
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

            $id = $create->id ;

        }elseif ($id == 0 && $draftUnit) {
            
            $id = $draftUnit->id;
        }


        $locale = $request->locale ?? "en" ; $column_name = 'name_en' ; $column_desc = 'description_en' ;
        if( $locale == 'ar' ){ $column_name = 'name' ; $column_desc = 'description' ; }


        $unit = Units::where('id' , $id)->where('user_id' , $user )->with('attachments')->with('cPolicy')->with('days')->with('fees')->with('dates')->with('Promo')->first();
        $options = ['aminites' => [] , 'views' => [] , 'rest' => [] , 'cpolicy' => [] , 'category' => [] , 'country' => [] , 'fee' => [] ] ;
        $opts = $options ;
        $data    = Taxonomy::whereIn('type' , ['aminites'  , 'views'  , 'rest' , 'category' , 'country' , 'fee' ] )->where('status' , 1)->whereNull('parent')->get();
        foreach( $data as $tax ){
            if( $tax->type == 'cpolicy' ) {
                $options[$tax->type][$tax->id] = [$tax->{$column_name} , $tax->{$column_desc}] ;
            }else{
                $options[$tax->type][$tax->id] = $tax->{$column_name} ;
            }
        }

        

        $dataop = Cancel::where('status' , 1)->selectRaw('id,'.$column_name.' as name, '.$column_desc.' as description')->get() ;
        foreach( $dataop as $do ){
            $options['cpolicy'][$do->id] = [$do->name , $do->description] ;
        }

        $unitOptions = $unit->options ;
        $unit = $unit->toArray() ;
        $unit['options'] = $opts ; 
        foreach( $unitOptions as $opt ) {
            $unit['options'][$opt->type][] = $opt->taxonomy_id ;
    
        }

        return [ 'unit' => $unit  , 'options' => $options ] ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_old(Request $request, $id) {

        $unit = $request->except(['days' , 'attachments' ,'options','c_policy','fees']) ;

        $unitXXX = Units::where('id' , $unit['id'] )->where('user_id' , Auth::user()->id)->firstOrFail() ;

        $options = $request['options'] ;
        $attachments = $request['attachments'] ;
        $c_policy    = $request['cancle_policy'] ;
        $days = $request['days'] ;
        $fees = $request['fees'] ;

        if( isset($unit['type2']) ) {
            $unit['child_type'] = $unit['type2'] ;
            unset($unit['type2']) ;
        }


        // Delete ALL defaul Options . days , attachments
        Attachments::where('unit_id' , $unit['id'])->delete();
        UnitDays::where('unit_id' , $unit['id'])->delete();
        UnitOptions::where('unit_id' , $unit['id'])->delete();
        UnitFee::where('unit_id' , $unit['id'])->delete();
        UnitPromo::where('unit_id' , $unit['id'])->delete();

        $unitXXX->update( $unit ) ;

        foreach( $fees as $k => $v ){
                UnitFee::create([
                    'unit_id' => $unit['id'] ,
                    'fee_type' => $v['fee_type'] ,
                    'amount' => $v['amount'] ,
                    'fee_id' => $v['fee_id']
                ]) ;
        }

        foreach( $options as $k => $v ){
            foreach( $v as $vv ) {
                UnitOptions::create([
                    'unit_id' => $unit['id'] ,
                    'type' => $k ,
                    'taxonomy_id' => $vv
                ]) ;
            }
        }

        if(empty($days)){
            Days::where('unit_id' , $unit['id'])->delete();
        }

        foreach( $days as $day )
        {
            UnitDays::create([
                'unit_id' => $unit['id'] ,
                'date_start' => $day['date_start'] ,
                'date_end' => $day['date_end'] ,
                'day_price'  => $day['day_price'] ,
                'day_price_before' => $day['day_price_before']
            ]) ;

            $day_s = getDatesFromRange( $day['date_start'] , $day['date_end'] ) ;

            // foreach($day_s as $day_){
            //     Days::create([
            //         'unit_id' => $unit['id'] ,
            //         'date' => $day_ ,
            //         'price'  => $day['day_price'] ,
            //         'status' => 1
            //     ]) ;
            // }
        }
        
        foreach( $attachments as $img ){
            
            Attachments::create([
                'unit_id' => $unit['id'] ,
                'image' => $img['image'] ,
                'title' => $img['title'] ,
                'ordr'  => (int) $img['ordr'] 
            ]) ;
        }

        return $unit ;
    }

    public function update(Request $request, $id)
    {

        $unitXXX = Units::where('id' , $id )->where('user_id' , Auth::user()->id)->firstOrFail() ;
      
        $unit = $request->except(['days' , 'attachments' ,'options','c_policy','fees' , 'dates','promo']) ;
        $options = $request['options'] ;
        $attachments = $request['attachments'] ;
        $c_policy    = $request['cancle_policy'] ;
        $days = $request['days'] ;
        
        $dates = $request['dates'] ;

        $promos = $request['promo'] ;
        
        $fees = $request['fees'] ;

        if( isset($unit['type2']) ) {
            $unit['child_type'] = $unit['type2'] ;
        }
        unset($unit['type2']) ;
        if( isset($unit['locale']) ) {
            unset($unit['locale']) ;
        }

        
        if( isset($unit['child_type']) && (int)$unit['child_type'] == 0 ) { $unit['child_type'] = null ; }
        if( isset($unit['city']) &&(int)$unit['city'] == 0 ) { $unit['city'] = null ; }
        if( isset($unit['country']) &&(int)$unit['country'] == 0 ) { $unit['country'] = null ; }
        if( isset($unit['government']) &&(int)$unit['government'] == 0 ) { $unit['government'] = null ; }
        if( isset($unit['area']) && (int)$unit['area'] == 0 ) { $unit['child_type'] = null ; }


        


        /*
            if( $unit['country'] == 0 && $unit['government'] == 0 && $unit['city'] == 0 && $unit['area'] > 0 ) {
                $area = $unit['area'] ;
                $getArea = Taxonomy::where('id' , $area)->with('father.father.father')->first() ;
                if( isset($getArea->father) and $getArea->father->id > 0 ){
                    $unit['city'] = $getArea->father->id ;
                    if( isset($getArea->father->father) and $getArea->father->father->id > 0 ){
                        $unit['government'] = $getArea->father->father->id ;
                        if( isset($getArea->father->father->father) and $getArea->father->father->father->id > 0 ){
                            $unit['country'] = $getArea->father->father->father->id ;
                        }
                    }
                }

            }
        */

        // Delete ALL defaul Options . days , attachments
        Attachments::where('unit_id' , $unit['id'])->delete();
        UnitDays::where('unit_id' , $unit['id'])->where('date_end' , '>' , date('Y-m-d') )->delete() ;
        Days::where('unit_id' , $unit['id'])->where('date' , '>' , date('Y-m-d') )->delete();
        UnitOptions::where('unit_id' , $unit['id'])->delete();
        UnitFee::where('unit_id' , $unit['id'])->delete();
        UnitPromo::where('unit_id' , $unit['id'])->delete();

        Units::where('id' , $unit['id'] )->update( $unit ) ;

        foreach( $fees as $k => $v ){
                UnitFee::create([
                    'unit_id' => $unit['id'] ,
                    'fee_type' => $v['fee_type'] ,
                    'amount' => $v['amount'] ,
                    'fee_id' => $v['fee_id']
                ]) ;
        }

        if( !empty($options) ){
            foreach( $options as $k => $v ){
                foreach( $v as $vv ) {
                    UnitOptions::create([
                        'unit_id' => $unit['id'] ,
                        'type' => $k ,
                        'taxonomy_id' => $vv
                    ]) ;
                }
            }
        }

        if( !empty($promos) ){
            foreach( $promos as $promo ){
                if( !$promo['date'][0] || !$promo['date'][1] ){
                    continue;
                }
                UnitPromo::create([
                    'unit_id' => $unit['id'] ,
                    'date_start' => $promo['date'][0] ,
                    'date_end' => $promo['date'][1] ,
                    'percent'  => $promo['percent'] ,
                ]) ;
            }
        }

        if( !empty($days) ){
            foreach( $days as $day ){
                if( !$day['date_start'] || !$day['date_end'] ){
                    continue;
                }
                UnitDays::create([
                    'unit_id' => $unit['id'] ,
                    'date_start' => $day['date_start'] ,
                    'date_end' => $day['date_end'] ,
                    'day_price'  => $day['day_price'] ,
                    'day_price_before' => $day['day_price_before'] ?? $day['day_price'] 
                ]) ;
            }
        }

        

        $dates_array = [] ;
        if( !empty($dates) ){
            foreach( $dates as $day ){
                // Check if date > today
                $dates_array[] = [
                    'unit_id' => $unit['id'] ,
                    'date' => $day['date'] ,
                    'status' => $day['status'] ,
                    'price'  => $day['price'] ,
                    'price_before' => $day['price_before'] ?? $day['price']
                ] ;
                
            }
        }

        if( is_array($dates_array) && !empty($dates_array) ){
            Days::insert($dates_array) ;
        }
        
        if( !empty($attachments) ){
            foreach( $attachments as $img ){
                
                Attachments::create([
                    'unit_id' => $unit['id'] ,
                    'image' => $img['image'] ,
                    'title' => $img['title'] ,
                    'ordr'  => (int) $img['ordr'] 
                ]) ;
            }
        }

        return $unit ;
    }

    public function updateN(Request $request, $id)
    {
        $auth = Auth::user();
       
        $user_id = $auth->id;

        $unitX = Units::where('id' , $id )->where('user_id' , $user_id)->first() ;

        if ($unitX) {
            
            $unit = $request->except(['days' , 'attachments' ,'options','c_policy','fees' , 'dates','promo','_method']) ;

            foreach ($unit as $key => $value) {
                if (gettype($value) == "NULL" && $value == null) {
                    unset($unit[$key]);
                }
            }

            // dd($unit);
            $options = $request['options'] ;
            $attachments = $request['attachments'] ;
            $c_policy    = $request['cancle_policy'] ;
            $days = $request['days'] ;
            
            $dates = $request['dates'] ;

            $promos = $request['promo'] ;
            
            $fees = $request['fees'] ;

            if( isset($unit['type2']) ) {
                $unit['child_type'] = $unit['type2'] ;
            }
            unset($unit['type2']) ;
            if( isset($unit['locale']) ) {
                unset($unit['locale']) ;
            }


            
            if( isset($unit['child_type']) && (int)$unit['child_type'] == 0 ) { $unit['child_type'] = null ; }
            if( isset($unit['city']) &&(int)$unit['city'] == 0 ) { $unit['city'] = null ; }
            if( isset($unit['country']) &&(int)$unit['country'] == 0 ) { $unit['country'] = null ; }
            if( isset($unit['government']) &&(int)$unit['government'] == 0 ) { $unit['government'] = null ; }
            if( isset($unit['area']) && (int)$unit['area'] == 0 ) { $unit['area'] = null ; }


            


        /*
                if( $unit['country'] == 0 && $unit['government'] == 0 && $unit['city'] == 0 && $unit['area'] > 0 ) {
                    $area = $unit['area'] ;
                    $getArea = Taxonomy::where('id' , $area)->with('father.father.father')->first() ;
                    if( isset($getArea->father) and $getArea->father->id > 0 ){
                        $unit['city'] = $getArea->father->id ;
                        if( isset($getArea->father->father) and $getArea->father->father->id > 0 ){
                            $unit['government'] = $getArea->father->father->id ;
                            if( isset($getArea->father->father->father) and $getArea->father->father->father->id > 0 ){
                                $unit['country'] = $getArea->father->father->father->id ;
                            }
                        }
                    }

                }
        */
            // dd($unit);
            // Delete ALL defaul Options . days , attachments
            
            
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;

            Units::where('id' , $id )->update( $unit ) ;
            // dd($fees);
            if (!empty($fees)) {
                
                UnitFee::where('unit_id' , $id)->delete();
                
                foreach( $fees as $k => $v ){
                    UnitFee::create([
                        'unit_id' => $id ,
                        'fee_type' => $v['fee_type'] ,
                        'amount' => $v['amount'] ,
                        'fee_id' => $v['fee_id']
                    ]) ;
                }
            }

            if( !empty($options) ){

                UnitOptions::where('unit_id' , $id)->delete();

                foreach( $options as $k => $v ){
                    foreach( $v as $vv ) {
                        UnitOptions::create([
                            'unit_id' => $id ,
                            'type' => $k ,
                            'taxonomy_id' => $vv
                        ]) ;
                    }
                }
            }

            if( !empty($promos) ){

                UnitPromo::where('unit_id' , $id)->delete();

                foreach( $promos as $promo ){
                    if( !$promo['date'][0] || !$promo['date'][1] ){
                        continue;
                    }
                    UnitPromo::create([
                        'unit_id' => $id ,
                        'date_start' => $promo['date'][0] ,
                        'date_end' => $promo['date'][1] ,
                        'percent'  => $promo['percent'] ,
                    ]) ;
                }
            }

            if( !empty($days) ){

                UnitDays::where('unit_id' , $id)->where('date_end' , '>' , date('Y-m-d') )->delete();

                foreach( $days as $day ){
                    if( !$day['date_start'] || !$day['date_end'] ){
                        continue;
                    }
                    UnitDays::create([
                        'unit_id' => $id ,
                        'date_start' => $day['date_start'] ,
                        'date_end' => $day['date_end'] ,
                        'day_price'  => $day['day_price'] ,
                        'day_price_before' => $day['day_price_before'] ?? $day['day_price'] 
                    ]) ;
                }
            }

            

            $dates_array = [] ;
            if( !empty($dates) ){
                foreach( $dates as $day ){
                    // Check if date > today
                    $dates_array[] = [
                        'unit_id' => $id ,
                        'date' => $day['date'] ,
                        'status' => $day['status'] ,
                        'price'  => $day['price'] ,
                        'price_before' => $day['price_before'] ?? $day['price']
                    ] ;
                    
                }
            }

            if( is_array($dates_array) && !empty($dates_array) ){

                Days::where('unit_id' , $id)->where('date' , '>' , date('Y-m-d') )->delete();

                Days::insert($dates_array) ;
            }
            
            if( !empty($attachments) ){

                Attachments::where('unit_id' , $id)->delete();

                foreach( $attachments as $img ){
                    
                    Attachments::create([
                        'unit_id' => $id ,
                        'image' => $img['image'] ,
                        'title' => $img['title'] ,
                        'ordr'  => (int) $img['ordr'] 
                    ]) ;
                }
            }

            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

            
            return response()->json(['status' => 1, 'message' => $unitX]);
       
        }else{

            return response()->json(['status' => 0, 'message' => 'you do not have a permission to update this unit']);
        }
      
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    public function category(Request $request){
        $type = $request->type ?? 'category' ; $col = 'name_en' ;
        if( isset($request['locale']) && $request['locale'] == 'en' ){ $col = 'name_en' ; }
        elseif( isset($request['locale']) && $request['locale'] == 'ar' ){ $col = 'name' ; }
        
        $tax = Taxonomy::where('type' , $type)->where('status' , 1 )->orderBy('id' , 'ASC')->selectRaw('id,'.$col.' as name')->limit(1000) ;
        if( (int)$request['parent'] > 0 ){
            $tax->where('parent' , (int) $request['parent'] ) ;
        }
        $taxonomy = $tax->get() ;
        return response()->json( $taxonomy ) ;
    }



    public function filterCity(Request $request){
        if( (isset($request->type) && $request->type == 'style2') || 1 == 1 ){
            return $this->filterAll($request) ;
        }
        $word = $request->search ;
        $return = [] ;
        $cous = [] ;
        $govs = [] ;
        $tax = Taxonomy::whereIn('type' , ['country' , 'city' , 'government'])->where('status' , 1 )->whereRaw('concat(" " , name , name_en) LIKE "%'.$word.'%" ')->selectRaw('id , name , name_en ,type')->get() ;

        foreach( $tax as $row ){
            if( $row->type == 'city' ) {
                 $return[$row->id] = $row ;
            }elseif($row->type == 'country'){
                 $cous[] = $row->id ;
            }elseif($row->type == 'government'){
                $govs[] = $row->id ;
           }
        }

        if( !empty( $cous ) ) {
            $co =   Taxonomy::whereIn('parent', function($query) use ($cous) {
                $query->select('id')
                ->from( with(new Taxonomy)->getTable() )
                ->whereIn('parent', $cous )
                ->where('status', 1);
            })->selectRaw('id , name')->get(); 
            foreach($co as $r){
                $return[$r->id] = $r ;
            }
        }

        if( !empty( $govs ) ) {
            
            $co =   Taxonomy::whereIn('parent',   $govs )->selectRaw('id , name')->get(); 
            foreach($co as $r){
                $return[$r->id] = $r ;
            }
        }
        
        return array_values($return); 
    }  
    
    public function filterAll($request){
        $return = [
            'country' => [] ,
            'government' => [] ,
            'city' => [] ,
           // 'area' => []
        ] ;

        $column = 'name' ; 
        
        if( isset($request['locale'])  && $request['locale'] == 'en' ){
            $column = 'name_en' ;
        }



        $word = urldecode($request->search) ;

        
        
        $q = Taxonomy::where('type' , 'country')->where('status' , 1 )->whereRaw('concat(" " , name , name_en) LIKE "%'.$word.'%" ')->selectRaw('id , name , name_en ,type')->get() ;
        foreach($q as $r){
            $return[$r->type][] = [ 'label' => $r->{$column} , 'value' => $r->id ] ;
        }

        $q = Taxonomy::where('type' , 'government')->where('status' , 1 )
        ->whereRaw('concat(" " , name , name_en) LIKE "%'.$word.'%" ')->with('father')->get() ;
        foreach($q as $r){
            if( isset($r->father) ){
                $return[$r->type][] = [ 'label' => $r->{$column}.' - '.$r->father->{$column} , 'value' => $r->id ] ;
            }else{
                $return[$r->type][] = [ 'label' => $r->{$column} , 'value' => $r->id ] ;
            }
        }

        $q = Taxonomy::where('type' , 'city')->where('status' , 1 )
        ->whereRaw('concat(" " , name , name_en) LIKE "%'.$word.'%" ')->with('father')->get() ;
        foreach($q as $r){
            if( isset($r->father) ){
                    $return[$r->type][] = [ 'label' => $r->{$column}.' - '.$r->father->{$column} , 'value' => $r->id ] ;
            }else{
                $return[$r->type][] = [ 'label' => $r->{$column} , 'name' => $r->{$column} , 'value' => $r->id ] ;
            }
        }


        $object = [] ;
        foreach( $return as $k=>$v ){
            if( empty($v) ){ continue; }
            foreach($v as $i){
                $object[] = ['name' => $i['label'] , 'id' => $i['value'] ] ;
            }
            //$object[] = ['language' => $k , 'options' => $v] ; 
            
        }

        return $object ;
    }

}
