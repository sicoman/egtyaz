<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Posts ;

use Auth ;

USE DB ;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $limit = $request->limit ?? 10 ;
        $type  = $request->type ?? 'category' ;

        logy( auth()->user()->id , 'index' , '' , $type , [] );

        $result = Posts::where('type' , $type)->with('author:id,name')->orderBy('id' , 'DESC') ;

        if( isset($request->s{1})  ){
            $result->whereraw("CONCAT_WS( `title` , `description`  ) LIKE '%".$request->s."%'" ) ;
        }

        // if( $type == 'eleran' )

        if( isset($request->status)  ){
            $result->where( 'status' , (int) $request->status ) ;
        }

        return response()->json( $result->with('Category:id,name,type')->paginate($limit) ) ;
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

        $type  = $request->type ?? 'category' ;

        logy( auth()->user()->id , 'add' , '' , $type , [] );

        $validate = $request->validate([
            'title' => 'required|max:255',
        ]);

        $inputs = $request->only('title' , 'description' ,'photo' , 'file' , 'taxonomy_id' , 'status' , 'user_id' , 'parent' , 'start' , 'end' ) ;

        $inputs['type'] = $type ;

        $inputs['status'] = (int)$inputs['status'] ;

        if( is_array($inputs['description']) ){
            $skill_id = $inputs['description']['skill'] ;
            $inputs['description'] = json_encode($inputs['description']) ;
            $inputs['taxonomy_id'] = $skill_id ;
        }

        if( !isset($inputs['user_id'][1]) ){
            $inputs['user_id'] = Auth::user()->id ;
        }

        $post = Posts::create($inputs) ;

        return response()->json($post) ;
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
        $validate = $request->validate([
            'title' => 'required|max:255',
        ]);

        $post = Posts::findOrFail($id) ;

        logy( auth()->user()->id , 'edit' , '' , $post->type , [] );

        $inputs = $request->only('title' , 'description'  ,'photo' , 'file' , 'taxonomy_id' , 'status' , 'user_id' , 'parent' , 'start' , 'end' ) ;

        $inputs['status'] = (int)$inputs['status'] ;

        if( is_array($inputs['description']) ){
            $skill_id = $inputs['description']['skill'] ;
            $inputs['description'] = json_encode($inputs['description']) ;
            $inputs['taxonomy_id'] = $skill_id ;
        }

       // dd( $inputs ) ;

        foreach($inputs as $field => $value) {
            $post->{$field} = $value ;
        }

        $post->save() ;

        return response()->json($post) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Posts::find($id) ;
        $type = '' ;
        if( $post){
            $type = $post->type ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
            $post =  $post->delete() ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
        }else{
            $post = false;
        }
        logy( auth()->user()->id , 'delete' , '' , $type , [] );
        return response()->json(['res' =>  $post]) ;
    }

    public function active($id , Request $request)
    {
        $post = Posts::find($id) ;
        if( $post){
            $postO = $post ;
            $post =  $post->update([ 'status' => (int) $request->status ]) ;
            logy( auth()->user()->id , 'status' , '' , $postO->type , [] );
        }else{
            $post = false;
        }
        return response()->json(['res' => $post]) ;
    }


    public function select($type = 'attachments')
    {
        return Posts::where('type' , $type )->where('status' , 1)->select(['id' , 'title'])->whereNull('parent')->get() ;
    }


}
