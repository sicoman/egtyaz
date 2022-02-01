<?php

namespace App\Http\Controllers;

use App\Courses;
use App\CourseItems ;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB ;

use Illuminate\Support\Facades\View ;

use Auth ;

class CoursesController extends Controller
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

        $result = Courses::with('author:id,name')->orderBy('id' , 'DESC') ;

        if( isset($request->s{1})  ){
            $result->whereraw("CONCAT_WS( `title` , `description`  ) LIKE '%".$request->s."%'" ) ;
        }

        if( isset($request->status)  ){
            $result->where( 'status' , (int) $request->status ) ;
        }

        return response()->json( $result->with('Category:id,name,type')->with('items')->paginate($limit) ) ;
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

        logy( auth()->user()->id , 'add' , '' , 'courses' , [] );

        $validate = $request->validate([
            'title' => 'required|max:255',
        ]);

        $inputs = $request->only('title' , 'description' ,'photo' , 'file' , 'taxonomy_id' , 'status' , 'user_id' , 'price' ) ;

        $inputs['status'] = (int)$inputs['status'] ;

        if( !isset($inputs['user_id'][1]) ){
            $inputs['user_id'] = Auth::user()->id ;
            $inputs['author_id'] = Auth::user()->id ;
        }else{
            $inputs['author_id'] = $inputs['user_id'] ;
        }

        unset( $inputs['user_id'] ) ;

        $post = Courses::create($inputs) ;
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;


        if( $post->id > 0 && !empty( $request->items ) ) {
            foreach( $request->items as $item ){
                if( is_array($item) ){ $item = (object) $item ; }
                CourseItems::create([
                    'course_id' => $post->id ,
                    'title'        => $item->text ?? $item->title  ,
                    'is_free'      => $item->is_free  ?? 1 
                ]) ;
            }
        }
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

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
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;

        $course = Courses::findOrFail($id) ;

        logy( auth()->user()->id , 'edit' , '' , 'courses' , [] );

        $inputs = $request->only('title' , 'description'  ,'photo' , 'file' , 'taxonomy_id' , 'status' , 'user_id' , 'price' ) ;

        $inputs['status'] = (int)$inputs['status'] ;
    
        foreach($inputs as $field => $value) {
            if($field == 'user_id'){
                $course->author_id = $value ;
            }else{
                $course->{$field} = $value ;
            }
            
        }

        $course->save() ;

        if( $course->id > 0 && !empty( $request->items ) ) {
            CourseItems::where('course_id' ,  $course->id )->delete();
            foreach( $request->items as $item ){
                if( is_array($item) ){ $item = (object) $item ; }
                CourseItems::create([
                    'course_id' => $course->id ,
                    'title'        => $item->title  ,
                    'is_free'      => $item->is_free  ?? 1 
                ]) ;
            }
        }

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;


        return response()->json($course) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Courses::find($id) ;
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
        $post = Courses::find($id) ;
        if( $post){
            $postO = $post ;
            $post =  $post->update([ 'status' => (int) $request->status ]) ;
            logy( auth()->user()->id , 'status' , '' , $postO->type , [] );
        }else{
            $post = false;
        }
        return response()->json(['res' => $post]) ;
    }


    public function select()
    {
        return Courses::where('status' , 1)->select(['id' , 'title'])->get() ;
    }


}
