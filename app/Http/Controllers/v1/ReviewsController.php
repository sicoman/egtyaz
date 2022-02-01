<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use \App\GraphQL\Type\ReviewsMutationType;
use \App\GraphQL\Type\ReviewsQueryType;
USE \EzuruCustom\Core\Traits\FlatParametersToObjs;

class ReviewsController extends ReviewsQueryType
{
    use FlatParametersToObjs;
    /**  
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $request->request->add(['rest' => true]) ; 
        $params = array_keys((new \App\GraphQL\Type\ReviewsType())->fields()); 
       return (new ReviewsQueryType())->resolveGetAllField($root = true, $this->convert($request->toArray())->packParam($params, 'Reviews')->mergeParams());
    }

    public function canReviewUnitField(Request $request)
    {   
       return (new ReviewsQueryType())->resolveCanReviewUnitField($root = true, $request->toArray());
    }


    public function getWithUnitsField(Request $request)
    {   
        $params = array_keys((new \App\GraphQL\Type\ReviewsType())->fields()); 
       return (new ReviewsQueryType())->resolveGetWithUnitsField($root = true, $this->convert($request->toArray())->mergeParams());
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
