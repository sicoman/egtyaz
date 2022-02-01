<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Points;
use App\User;
use Illuminate\Http\Request;
use App\Traits\UnitManager ;
 
use DB ;

class PaymentsController extends Controller
{
    use UnitManager ;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = 10 ; if( $request->limit > 0 ){ $limit = $request->limit ; }

        $Payments = Payments::with('user:id,name');

        if( isset($request->type) ){
            $Payments->where('type' , $request->type )->with( $request->type ) ;

        }else{
            $Payments->where('type' , 'package' )->with('package') ;
        }

        if( isset($request->user_id) && (int)$request->user_id > 0 ){ $Payments->where('payments.user_id' , (int)$request->user_id ) ; }

        if( isset($request->package_id) && (int)$request->package_id > 0 ){ $Payments->where('payments.package_id' , (int)$request->package_id ) ; }

        if( isset($request->created) ){  $Payments->whereBetween('payments.created_at' , $request->date ) ; }

        if( isset($request->gateway) ){ $Payments->where('payments.gateway' , $request->gateway ) ; }

        if( isset($request->status) && $request->status != '' ){ $Payments->where('payments.status' , (int)$request->status ) ; }

        if( isset($request->is_paid) && $request->is_paid != '' ){ $Payments->where('payments.is_paid' , (int)$request->is_paid ) ; }

        $Auth = auth()->user() ;
        
        $roles = [] ;
        foreach($Auth->roles as $rol){
            $roles[] = $rol->name ; 
        }


        $list = $Payments->orderBy('payments.created_at', 'DESC')->paginate( $limit );

        logy( auth()->user()->id , 'index' , '' , 'payments' , [] );

        return $list ;
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
        $inputs = $request->all() ;

        $inputs['is_paid'] = $inputs['status'] ;

        $inputs['created_at'] = date('Y-m-d h:i:s' , strtotime($inputs['date']) ) ;

        unset($inputs['date']) ;

        Payments::create($inputs) ;

        return $inputs ;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function show(Payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function edit(Payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function update($id = 0 , Request $request)
    {
        $inputs = $request->all() ;

        $inputs['created_at'] = date('Y-m-d h:i:s' , strtotime($inputs['created_at']) ) ;

        Points::create($inputs) ;

        $user = User::find( $inputs['user_id'] ) ;

        if( $inputs['type'] == 'deposit' ){
            $user->points = $user->points + $inputs['points'] ;   
        }else{
            $user->points = $user->points - $inputs['points'] ; 
        }

        $user->save() ;

        return $inputs ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payments  $units
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        logy( auth()->user()->id , 'delete' , '' , 'payments' , [] );
        $payment = Payments::findOrFail($id);
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        $delete = $payment->delete();
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

        
        return response()->json( $delete , 204);
    }

    public function active($id , Request $request)
    {
        logy( auth()->user()->id , 'active' , '' , 'payments' , [] );
        $payment = Payments::findOrFail($id);

        try {
            $payment->update([ 'status' => (int) $request->status ]) ;
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        
        return response()->json(null, 204);
    }
}
