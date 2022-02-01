<?php

namespace App\Http\Controllers\Edu;

use Illuminate\Http\Request;

use App\Packages ;
use App\Courses ;

use  App\Http\Controllers\Controller ;

use DB ;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request )
    {
        $limit = $request->limit ?? 10 ;

        logy( auth()->user()->id , 'index' , '' , 'packages' , [] );

        $result = Packages::orderBy('id' , 'DESC') ;

        if( isset($request->s[1])  ){
            $result->whereraw("CONCAT_WS( `name` , `description` ) LIKE '%".$request->s."%'" ) ;
        }

        if( isset($request->status)  ){
            $result->where( 'status' , (int) $request->status ) ;
        }

        $packages = $result->paginate($limit) ;

        foreach( $packages as $package ){
            $package->new_price = $package->new_price ;
            $package->roles = json_decode($package->roles) ;
        }

        return response()->json( $packages ) ;
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
          //  'name' => 'required|unique:packages|max:255',
        ]);

        $inputs = $request->only( 'name' , 'description' , 'price' , 'period' , 'sale_type' , 'sale_amount' , 'status' , 'points' , 'roles') ;

        logy( auth()->user()->id , 'add' , '' , 'packages' , [] );

        $inputs['status'] = (int)$inputs['status'] ;

        if( isset($inputs['roles']) && is_array($inputs['roles']) ){
            $inputs['roles'] = json_encode( $inputs['roles'] ) ;
        }

        $create = Packages::create($inputs) ;

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
            'name' => 'required|max:255',
        ]);

        $package = Packages::findOrFail($id) ;

        logy( auth()->user()->id , 'edit' , '' , 'packages ', [] );

        $inputs = $request->only('name' , 'description' ,'price' , 'period' , 'sale_type' , 'sale_amount' , 'status' , 'points' , 'roles') ;

        $inputs['status'] = (int)$inputs['status'] ;

        if( isset($inputs['roles']) && is_array($inputs['roles']) ){
            $inputs['roles'] = json_encode( $inputs['roles'] ) ;
        }

        foreach($inputs as $field => $value) {
            $package->{$field} = $value ;
        }

        $package->save() ;

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

        return response()->json($package) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $package = Packages::find($id) ;

        if($package){
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
            $package = $package->delete() ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
            logy( auth()->user()->id , 'delete' , '' , 'packages' , [] );
        }else{
            $package = false;
        }
        return response()->json(['res' => $package]) ;
    }

    public function active($id , Request $request)
    {
        $package = Packages::find($id) ;
        if($package){
            $package = $package->update([ 'status' => (int) $request->status ]) ;
            logy( auth()->user()->id , 'status' , '' , 'packages' , [] );
        }else{
            $package = false;
        }
        return response()->json(['res' => $package]) ;
    }

    public function select()
    {
        return Packages::where('status' , 1)->select(['id' , 'name'])->get() ;
    }

}
