<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

use DB ;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10 ;

        $list = Badge::orderBy('id' , 'DESC')->with('badge')->with('user') ;


        if( isset($request->status) && $request->status != '' ){ $list->where('status' , (int)$request->status ) ; }

        if( isset($request->user_id) && $request->user_id != '' ){ $list->where('user_id' , (int)$request->user_id ) ; }

        logy( auth()->user()->id , 'index' , '' , 'badges' , [] ) ;

        return $list->paginate($limit) ;

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
        $id = $request['id'] ;

        $type =  'user' ;

        $object = [
            'user_id' => $request->u_u_id ,
            'badge'  => $request->badge
        ] ;


        // Check if Badge Already exists
        $count = Badge::where('user_id' , $request->user_id)->where('badge' , $request->badge)->count() ; 

        
        if ( $count > 0 ){
            return response()->json( ['error' => 'Badge Attached Before'] , 404 ) ;
        }


        if($id == 0){
            $res = Badge::create($object) ;
        }else{
            $res = Badge::where('id' , $id)->update($object) ;
        }

        return $object ;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Flags  $flags
     * @return \Illuminate\Http\Response
     */
    public function show($id , Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Flags  $flags
     * @return \Illuminate\Http\Response
     */
    public function edit($id , Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Flags  $flags
     * @return \Illuminate\Http\Response
     */
    public function update($id , Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Flags  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flag = Badge::findOrFail($id);
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        $delete = $flag->delete();
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
        logy( auth()->user()->id , 'delete' , '' , 'badges' , [] );
        return response()->json( $delete , 204);
    }

    public function active($id , Request $request)
    {
        $flag = Holidays::findOrFail($id);

        try {
            $flag->update([ 'status' => (int) $request->status ]) ;
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        logy( auth()->user()->id , 'active' , '' , 'holidays' , [] );
        return response()->json(null, 204);
    }
}
