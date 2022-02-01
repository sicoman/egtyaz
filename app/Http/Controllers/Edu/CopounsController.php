<?php

namespace App\Http\Controllers\Edu;

use App\Copouns;
use Illuminate\Http\Request;

use  App\Http\Controllers\Controller ;

use DB ;

class CopounsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request )
    {
        $limit = $request->limit ?? 10 ;

        logy( auth()->user()->id , 'index' , '' , 'Copouns' , [] );

        $result = Copouns::orderBy('id' , 'DESC') ;

        if( isset($request->s[1])  ){
            $result->whereraw("CONCAT_WS( `code` , `description` ) LIKE '%".$request->s."%'" ) ;
        }

        if( isset($request->status)  ){
            $result->where( 'status' , (int) $request->status ) ;
        }

        if( isset($request->finished)  ){
            $result->where( 'finished' , (int) $request->finished ) ;
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

        $validate = $request->validate([
           // 'code' => 'required|unique:Copouns|max:20',
        ]);

        $inputs = $request->only( 'code' , 'description' ,'type' , 'amount' , 'expire_date' , 'times' , 'finished' , 'status') ;

        logy( auth()->user()->id , 'add' , '' , 'Copouns' , [] );

        $inputs['status'] = (int)$inputs['status'] ;

        $create = Copouns::create($inputs) ;

        return response()->json($create) ;
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
            'code' => 'required|max:20',
        ]);

        $copoun = Copouns::findOrFail($id) ;

        logy( auth()->user()->id , 'edit' , '' , 'Copouns ', [] );

        $inputs = $request->only( 'code' , 'description' ,'type' , 'amount' , 'expire_date' , 'times' , 'finished' , 'status') ;

        $inputs['status'] = (int)$inputs['status'] ;

        foreach($inputs as $field => $value) {
            $copoun->{$field} = $value ;
        }

        $copoun->save() ;

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

        return response()->json($copoun) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $copoun = Copouns::find($id) ;

        if($copoun){
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
            $copoun = $copoun->delete() ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
            logy( auth()->user()->id , 'delete' , '' , 'Copouns' , [] );
        }else{
            $copoun = false;
        }
        return response()->json(['res' => $copoun]) ;
    }

    public function active($id , Request $request)
    {
        $copoun = Copouns::find($id) ;
        if($copoun){
            $copoun = $copoun->update([ 'status' => (int) $request->status ]) ;
            logy( auth()->user()->id , 'status' , '' , 'Copouns' , [] );
        }else{
            $copoun = false;
        }
        return response()->json(['res' => $copoun]) ;
    }

}