<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\GraphQL\Type\PostsQueryType;
USE \EzuruCustom\Core\Traits\FlatParametersToObjs;

class PostsController extends PostsQueryType
{
    use FlatParametersToObjs;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $postsParams = array_keys((new \App\GraphQL\Type\PostsType())->fields()); 
       $return = $this->resolveGetAllField($root = true, $this->convert($request->toArray())->packParam($postsParams, 'Posts')->mergeParams());
    
       if( $request->type == 'popular_places' || $request->type == 'tourist_places' ) {
        
            $response = $return['response']->toArray() ;

            $data = $response['data'] ;

            $new_data = [] ;

            $Xheaders = request()->header('accept-language') ;

            foreach( $data as $k => $post ){

                $desciption = (array) json_decode($post['description']) ; unset( $post['description'] ) ;
                $post['origin_id'] = 0 ;

            
                if( isset($desciption['area']) ){
                    $post['origin_id'] = $desciption['area'] ;
                }elseif( isset($desciption['city']) ){
                    $post['origin_id'] = $desciption['city'] ;
                }elseif( isset($desciption['goverment']) ){
                    $post['origin_id'] = $desciption['goverment'] ;
                }elseif( isset($desciption['country']) ){
                    $post['origin_id'] = $desciption['country'] ;
                }

                $post['description'] = $desciption['title'] ;

                $post['description_en'] = $desciption['title_en'] ;

                
                
                if( $Xheaders == 'en' ){
                    $post['title'] = $post['title_en'] ;
                    $post['description'] = $post['description_en'] ; 
                }

                $new_data[] = $post ;

            }


            $return['response'] = ['data' => $new_data] ;

       }

       return $return ;

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
