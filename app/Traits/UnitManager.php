<?php

namespace App\Traits;

trait UnitManager {

    public function UnitManager($obj , $join = '' , $joinCol = '' ){
        $Auth  = auth()->user() ;
        $areas = [] ;
        foreach($Auth->area as $area){
            $areas[$area->type][] = $area->area_id ; 
        }

        if( $join != '' ) {
            $obj->join('units' , 'units.id' , $joinCol ) ;
        }
        
        if( isset($areas['country']) and !empty($areas['country']) ){ $obj->whereIn('country' , $areas['country']) ; }
        if( isset($areas['government']) and !empty($areas['government']) ){ $obj->whereIn('government' , $areas['government']) ; }
        if( isset($areas['city']) and !empty($areas['city']) ){ $obj->whereIn('city' , $areas['city']) ; }
        if( isset($areas['area']) and !empty($areas['area']) ){ $obj->whereIn('area' , $areas['area']) ; }

        if( !isset($areas['country']) && !isset($areas['government']) && !isset($areas['city']) && !isset($areas['area'])  ) {
            $obj->whereRaw( ' 1 = 0 ') ;
        }

        return $obj ;
    }


}

?>