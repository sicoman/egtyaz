<?php

namespace App\Http\Controllers;

use App\Models\log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10 ;

        $type  = $request->type ?? 'user' ;

        $list = log::orderBy('id' , 'DESC') ;

       //  if( isset($request->flagged_id) && (int)$request->flagged_id > 0 ){ $list->where('user_id' , (int)$request->flagged_id ) ; }

        if( isset($request->user_id) && (int)$request->user_id > 0 ){ $list->where('user_id' , (int)$request->user_id ) ; }

        if( isset($request->models) && $request->models != '' ){ $list->where('model' , $request->models ) ; }

        logy( auth()->user()->id , 'index' , '' , 'log' , [] );

        return $list->with('user')->paginate($limit) ;

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
     * @param  \App\log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(log $log)
    {
        //
    }
}
