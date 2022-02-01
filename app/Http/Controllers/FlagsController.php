<?php

namespace App\Http\Controllers;

use App\Models\Flags;
use Illuminate\Http\Request;

use App\Traits\UnitManager ;



use DB ;

class FlagsController extends Controller
{
    use UnitManager;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10 ;

        $type  = $request->type ?? 'user' ;

        $list = Flags::orderBy('flags.id' , 'DESC')->where('flags.type' , $type) ;

        if( isset($request->flagged_id) && (int)$request->flagged_id > 0 ){ $list->where('flags.flagged_id' , (int)$request->flagged_id ) ; }

        if( isset($request->user_id) && (int)$request->user_id > 0 ){ $list->where('flags.flagged_by' , (int)$request->user_id ) ; }

        if( isset($request->status) && $request->status != '' ){ $list->where('flags.status' , (int)$request->status ) ; }

        logy( auth()->user()->id , 'index' , '' , 'flags' , [] );

        $Auth = auth()->user() ;
        
        $roles = [] ;
        foreach($Auth->roles as $rol){
            $roles[] = $rol->name ; 
        }

        if( !in_array( 'admin' , $roles ) && !in_array( 'manager' , $roles ) and $type == 'unit' ) {
            $list = $this->UnitManager($list , 1 , 'flags.flagged_id') ;
        }

        return $list->with($type)->with('by')->paginate($limit) ;

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
     * Display the specified resource.
     *
     * @param  \App\Models\Flags  $flags
     * @return \Illuminate\Http\Response
     */
    public function show(Flags $flags)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Flags  $flags
     * @return \Illuminate\Http\Response
     */
    public function edit(Flags $flags)
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
    public function update(Request $request, Flags $flags)
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
        $flag = Flags::findOrFail($id);
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        $delete = $flag->delete();
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
        logy( auth()->user()->id , 'delete' , '' , 'flags' , [] );
        return response()->json( $delete , 204);
    }

    public function active($id , Request $request)
    {
        $flag = Flags::findOrFail($id);

        try {
            $flag->update([ 'status' => (int) $request->status ]) ;
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        logy( auth()->user()->id , 'active' , '' , 'flags' , [] );
        return response()->json(null, 204);
    }
}
