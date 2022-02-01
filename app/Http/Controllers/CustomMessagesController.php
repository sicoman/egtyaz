<?php

namespace App\Http\Controllers;

use App\CustomMessage;
use App\User;
use Illuminate\Http\Request;

use App\Notifications\UNotify ;


class CustomMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // Send Message And Save to Custom Messages

        $users = User::whereIn( 'id' , $request->users ?? [] )->get() ;

        foreach( $users as $model ) {
            $data = clone $model ;
            $data = $data->toArray() ;
            \Notification::send( $model , new UNotify( $data , 'customMessage' , '' , $request->message , $request->title  , $request->via  ) ) ;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomMessage  $customMessage
     * @return \Illuminate\Http\Response
     */
    public function show(CustomMessage $customMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomMessage  $customMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomMessage $customMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomMessage  $customMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomMessage $customMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomMessage  $customMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomMessage $customMessage)
    {
        //
    }
}
