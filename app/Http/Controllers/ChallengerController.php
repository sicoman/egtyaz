<?php

namespace App\Http\Controllers;

use App\Challenger;
use Illuminate\Http\Request;

use App\Repositories\ChallengeRepository ;

class ChallengerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $repo  ;

    public function __construct(ChallengeRepository $challenge)
    {   
        $this->repo = $challenge ;
    }

    public function index()
    {
        $challenge = $this->repo->CreateChallenge( 1 , [] , null , 10 , 300 ) ;
        if( isset($challenge->id) ){
            $users = [2,3,4,5] ;
            foreach( $users as $user ) {
                $this->repo->AddChallengers( $challenge , $user ) ;
            }
            return $challenge ;
        }
        return false ;
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
     * @param  \App\Challenger  $challenger
     * @return \Illuminate\Http\Response
     */
    public function show(Challenger $challenger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Challenger  $challenger
     * @return \Illuminate\Http\Response
     */
    public function edit(Challenger $challenger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Challenger  $challenger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Challenger $challenger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Challenger  $challenger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Challenger $challenger)
    {
        //
    }
}
