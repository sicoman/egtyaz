<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comments ;

use Auth ;

USE DB ;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $limit = $request->limit ?? 50 ;
        $type  = $request->type ;
        
        if( $type == 'undefined' ){ $type = '' ; }

        logy( auth()->user()->id , 'index' , '' , 'comments' , [] );

        $result = Comments::whereRaw('1 = 1') ;

        if( isset($request->s[1])  ){
            $result->whereraw("`comment` LIKE '%".$request->s."%'" ) ;
        }

        if( isset($request->status)  ){
            $result->where( 'status' , (int) $request->status ) ;
        }

        if( isset($request->post_id)  ){
            $result->where( 'post_id' , (int) $request->post_id ) ;
        }
        
        if( isset($type[2])  ){
            $result->join('posts' , 'comments.post_id' , 'posts.id')->where( 'type' , $type ) ;
        }

        $result->orderBy( 'comments.id' , $request->order ?? 'DESC' ) ;
    

        return response()->json( $result->with('post:id,title')->with('author:id,name,email')->paginate($limit) ) ;
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
            'comment' => 'required|max:255',
        ]);

        $post = Comments::findOrFail($id) ;

        logy( auth()->user()->id , 'edit' , '' , 'comments' , [] );

        $inputs = $request->only('user_id' ,'comment' , 'status' ) ;

        $inputs['status'] = (int)$inputs['status'] ;

        $post->update( $inputs ) ;

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

        $ids = explode(',' , trim($id , ',') ) ;

        if( count($ids) > 1 ){
            foreach( $ids as $id ){
                $this->destroy( $id ) ;
            }
            return response()->json(['res' => 1]) ;
        }else{
            $id = trim($id , ',') ;
        }

        $post = Comments::find($id) ;
        $type = '' ;
        if( $post){
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
            $post =  $post->delete() ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
        }else{
            $post = false;
        }
        logy( auth()->user()->id , 'delete' , '' , 'comments' , [] );
        return response()->json(['res' =>  $post]) ;
    }

    public function active($id , Request $request)
    {
        $post = Comments::find($id) ;
        if( $post){
            $postO = $post ;
            $post =  $post->update([ 'status' => (int) $request->status ]) ;
            logy( auth()->user()->id , 'status' , '' , 'comments' , [] );
        }else{
            $post = false;
        }
        return response()->json(['res' => $post]) ;
    }


}
