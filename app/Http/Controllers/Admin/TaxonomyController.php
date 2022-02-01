<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Taxonomy ; 

use DB ;


class TaxonomyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $limit = $request->limit ?? 10 ;
        $type  = $request->type ?? 'category' ;

        logy( auth()->user()->id , 'index' , '' , 'category '.$type , [] );

        $result = Taxonomy::where('type' , $type)->orderBy('id' , 'DESC') ;

        if( isset($request->s[1])  ){
            $result->whereraw("CONCAT_WS( `name` , `name_en` , `description` , `description_en` ) LIKE '%".$request->s."%'" ) ;
        }

        if( isset($request->status)  ){
            $result->where( 'status' , (int) $request->status ) ;
        }

        if( isset($request->parent)  ){
            $result->where( 'parent' , (int) $request->parent ) ;
        }

        

        return response()->json( $result->paginate($limit) ) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        if( $request->id > 0 ){
            return $this->update( $request , $request->id ) ;
        }

        $type  = $request->type ?? 'category' ;

        $validate = $request->validate([
            'name' => 'required|unique:taxonomies|max:255',
        ]);

        $inputs = $request->only('name' , 'description' ,'photo' , 'parent' , 'status') ;

        $inputs['type'] = $type ;

        logy( auth()->user()->id , 'add' , '' , 'category '.$type , [] );

        $inputs['status'] = (int)$inputs['status'] ;

        $tax = Taxonomy::create($inputs) ;



        return response()->json($tax) ;
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
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        $validate = $request->validate([
            'name' => 'required|max:255',
        ]);

        $taxonomy = Taxonomy::findOrFail($id) ;

        logy( auth()->user()->id , 'edit' , '' , 'category '.$taxonomy->type , [] );

        $inputs = $request->only('name' , 'description' ,'photo' , 'parent' , 'status') ;

        // $inputs['photo'] = $this->photoUpload('photo' , 'jpg' , $request) ;

        $inputs['status'] = (int)$inputs['status'] ;

        foreach($inputs as $field => $value) {
            $taxonomy->{$field} = $value ;
        }

        $taxonomy->save() ;

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

        return response()->json($taxonomy) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $taxonomy = Taxonomy::find($id) ;
        $type = '' ;

        if($taxonomy){
            $type = $taxonomy->type ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
            $taxonomy = $taxonomy->delete() ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
            logy( auth()->user()->id , 'status' , '' , 'category '.$type , [] );
        }else{
            $taxonomy = false;
        }
        return response()->json(['res' => $taxonomy]) ;
    }

    public function active($id , Request $request)
    {
        $taxonomy = Taxonomy::find($id) ;
        if($taxonomy){
            $taxonomyO = $taxonomy ;
            $taxonomy = $taxonomy->update([ 'status' => (int) $request->status ]) ;
            logy( auth()->user()->id , 'status' , '' , 'category '.$taxonomyO->type , [] );
        }else{
            $taxonomy = false;
        }
        return response()->json(['res' => $taxonomy]) ;
    }
    

    public function parents($type = 'category' , Request $request){
        $tax = Taxonomy::where('type' , $type)->where('status' , 1 )->orderBy('id' , 'ASC')->selectRaw('id,name')->limit(1000) ;
        if( (int)$request['parent'] > 0 ){
            $tax->where('parent' , (int) $request['parent'] ) ;
        }

        if( $type == 'area' ){
            
            $user = auth()->user();

            if( $user ){
                $userrole = $user->roles ;
                $user_role = [] ;
                foreach( $userrole as $roly ){
                    $user_role[] = $roly->name ;
                }
                
                if( in_array('areamanager' , $user_role) || in_array('agent' , $user_role) ){
                    // Get User List
                    $areas = $user->Area ;
                    $listids = [] ;
                    foreach($areas as $ar){
                        $listids[] = $ar->area_id ;
                    }
                    $list->whereIn('id' , $listids ) ;
                }
            }

        }



        $taxonomy = $tax->get() ;
        if( count($taxonomy) > 0 ) {
            $prepend = array('id' => 0 , 'name' => 'None') ;
            $taxonomy->prepend( $prepend ) ;
        }
        return response()->json(  $taxonomy ) ;
    }

    public function select_list( Request $request )
    {
        $list = Taxonomy::orderBy('id' , 'DESC')->where('type' , $request->type );

        if( isset($request->s) and $request->s != '' ) {
            $list->where('name'  , 'like' , '%'.$request->s.'%' ) ;
        }
        
        if( isset($request['parent']) ) {
                if(  $request['parent'] != '' && !is_numeric($request['parent']) ){
                    $parent = trim( $request['parent'] , ',' ) ; 
                    $list->whereIn('parent' , explode(',' , $parent ) ) ;
                }elseif( $request['parent'] > 0  ){
                    $list->where('parent' , $request['parent'] ) ;
                }
        }

        if( in_array( $request->type , ['area' , 'city' , 'government' , 'country'] ) ){
            /*
            $user = auth()->user();
            if( $user ){

                $userrole = $user->roles ;

                $user_role = [] ;

                foreach( $userrole as $roly ){
                    $user_role[] = $roly->name ;
                }
                
                if( in_array('areamanager' , $user_role) || in_array('agent' , $user_role) ){
                    // Get User List
                    $areas = $user->Area ;
                    $listids = [] ;
                    foreach($areas as $ar){
                        $listids[] = $ar->area_id ;
                    }
                    $list->whereIn('id' , $listids ) ;
                }
            }
            */

            if( isset($request['id_in']) && $request['id_in'] != '' ){
                $id_in = trim( $request['id_in'] , ',' ) ; 
                $list->whereIn('id' , explode(',' , $id_in ) ) ;
            }    

        }
        DB::enableQueryLog() ;    
        $res = $list->select(['id' , 'name'] )->get() ;
        if( $request->type == 'city' ){
           // dd( DB::getQueryLog() ) ;
        }
       
        return $res ;
    }


}
